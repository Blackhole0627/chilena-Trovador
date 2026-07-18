<?php

namespace App\Services\Moderation;

use App\Model\Attachment;
use App\Providers\AttachmentServiceProvider;
use App\Settings\AISettings;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

/**
 * Trovador — T8. Wraps AWS Rekognition DetectModerationLabels and maps the
 * result to one of three outcomes using admin-configurable thresholds.
 *
 * Result shape returned by moderate():
 *   [
 *     'decision' => 'approved'|'pending_review'|'rejected',
 *     'score'    => float,      // highest moderation confidence found (0-100)
 *     'labels'   => array,      // [ ['name'=>..., 'confidence'=>...], ... ]
 *   ]
 */
class RekognitionModerationService
{
    private ?RekognitionClient $client = null;

    /**
     * Moderate a stored attachment. Images are checked directly; videos are
     * sampled frame-by-frame with FFmpeg and the worst frame wins.
     */
    public function moderate(Attachment $attachment): array
    {
        $type = AttachmentServiceProvider::getAttachmentType($attachment->type);

        if ($type === 'image') {
            $bytes = $this->readAttachmentBytes($attachment);
            return $this->decide($this->detect($bytes));
        }

        if ($type === 'video') {
            return $this->decide($this->moderateVideo($attachment));
        }

        // Audio / regular files are never moderated visually.
        return ['decision' => 'approved', 'score' => 0.0, 'labels' => []];
    }

    /**
     * Call Rekognition on raw image bytes and return the flat list of labels.
     */
    private function detect(string $bytes): array
    {
        $minConfidence = $this->thresholds()['review'];

        $result = $this->client()->detectModerationLabels([
            'Image'         => ['Bytes' => $bytes],
            'MinConfidence' => $minConfidence,
        ]);

        $labels = [];
        foreach ($result->get('ModerationLabels') ?? [] as $label) {
            // Skip parent taxonomy rows without their own confidence signal.
            if (empty($label['Name'])) {
                continue;
            }
            $labels[] = [
                'name'       => $label['Name'],
                'parent'     => $label['ParentName'] ?? null,
                'confidence' => (float) ($label['Confidence'] ?? 0),
            ];
        }

        return $labels;
    }

    /**
     * Sample N evenly-spaced frames from a video and moderate each, keeping
     * the labels from the most explicit frame.
     */
    private function moderateVideo(Attachment $attachment): array
    {
        $samples = max(1, (int) config('rekognition.video_frame_samples', 5));
        $localVideo = $this->downloadToTmp($attachment);

        $worst = [];
        $worstScore = -1.0;

        try {
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open($localVideo);
            $durationSec = (float) $video->getStreams()->videos()->first()->get('duration', 0);

            for ($i = 1; $i <= $samples; $i++) {
                $ts = $durationSec > 0 ? ($durationSec * $i / ($samples + 1)) : 0;
                $framePath = $localVideo.'.frame'.$i.'.jpg';
                $video->frame(TimeCode::fromSeconds($ts))->save($framePath);

                $labels = $this->detect(file_get_contents($framePath));
                $score = $this->maxConfidence($labels);
                if ($score > $worstScore) {
                    $worstScore = $score;
                    $worst = $labels;
                }
                @unlink($framePath);
            }
        } finally {
            @unlink($localVideo);
        }

        return $worst;
    }

    /**
     * Map a flat label list to a decision using current thresholds.
     */
    private function decide(array $labels): array
    {
        $score = $this->maxConfidence($labels);
        $t = $this->thresholds();

        if ($score >= $t['reject']) {
            $decision = 'rejected';
        } elseif ($score >= $t['review']) {
            $decision = 'pending_review';
        } else {
            $decision = 'approved';
        }

        return ['decision' => $decision, 'score' => $score, 'labels' => $labels];
    }

    private function maxConfidence(array $labels): float
    {
        $max = 0.0;
        foreach ($labels as $label) {
            $max = max($max, (float) ($label['confidence'] ?? 0));
        }
        return $max;
    }

    /**
     * Resolve thresholds: admin panel (AISettings) wins, config is fallback.
     */
    public function thresholds(): array
    {
        try {
            $settings = app(AISettings::class);
            return [
                'reject' => (float) ($settings->moderation_reject_threshold ?? config('rekognition.reject_threshold')),
                'review' => (float) ($settings->moderation_review_threshold ?? config('rekognition.review_threshold')),
            ];
        } catch (\Throwable $e) {
            return [
                'reject' => (float) config('rekognition.reject_threshold', 85),
                'review' => (float) config('rekognition.review_threshold', 70),
            ];
        }
    }

    public function isEnabled(): bool
    {
        if (! config('rekognition.enabled', true)) {
            return false;
        }
        try {
            return (bool) app(AISettings::class)->moderation_enabled;
        } catch (\Throwable $e) {
            return true;
        }
    }

    private function client(): RekognitionClient
    {
        if ($this->client === null) {
            $this->client = new RekognitionClient([
                'region'      => config('rekognition.credentials.region'),
                'version'     => config('rekognition.credentials.version', 'latest'),
                'credentials' => [
                    'key'    => config('rekognition.credentials.key'),
                    'secret' => config('rekognition.credentials.secret'),
                ],
            ]);
        }
        return $this->client;
    }

    private function readAttachmentBytes(Attachment $attachment): string
    {
        $disk = $this->diskFor($attachment);
        return $disk->get($attachment->filename);
    }

    private function downloadToTmp(Attachment $attachment): string
    {
        $disk = $this->diskFor($attachment);
        $tmp = tempnam(sys_get_temp_dir(), 'mod_');
        file_put_contents($tmp, $disk->get($attachment->filename));
        return $tmp;
    }

    private function diskFor(Attachment $attachment)
    {
        // Canonical resolution: map the attachment's stored driver id to its
        // filesystem disk name, exactly as removeAttachment()/copyOnDisk() do.
        $diskName = AttachmentServiceProvider::getStorageProviderName($attachment->driver);
        return Storage::disk($diskName);
    }
}
