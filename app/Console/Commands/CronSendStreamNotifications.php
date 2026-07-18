<?php

namespace App\Console\Commands;

use App\Model\Stream;
use App\Providers\ListsHelperServiceProvider;
use App\Services\WebPushService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Trovador — F2. Push reminders for scheduled lives:
 *   - 24 hours before
 *   - 15 minutes before
 * Each reminder fires once (guarded by the scheduled_notified_* flags).
 * Cloned in spirit from CronSendDuePostNotifications.
 */
class CronSendStreamNotifications extends Command
{
    protected $signature = 'cron:send_stream_notifications';

    protected $description = 'Send push reminders for scheduled live streams';

    public function handle(WebPushService $webPush): int
    {
        Log::channel('cronjobs')->info('[*]['.date('H:i:s')."] Start scheduled stream reminders.\r\n");

        $now = Carbon::now();

        $streams = Stream::query()
            ->with('user')
            ->whereNotNull('scheduled_at')
            ->where('status', Stream::SCHEDULED_STATUS)
            ->where('scheduled_at', '>', $now)
            ->where(function ($q) {
                $q->where('scheduled_notified_24h', false)
                  ->orWhere('scheduled_notified_15m', false);
            })
            ->get();

        foreach ($streams as $stream) {
            $scheduledAt = Carbon::parse($stream->scheduled_at);
            $minutesUntil = $now->diffInMinutes($scheduledAt, false);

            // 24h reminder window (send once we're within 24h).
            if (!$stream->scheduled_notified_24h && $minutesUntil <= 1440) {
                $this->notifyFollowers($webPush, $stream, __('en 24 horas'));
                $stream->scheduled_notified_24h = true;
                // If we're already inside 15 min, mark that too to avoid a double ping.
                $stream->save();
            }

            // 15 min reminder window.
            if (!$stream->scheduled_notified_15m && $minutesUntil <= 15) {
                $this->notifyFollowers($webPush, $stream, __('en 15 minutos'));
                $stream->scheduled_notified_15m = true;
                $stream->save();
            }
        }

        $this->info('Scheduled stream reminders done.');
        return 0;
    }

    private function notifyFollowers(WebPushService $webPush, Stream $stream, string $whenText): void
    {
        $creator = $stream->user;
        if (!$creator) {
            return;
        }

        // getUserFollowers() returns an array of rows with a 'user_id' key.
        $followers = ListsHelperServiceProvider::getUserFollowers($creator->id);
        $followerIds = array_values(array_unique(array_filter(array_column($followers, 'user_id'))));

        if (empty($followerIds)) {
            return;
        }

        $webPush->sendToUsers($followerIds, [
            'title' => $creator->name.' '.__('transmitirá en vivo').' '.$whenText,
            'body'  => $stream->name ?: __('¡No te lo pierdas en Trovador!'),
            'url'   => url('/live/'.$creator->username),
        ]);
    }
}
