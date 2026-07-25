<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AISettings extends Settings
{
    // Generic feature toggles
    public bool $text_enabled = false;

    public bool $images_enabled = false;

    // Generic runtime routing
    public ?string $text_provider = 'openai';

    public ?string $image_provider = 'openai';

    public ?string $text_model = null;

    public ?string $image_model = null;

    public ?int $text_max_tokens = 200;

    public ?float $text_temperature = 1.0;

    // OpenAI
    public ?string $openai_api_key = null;

    public ?string $openai_base_url = 'https://api.openai.com/v1';

    // Ollama
    public ?string $ollama_base_url = 'http://127.0.0.1:11434';

    // Anthropic
    public ?string $anthropic_api_key = null;

    public ?string $anthropic_base_url = 'https://api.anthropic.com';

    // DeepSeek
    public ?string $deepseek_api_key = null;

    public ?string $deepseek_base_url = 'https://api.deepseek.com';

    // Google Gemini
    public ?string $google_api_key = null;

    public ?string $google_base_url = 'https://generativelanguage.googleapis.com';

    // xAI / Grok
    public ?string $xai_api_key = null;

    public ?string $xai_base_url = 'https://api.x.ai/v1';

    // Trovador T8 — AWS Rekognition visual moderation (admin-configurable)
    public bool $moderation_enabled = true;

    public float $moderation_reject_threshold = 85.0;

    public float $moderation_review_threshold = 70.0;

    public bool $moderation_notify_user = true;

    // Trovador — admin-editable moderation notification messages (shown to the uploader).
    public string $moderation_msg_approved = 'Tu contenido fue aprobado y ya está visible. ¡Gracias por ser parte de Trovador!';

    public string $moderation_msg_pending = 'Tu contenido está siendo revisado por nuestro equipo. Te notificaremos pronto.';

    public string $moderation_msg_rejected = 'Tu contenido no cumple con nuestras políticas. En Trovador celebramos la sensualidad y la creatividad — pero sin contenido explícito. Revisa nuestras normas e inténtalo de nuevo.';

    public string $moderation_msg_failed = 'No pudimos procesar este archivo. Inténtalo de nuevo o escríbenos a contacto@trovadorapp.com';

    public static function group(): string
    {
        return 'ai';
    }
}
