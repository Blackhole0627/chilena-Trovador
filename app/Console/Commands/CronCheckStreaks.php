<?php

namespace App\Console\Commands;

use App\Model\User;
use App\Providers\NotificationServiceProvider;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Trovador — F3. Daily presence-streak evaluation.
 *
 * A user's streak increments once per calendar day they were active
 * (users.last_active_at). Missing a day resets the streak on their next
 * visit. Reaching a tier assigns a badge and notifies the user.
 */
class CronCheckStreaks extends Command
{
    protected $signature = 'cron:check_streaks';

    protected $description = 'Update presence streaks and award streak badges';

    /** Ordered high -> low so the first match wins. */
    public const TIERS = [
        100 => ['emoji' => '🎖️', 'name' => 'Trovador'],
        60  => ['emoji' => '🦅', 'name' => 'Águila'],
        30  => ['emoji' => '🦉', 'name' => 'Búho'],
        7   => ['emoji' => '🪶', 'name' => 'Pluma'],
    ];

    public function handle(): int
    {
        Log::channel('cronjobs')->info('[*]['.date('H:i:s')."] Start streak check.\r\n");

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Only consider users seen today; nothing to do for absent users.
        User::query()
            ->whereNotNull('last_active_at')
            ->whereDate('last_active_at', $today)
            ->chunkById(500, function ($users) use ($today, $yesterday) {
                foreach ($users as $user) {
                    $lastDay = $user->streak_last_day ? Carbon::parse($user->streak_last_day) : null;

                    if ($lastDay && $lastDay->isSameDay($today)) {
                        continue; // already counted today
                    }

                    if ($lastDay && $lastDay->isSameDay($yesterday)) {
                        $user->streak_days = (int) $user->streak_days + 1; // continued
                    } else {
                        $user->streak_days = 1; // first day or broken streak
                    }

                    $user->streak_last_day = $today->toDateString();

                    $newBadge = $this->badgeForDays($user->streak_days);
                    $badgeChanged = $newBadge && $newBadge['emoji'] !== $user->streak_badge;
                    if ($newBadge) {
                        $user->streak_badge = $newBadge['emoji'];
                    }

                    $user->saveQuietly();

                    if ($badgeChanged) {
                        $this->notifyBadge($user, $newBadge);
                    }
                }
            });

        $this->info('Streak check complete.');
        return 0;
    }

    private function badgeForDays(int $days): ?array
    {
        foreach (self::TIERS as $threshold => $badge) {
            if ($days >= $threshold) {
                return $badge;
            }
        }
        return null;
    }

    private function notifyBadge(User $user, array $badge): void
    {
        try {
            NotificationServiceProvider::publishRawEvent($user->username, 'new-notification', [
                'message' => $badge['emoji'].' '.__('¡Felicidades! Alcanzaste la insignia :name por :days días consecutivos en Trovador.', [
                    'name' => $badge['name'],
                    'days' => $user->streak_days,
                ]),
                'type' => 'streak-badge',
            ]);
        } catch (\Throwable $e) {
            Log::channel('cronjobs')->warning('Streak badge notify failed: '.$e->getMessage());
        }
    }
}
