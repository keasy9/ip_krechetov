<?php

namespace Modules\Filament\Pages\Page;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Modules\Content\Enums\GalleryTemplateEnum;
use Modules\Content\Enums\MenuEnum;
use Modules\Content\Enums\PageEnum;
use Modules\Content\Models\Gallery;
use Modules\Main\Enums\WeekdayEnum;

class Home extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Главная страница';
    protected static ?string $title = 'Главная страница';
    protected static PageEnum $pageCode = PageEnum::home;
    protected static ?int $navigationSort = 2;

    public function fields(): array
    {
        return [
            Fieldset::make('Контент')->schema([
                Select::make('promo-slider')
                    ->label('Галерея в промо-слайдере')
                    ->placeholder('Не выводить')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Gallery::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => Gallery::find($value)?->name)
                    ->helperText('Шаблон по умолчанию: ' . GalleryTemplateEnum::slider->label()),

                Select::make('cards')
                    ->label('Галерея в карточках преимуществ')
                    ->placeholder('Не выводить')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Gallery::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => Gallery::find($value)?->name)
                    ->helperText('Шаблон по умолчанию: ' . GalleryTemplateEnum::cards->label()),

                Fieldset::make('Соц. сети')->schema([
                    TextInput::make('socials.title')->label('Заголовок'),
                    Select::make('socials.menu')
                        ->label('Меню')
                        ->placeholder('Не выводить')
                        ->options(MenuEnum::options()),
                    TextInput::make('socials.subtitle')->label('Подзаголовок'),
                ]),

                Select::make('gallery')
                    ->label('Галерея перед блоком контактов')
                    ->placeholder('Не выводить')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Gallery::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => Gallery::find($value)?->name)
                    ->helperText('Шаблон по умолчанию: ' . GalleryTemplateEnum::gallery->label()),

                Fieldset::make('Контакты')->schema([
                    TextInput::make('contacts.2gisId')->label('Id компании в 2gis'),
                    TextInput::make('contacts.title')->label('Заголовок блока'),
                    Textarea::make('contacts.address')->label('Адрес'),

                    Repeater::make('contacts.phone')
                        ->simple(
                            TextInput::make('phone')->label('Телефоны')->mask('+7 (999) 999-99-99')->stripCharacters([' ', '(', ')', '-'])->tel(),
                        )->label('Телефоны'),

                    KeyValue::make('contacts.schedule')
                        ->label('Режим работы')
                        ->addable(false)
                        ->deletable(false)
                        ->editableKeys(false)
                        ->keyLabel('Период')
                        ->valueLabel('Время'),

                ])->columns(1),
            ])->columns(1),
        ];
    }
}
