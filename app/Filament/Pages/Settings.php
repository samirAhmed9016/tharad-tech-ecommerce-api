<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\{FileUpload, Tabs, TextInput, Textarea, Toggle};


class Settings extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.settings';
    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public static function getLabel(): string
    {
        return __('Settings');
    }

    public static function getPluralLabel(): string
    {
        return __('Settings');
    }

    public function getTitle(): string
    {
        return __('Settings');
    }

    public ?array $data = [];

    public function mount()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $formData = [];

        foreach ($settings as $key => $value) {
            $formData[$key] = $this->getSettingValue($key);
        }

        $this->form->fill($formData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make(__('Settings'))
                    ->columnSpanFull()
                    ->tabs([

                        Tabs\Tab::make(__('App Information'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('app_name')
                                    ->label(__('App Name'))
                                    ->required()
                                    ->translatable()
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make(__('Contact Information'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('contact_email')
                                    ->label(__('Contact Email'))
                                    ->email()
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->required(),

                                TextInput::make('contact_number')
                                    ->label(__('Contact Number'))
                                    ->tel()
                                    ->prefixIcon('heroicon-o-phone')
                                    ->required(),

                                Textarea::make('contact_location')
                                    ->label(__('Contact Location'))
                                    ->rows(3)
                                    ->translatable()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Tabs\Tab::make(__('Images'))
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('logo')
                                    ->label(__('Logo'))
                                    ->image()
                                    ->directory('settings')
                                    ->disk('public')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->maxSize(2048),

                                FileUpload::make('favicon')
                                    ->label(__('Favicon'))
                                    ->image()
                                    ->directory('settings')
                                    ->disk('public')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '1:1',
                                    ])
                                    ->maxSize(1024),
                            ])->columns(2),


                        Tabs\Tab::make(__('Social Media'))
                            ->icon('heroicon-o-share')
                            ->schema([
                                TextInput::make('whatsapp_number')
                                    ->label(__('WhatsApp Number'))
                                    ->tel()
                                    ->prefixIcon('heroicon-o-phone'),

                                TextInput::make('facebook_link')
                                    ->label(__('Facebook Link'))
                                    ->url()
                                    ->prefixIcon('heroicon-o-link'),

                                TextInput::make('instagram_link')
                                    ->label(__('Instagram Link'))
                                    ->url()
                                    ->prefixIcon('heroicon-o-link'),
                            ]),


                        Tabs\Tab::make(__('Legal'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Textarea::make('terms_conditions')
                                    ->label(__('Terms & Conditions'))
                                    ->rows(8)
                                    ->translatable()
                                    ->columnSpanFull(),

                                Textarea::make('policy')
                                    ->label(__('Privacy Policy'))
                                    ->rows(8)
                                    ->translatable()
                                    ->columnSpanFull(),
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    private function getSettingValue($key)
    {
        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            // Try to decode JSON
            $decoded = json_decode($setting->value, true);

            // If it's a valid array, return it (for translatable fields)
            if (is_array($decoded)) {
                return $decoded;
            }

            // Otherwise return the raw value
            return $setting->value;
        }

        return null;
    }

    public function save()
    {
        $validatedData = $this->form->getState();

        foreach ($validatedData as $key => $value) {
            // Skip if null
            if (is_null($value)) {
                $value = '';
            }

            // Convert arrays to JSON (for translatable fields)
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Notification::make()
            ->title(__('Settings saved successfully!'))
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Settings'))
                ->submit('save')
                ->color('primary'),
        ];
    }
}
