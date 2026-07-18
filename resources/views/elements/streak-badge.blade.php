{{-- F3 — presence streak badge next to a username. Expects $user. --}}
@if(!empty($user->streak_badge))
    @php
        $tvStreakName = null;
        foreach (\App\Console\Commands\CronCheckStreaks::TIERS as $tvT => $tvB) {
            if ((int) ($user->streak_days ?? 0) >= $tvT) { $tvStreakName = $tvB['name']; break; }
        }
    @endphp
    <span class="trovador-streak-badge ml-1"
          data-toggle="tooltip" data-placement="top"
          title="{{ $user->streak_badge }} {{ $tvStreakName }} · {{ (int) $user->streak_days }} {{ __('días consecutivos en Trovador') }}">
        {{ $user->streak_badge }}
    </span>
@endif
