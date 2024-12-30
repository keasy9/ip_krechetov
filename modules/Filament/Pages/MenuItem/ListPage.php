<?php

namespace Modules\Filament\Pages\MenuItem;

use App\Models\MenuItem;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Content\Enums\MenuEnum;
use Modules\Filament\Resources\MenuItemResource;

class ListPage extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    public function getTabs(): array
    {
        $tabs = [];

        foreach (MenuEnum::cases() as $case) {
            $tabs[$case->value] = Tab::make($case->title())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('menu_type', $case->value))
                ->badge(MenuItem::query()->where('menu_type', $case->value)->count());
        }

        return $tabs;
    }

    public function getTableRecords(): Collection|Paginator|CursorPaginator
    {
        return new Collection(MenuItem::treeList($this->activeTab));
    }
}
