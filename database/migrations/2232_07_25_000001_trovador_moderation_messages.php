<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Trovador — registers admin-editable Rekognition moderation notification
 * messages under the "ai" group. Editable in the panel via ManageAiSettings.
 */
class TrovadorModerationMessages extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('ai.moderation_msg_approved', 'Tu contenido fue aprobado y ya está visible. ¡Gracias por ser parte de Trovador!');
        $this->migrator->add('ai.moderation_msg_pending', 'Tu contenido está siendo revisado por nuestro equipo. Te notificaremos pronto.');
        $this->migrator->add('ai.moderation_msg_rejected', 'Tu contenido no cumple con nuestras políticas. En Trovador celebramos la sensualidad y la creatividad — pero sin contenido explícito. Revisa nuestras normas e inténtalo de nuevo.');
        $this->migrator->add('ai.moderation_msg_failed', 'No pudimos procesar este archivo. Inténtalo de nuevo o escríbenos a contacto@trovadorapp.com');
    }

    public function down(): void
    {
        $this->migrator->delete('ai.moderation_msg_approved');
        $this->migrator->delete('ai.moderation_msg_pending');
        $this->migrator->delete('ai.moderation_msg_rejected');
        $this->migrator->delete('ai.moderation_msg_failed');
    }
}
