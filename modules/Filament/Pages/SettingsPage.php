<?php

namespace Modules\Filament\Pages;

use App\Enums\PermissionEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-m-adjustments-horizontal';

    protected static string $view = 'modules.filament.pages.settings-page';
    protected static ?string $navigationLabel = 'Настройки сайта';
    protected static ?string $title = 'Настройки сайта';

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::settings->value);
    }

    public function mount(): void
    {
        // todo $this->form->fill();
    }

    protected function getForms(): array
    {
        $forms = [
            'form'
        ];

        /**
         *todo
         * foreach(Setting::where('code', 'like', "{$this->code}.%")->get() as $setting) {
         *     $forms[] = ...
         * }
         */

        return $forms;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // todo
            ])
            ->statePath('data');
    }

    public function save($data): void
    {
        //todo

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
