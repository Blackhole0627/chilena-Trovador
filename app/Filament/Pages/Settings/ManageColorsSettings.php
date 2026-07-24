<?php

namespace App\Filament\Pages\Settings;

use App\Settings\ColorsSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use BackedEnum;

class ManageColorsSettings extends SettingsPage
{
    use HasPageShield;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $slug = 'settings/colors';

    protected static string $settings = ColorsSettings::class;

    protected static ?string $title = 'Theme Settings';

    public bool $includeRtlVersion = false;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Customize your theme colors')
                ->description('Customize your branding by adjusting theme colors.')
                ->schema([

                    Placeholder::make('theme_generation')
                        ->label('')
                        ->hiddenLabel()
                        ->columnSpanFull()
                        ->content(new HtmlString(view('filament.partials.colors')->render())),

                    ColorPicker::make('theme_color_code')
                        ->label('Primary color')
                        ->helperText('Used for buttons, accents, and highlights.')
                        ->required(),

                    ColorPicker::make('theme_gradient_from')
                        ->label('Gradient start color')
                        ->helperText('Starting color for gradients and background transitions.')
                        ->required(),

                    ColorPicker::make('theme_gradient_to')
                        ->label('Gradient end color')
                        ->helperText('Ending color for gradients and background transitions.')
                        ->required(),

                    Toggle::make('include_rtl_version')
                        ->label('Include RTL version')
                        ->helperText('Includes a right-to-left CSS file in the generated theme.')
                        ->live()
                        ->afterStateUpdated(fn ($state) => $this->includeRtlVersion = $state)
                        ->dehydrated(false)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    protected function afterSave(): void
    {
        try {
            $state = $this->form->getState();

            // Trovador: theme generation via the external theme service has been
            // removed. The theme is compiled locally from the custom SCSS palette, so
            // this action no longer calls any external server.
            unset($state);

            Notification::make()
                ->title('Tema gestionado por el desarrollador')
                ->body('Los colores de Trovador se compilan de forma personalizada. Para cambios de tema, contacta al desarrollador.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Theme generation failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
