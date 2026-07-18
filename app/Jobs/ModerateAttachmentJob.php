<?php

namespace App\Jobs;

use App\Model\Attachment;
use App\Providers\NotificationServiceProvider;
use App\Services\Moderation\RekognitionModerationService;
use App\Settings\AISettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Trovador — T8. Runs Rekognition moderation for one attachment asynchronously,
 * updates its moderation_status, and notifies the owner in real time.
 *
 * Resilience (per approved proposal):
 *   - exponential backoff 30s / 2min / 5min
 *   - 3 attempts, then permanent failure -> user notified + logged
 *   - 5-minute global timeout per attempt
 */
class ModerateAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout;
    public int $tries;

    public function __construct(public string $attachmentId)
    {
        $this->tries   = (int) config('rekognition.job.tries', 3);
        $this->timeout = (int) config('rekognition.job.timeout', 300);
    }

    /**
     * Exponential backoff between retries.
     */
    public function backoff(): array
    {
        return config('rekognition.job.backoff', [30, 120, 300]);
    }

    public function handle(RekognitionModerationService $moderator): void
    {
        $attachment = Attachment::find($this->attachmentId);
        if (! $attachment) {
            return; // deleted before we got to it
        }

        $attachment->increment('moderation_attempts');

        // Kill switch — approve without spending an AWS call.
        if (! $moderator->isEnabled()) {
            $this->apply($attachment, 'approved', 0.0, []);
            return;
        }

        $result = $moderator->moderate($attachment);
        $this->apply(
            $attachment,
            $result['decision'],
            $result['score'],
            $result['labels']
        );
    }

    /**
     * Persist the outcome and broadcast it to the owner.
     */
    private function apply(Attachment $attachment, string $decision, float $score, array $labels): void
    {
        $attachment->forceFill([
            'moderation_status' => $decision,
            'moderation_score'  => $score,
            'moderation_labels' => $labels,
            'moderated_at'      => now(),
        ])->save();

        $this->notify($attachment, $decision, $score);
    }

    private function notify(Attachment $attachment, string $status, float $score): void
    {
        // Respect the admin "notify user" toggle.
        try {
            if (! app(AISettings::class)->moderation_notify_user) {
                return;
            }
        } catch (\Throwable $e) {
            // settings unavailable -> default to notifying
        }

        $user = $attachment->user;
        if (! $user || ! $user->username) {
            return;
        }

        $messages = [
            'approved'       => __('Tu contenido fue publicado correctamente.'),
            'pending_review' => __('Tu contenido está siendo revisado por nuestro equipo. Te avisaremos en breve.'),
            'rejected'       => __('Tu contenido no cumple con nuestras políticas y no fue publicado.'),
            'failed'         => __('No pudimos procesar este archivo. Inténtalo de nuevo o contacta a soporte.'),
        ];

        // Publish on the user's private channel (named by username), the same
        // convention the frontend already listens on (see Websockets.js).
        NotificationServiceProvider::publishRawEvent($user->username, 'attachment-moderated', [
            'attachmentId' => (string) $attachment->id,
            'status'       => $status,
            'score'        => $score,
            'message'      => $messages[$status] ?? $status,
        ]);
    }

    /**
     * Called by the queue after the final attempt fails.
     */
    public function failed(?Throwable $e): void
    {
        Log::error('Rekognition moderation failed permanently', [
            'attachment_id' => $this->attachmentId,
            'error'         => $e?->getMessage(),
        ]);

        $attachment = Attachment::find($this->attachmentId);
        if ($attachment) {
            $attachment->forceFill([
                'moderation_status' => 'failed',
                'moderated_at'      => now(),
            ])->save();

            $this->notify($attachment, 'failed', 0.0);
        }
    }
}
