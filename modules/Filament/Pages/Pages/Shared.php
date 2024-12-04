<?php

namespace Modules\Filament\Pages\Pages;

use App\Enums\PageEnum;

class Shared extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-m-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Общие';
    protected static ?string $title = 'Общие настройки для всех страниц';
    protected static PageEnum $pageCode = PageEnum::shared;

    public static function defaultFields(bool $titleIsRequired = false): array
    {
        return [];
    }

    public static function fields(): array
    {
        return [
            // todo
        ];
    }

    public static function rules(): array
    {
        return [
            // todo
        ];
    }
}
