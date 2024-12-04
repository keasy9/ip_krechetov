<?php

namespace Modules\Filament\Pages\Pages;

use App\Enums\PageEnum;

class Home extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Главная страница';
    protected static ?string $title = 'Главная страница';
    protected static PageEnum $pageCode = PageEnum::home;
    protected static ?int $navigationSort = 2;
}
