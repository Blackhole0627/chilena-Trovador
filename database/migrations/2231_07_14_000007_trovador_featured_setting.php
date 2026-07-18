<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Trovador — F4: registers the toggle for the "Destacados" feed widget.
 * Add a matching Toggle('featured_highlights_enabled') to ManageFeedSettings
 * and a bool $featured_highlights_enabled to App\Settings\FeedSettings to
 * expose it in the panel.
 */
class TrovadorFeaturedSetting extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('feed.featured_highlights_enabled', false);
    }

    public function down(): void
    {
        $this->migrator->delete('feed.featured_highlights_enabled');
    }
}
