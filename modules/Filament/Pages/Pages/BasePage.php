<?php

namespace Modules\Filament\Pages\Pages;

use App\Enums\PermissionEnum;
use App\Models\PagePartial;
use App\Services\PagePartialService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page as FilamentPage;

abstract class BasePage extends FilamentPage implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'modules.filament.pages.page';
    public ?array $data = [];

    public static function defaultFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Заголовок окна браузера')
                ->prefix('<title>')
                ->suffix('</title>'),
            TextInput::make('h1')
                ->label('Заголовок страницы')
                ->prefix('<h1>')
                ->suffix('</h1>')
                ->helperText('Заголовок в теле страницы'),
            Textarea::make('description')
                ->label('Описание')
                ->helperText('META-тег DESCRIPTION для поисковых роботов'),
            Textarea::make('keywords')
                ->label('Ключевые слова')
                ->helperText('META-тег KEYWORDS для поисковых роботов'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::pages->value);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить')
                ->action(fn() => $this->save($this->form->getState())),
        ];
    }

    public function mount(): void
    {
        $this->form->fill(
            PagePartialService::get(static::$pageCode)
                ->map(fn ($item) => $item->value)
                ->filter()
                ->map(fn ($item) => json_decode($item, true) ?: $item ?: '')
                ->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...static::defaultFields(),
                ...static::fields(),
            ])
            ->statePath('data');
    }

    public function save($data): void
    {
        foreach ($data as $slug => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
            }

            PagePartial::updateOrCreate([
                'page_code' => static::$pageCode,
                'slug'      => $slug,
            ], ['value' => $value]);
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    public static function fields(): array
    {
        return [];
    }

    public static function rules(): array
    {
        return [];
    }
}