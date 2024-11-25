<?php

namespace Modules\Filament\Pages;

use App\Enums\PermissionEnum;
use App\Models\Setting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasSubNavigation;
use Filament\Pages\Page;
use Modules\Filament\Enums\SettingTypeEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    use HasSubNavigation;
    protected static ?string $navigationIcon = 'heroicon-m-adjustments-horizontal';

    protected static string $view = 'modules.filament.pages.settings-page';
    protected static ?string $navigationLabel = 'Настройки сайта';
    protected static ?string $title = 'Настройки сайта';
    protected ?string $sectionCode = null;

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::settings->value);
    }

    public function mount(): void
    {
        $this->sectionCode = request()->get('code', null);
    }

    public function form(Form $form): Form
    {
        $fields = [];

        foreach(Setting::where('code', 'like', "{$this->sectionCode}.%")->get() as $setting) {
            if ($setting->type === SettingTypeEnum::text) {
                $fields[] = Textarea::make($setting->code)
                    ->label(__($setting->code));
            }
        }

        return $form
            ->schema([
                // todo
            ]);
    }

    public function save($data): void
    {
        //todo

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    public function getSubNavigation(): array
    {
        return Setting::getSections()->map(function (string $code) {
            return NavigationItem::make($code)
                ->label(__($code))
                ->url(fn (): string => Settings::getUrl(['code' => $code]))
                ->isActiveWhen(fn (): string => $this->sectionCode === $code);
        })->toArray();
    }
}
