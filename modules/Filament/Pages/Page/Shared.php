<?php

namespace Modules\Filament\Pages\Page;

use App\Enums\PageEnum;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class Shared extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-m-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Настройки';
    protected static ?string $title = 'Настройки сайта';
    protected static ?int $navigationSort = 1;
    protected static PageEnum $pageCode = PageEnum::shared;

    public static function defaultFields(bool $titleIsRequired = false): array
    {
        return [];
    }

    public function fields(): array
    {
        return [
            TextInput::make('site-name')
                ->label('Название сайта')
                ->required(),

            Fieldset::make('Шапка сайта')->schema([
                TextInput::make('header.site-name')
                    ->label('Название сайта')
                    ->placeholder($this->data['site-name'] ?? ''),

                Textarea::make('header.address')
                    ->label('Адрес')
                    ->rows(2),

                Repeater::make('header.phone')
                    ->simple(
                        TextInput::make('phone')->label('Телефоны')->mask('+7 (999) 999-99-99')->stripCharacters([' ', '(', ')', '-'])->tel(),
                    )->label('Телефоны'),

            ])->columns(3),

            Fieldset::make('Подвал сайта')->schema([
                TextInput::make('footer.site-name')
                    ->label('Название сайта')
                    ->placeholder($this->data['site-name'] ?? ''),

                TextInput::make('footer.start-year')
                    ->label('Период деятельности')
                    ->suffix(' - ' . now()->format('Y')),
            ])->columns(),
        ];
    }

    public static function rules(): array
    {
        return [
            // todo
        ];
    }
}
