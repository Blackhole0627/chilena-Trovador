<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Trovador — T8: registers admin-configurable Rekognition moderation
 * settings under the existing "ai" group. Exposed in the panel via
 * ManageAiSettings (Content Moderation tab). Defaults mirror config/rekognition.php.
 */
class TrovadorModerationSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('ai.moderation_enabled', true);
        $this->migrator->add('ai.moderation_reject_threshold', 85.0);
        $this->migrator->add('ai.moderation_review_threshold', 70.0);
        $this->migrator->add('ai.moderation_notify_user', true);
    }

    public function down(): void
    {
        $this->migrator->delete('ai.moderation_enabled');
        $this->migrator->delete('ai.moderation_reject_threshold');
        $this->migrator->delete('ai.moderation_review_threshold');
        $this->migrator->delete('ai.moderation_notify_user');
    }
}
