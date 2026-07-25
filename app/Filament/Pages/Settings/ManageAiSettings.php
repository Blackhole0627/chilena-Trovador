<?php

namespace App\Filament\Pages\Settings;

use App\Services\Ai\AiAdminOptions;
use App\Settings\AISettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ManageAiSettings extends SettingsPage
{
    use HasPageShield;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $slug = 'settings/ai';

    protected static string $settings = AISettings::class;

    protected static ?string $title = 'AI Settings';

    public function form(Schema $schema): Schema
    {
        $options = app(AiAdminOptions::class);

        return $schema->components([
            Tabs::make('AI Settings')
                ->columnSpanFull()
                ->persistTabInQueryString('tab')
                ->tabs([
                    Tabs\Tab::make('General')
                        ->columns(2)
                        ->schema([
                            Toggle::make('text_enabled')
                                ->label('Enable AI text generation')
                                ->helperText('Bio, post, stream or story suggestions.')
                                ->columnSpanFull(),

                            Toggle::make('images_enabled')
                                ->label('Enable AI image generation')
                                ->helperText('Avatar and cover generation.')
                                ->columnSpanFull(),
                        ]),

                    Tabs\Tab::make('Text')
                        ->columns(2)
                        ->schema([
                            Select::make('text_provider')
                                ->label('Text provider')
                                ->options($options->getTextProviders())
                                ->default('openai')
                                ->live()
                                ->required()
                                ->afterStateUpdated(fn ($state, callable $set) => $set(
                                    'text_model',
                                    $options->getDefaultTextModelForProvider((string) $state)
                                )),

                            Select::make('text_model')
                                ->label('Text model')
                                ->options(fn ($get): array => $options->getTextModelsForProvider((string) $get('text_provider')))
                                ->searchable()
                                ->required()
                                ->helperText('Used for text suggestions.'),

                            TextInput::make('text_max_tokens')
                                ->label('Default output budget')
                                ->numeric()
                                ->required()
                                ->suffix('tokens')
                                ->helperText('Some providers may interpret this value differently.'),

                            TextInput::make('text_temperature')
                                ->label('Default temperature')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(2)
                                ->step(0.1)
                                ->required()
                                ->helperText('Higher = more random, lower = more deterministic.'),
                        ]),

                    Tabs\Tab::make('Images')
                        ->columns(2)
                        ->schema([
                            Select::make('image_provider')
                                ->label('Image provider')
                                ->options($options->getImageProviders())
                                ->default('openai')
                                ->live()
                                ->required()
                                ->afterStateUpdated(fn ($state, callable $set) => $set(
                                    'image_model',
                                    $options->getDefaultImageModelForProvider((string) $state)
                                )),

                            Select::make('image_model')
                                ->label('Image model')
                                ->options(fn ($get): array => $options->getImageModelsForProvider((string) $get('image_provider')))
                                ->searchable()
                                ->required()
                                ->helperText('Used for avatars and covers.'),
                        ]),

                    Tabs\Tab::make('Providers')
                        ->columns(2)
                        ->schema([
                            TextInput::make('openai_api_key')
                                ->label('OpenAI API key')
                                ->password()
                                ->revealable()
                                ->autocomplete('new-password'),

                            TextInput::make('openai_base_url')
                                ->label('OpenAI base URL')
                                ->placeholder('https://api.openai.com/v1'),

                            TextInput::make('anthropic_api_key')
                                ->label('Anthropic API key')
                                ->password()
                                ->revealable()
                                ->autocomplete('new-password'),

                            TextInput::make('anthropic_base_url')
                                ->label('Anthropic base URL')
                                ->placeholder('https://api.anthropic.com'),

                            TextInput::make('deepseek_api_key')
                                ->label('DeepSeek API key')
                                ->password()
                                ->revealable()
                                ->autocomplete('new-password'),

                            TextInput::make('deepseek_base_url')
                                ->label('DeepSeek base URL')
                                ->placeholder('https://api.deepseek.com'),

                            TextInput::make('google_api_key')
                                ->label('Google API key')
                                ->password()
                                ->revealable()
                                ->autocomplete('new-password'),

                            TextInput::make('google_base_url')
                                ->label('Google base URL')
                                ->placeholder('https://generativelanguage.googleapis.com'),

                            TextInput::make('xai_api_key')
                                ->label('xAI API key')
                                ->password()
                                ->revealable()
                                ->autocomplete('new-password'),

                            TextInput::make('xai_base_url')
                                ->label('xAI base URL')
                                ->placeholder('https://api.x.ai/v1'),

                            TextInput::make('ollama_base_url')
                                ->label('Ollama base URL')
                                ->placeholder('http://127.0.0.1:11434')
                                ->helperText('For self-hosted Ollama servers. No API key is normally required.')
                                ->columnSpanFull(),

                        ]),

                    // Trovador T8 — AWS Rekognition visual moderation
                    Tabs\Tab::make('Content Moderation')
                        ->columns(2)
                        ->schema([
                            Toggle::make('moderation_enabled')
                                ->label('Enable AWS Rekognition moderation')
                                ->helperText('Scan uploaded images and videos before publishing.')
                                ->columnSpanFull(),

                            Toggle::make('moderation_notify_user')
                                ->label('Notify the user of the result')
                                ->helperText('Send a real-time notification on approve / review / reject.')
                                ->columnSpanFull(),

                            TextInput::make('moderation_reject_threshold')
                                ->label('Auto-reject threshold (%)')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(1)
                                ->required()
                                ->helperText('At or above this confidence the upload is rejected and hidden.'),

                            TextInput::make('moderation_review_threshold')
                                ->label('Manual-review threshold (%)')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(1)
                                ->required()
                                ->helperText('Between this and the reject threshold, content is flagged for human review. Below it, auto-approved.'),

                            Textarea::make('moderation_msg_approved')
                                ->label('Mensaje: aprobado')
                                ->rows(2)
                                ->columnSpanFull(),

                            Textarea::make('moderation_msg_pending')
                                ->label('Mensaje: en revisión')
                                ->rows(2)
                                ->columnSpanFull(),

                            Textarea::make('moderation_msg_rejected')
                                ->label('Mensaje: rechazado')
                                ->rows(3)
                                ->columnSpanFull(),

                            Textarea::make('moderation_msg_failed')
                                ->label('Mensaje: error de procesamiento')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
