<?php

namespace Modules\Filament\Pages\Page;

use App\Enums\PageEnum;
use Filament\Forms\Components\Select;
use Modules\Content\Enums\GalleryTemplateEnum;
use Modules\Content\Models\Gallery;

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

            Select::make('gallery')
                ->label('Галерея в перед блоком контактов')
                ->placeholder('Не выводить')
                ->searchable()
                ->getSearchResultsUsing(fn (string $search): array => Gallery::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                ->getOptionLabelUsing(fn ($value): ?string => Gallery::find($value)?->name)
                ->helperText('Шаблон по умолчанию: ' . GalleryTemplateEnum::gallery->label()),
        ];
    }
}
