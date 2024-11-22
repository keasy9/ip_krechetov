<?php

namespace Modules\Filament\Pages\Pages;

use App\Enums\PageEnum;

class Home extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Страницы';
    protected static ?string $navigationLabel = 'Главная';
    protected static ?string $title = 'Главная страница';
    protected static PageEnum $pageCode = PageEnum::home;
}
