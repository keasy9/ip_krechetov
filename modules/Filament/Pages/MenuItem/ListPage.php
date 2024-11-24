<?php

namespace Modules\Filament\Pages\MenuItem;

use App\Enums\MenuEnum;
use App\Models\MenuItem;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Modules\Filament\Resources\MenuItemResource;

class ListPage extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    public function getTabs(): array
    {
        return [
            'header' => Tab::make(MenuEnum::header->title())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('menu_type', MenuEnum::header->value))
                ->badge(MenuItem::query()->where('menu_type', MenuEnum::header->value)->count()),

            'footer' => Tab::make(MenuEnum::footer->title())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('menu_type', MenuEnum::footer->value))
                ->badge(MenuItem::query()->where('menu_type', MenuEnum::footer->value)->count()),
        ];
    }
}
