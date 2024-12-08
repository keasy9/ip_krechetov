<?php

namespace Modules\Filament\Resources;

use App\Enums\PageEnum;
use App\Enums\PermissionEnum;
use App\Models\MenuItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\Content\Models\Page;
use Modules\Filament\Pages\MenuItem\ListPage;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;
    protected static ?string $navigationIcon = 'heroicon-c-list-bullet';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'Элемент меню';
    protected static ?string $pluralModelLabel = 'Меню сайта';
    protected static ?string $navigationGroup = 'Контент';
    protected static ?int $navigationSort = 0;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->getStateUsing(function (MenuItem $item) {
                        return Str::repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $item->level) . $item->title;
                    })->html(),

                TextColumn::make('url')
                    ->label('Ссылка')
                    ->url(fn(MenuItem $item) => $item->getUrl())
                    ->openUrlInNewTab()
                    ->getStateUsing(fn(MenuItem $item) => $item->getUrl())
                    ->icon('heroicon-o-arrow-top-right-on-square'),

                TextColumn::make('sort')
                    ->label('Сортировка'),
                // todo кнопки-стрелки для сортировки. стандартная сортировка не подходит из-за древовидной структуры

                ToggleColumn::make('deleted_at')
                    ->label('Выводить на сайте')
                    ->updateStateUsing(function (bool $state, MenuItem $item) {
                        if ($state) {
                            $item->restore();
                        } elseif(!$item->trashed()) {
                            $item->delete();
                        }
                        // todo при удалении родителя надо показать детей удалёнными, при восстановлении показать их реальное сосотяние
                    })
                    ->getStateUsing(fn(MenuItem $item) => !$item->deleted_at),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->toggleable()
                    ->since()
                    ->dateTimeTooltip(),

                TextColumn::make('updated_at')
                    ->label('Последнее редактирование')
                    ->toggleable()
                    ->since()
                    ->dateTimeTooltip(),

            ])
            ->actions([
                Action::make('create_child')
                    ->label('')
                    ->tooltip('Добавить подпункт')
                    ->icon('heroicon-c-plus')
                    ->steps(static::getSteps())
                    ->action(function (MenuItem $parent, array $data, $livewire) {
                        if ($data['type'] === 'tts') $data['route'] = 'content.tts';
                        unset($data['type'], $data['parent_id']);
                        if (!$data['sort']) unset($data['sort']);
                        $item = new MenuItem($data);
                        $item->menu_type = $livewire->activeTab;
                        $item->parent_id = $parent->getKey();

                        if ($item->save()) {
                            Notification::make()->success()->title('Сохранено')->send();

                        } else {
                            Notification::make()->danger()->title('Ошибка')->send();
                        }
                    }),

                EditAction::make()
                    ->label('')
                    ->tooltip('Редактировать')
                    ->modalHeading('')
                    ->fillForm(function (MenuItem $item) {
                        $attrs = $item->attributesToArray();
                        if (!$item->route) {
                            $attrs['type'] = 'link';
                        } elseif ($item->route === 'content.tts') {
                            $attrs['type'] = 'tts';
                        } else {
                            $attrs['type'] = 'page';
                        }

                        return $attrs;
                    })
                    ->steps(static::getSteps())
                    ->skippableSteps()
                    ->action(function (MenuItem $item, array $data) {
                        unset($data['type'], $data['menu_type']);

                        if ($item->fill($data)->save()) {
                            Notification::make()->success()->title('Сохранено')->send();

                        } else {
                            Notification::make()->danger()->title('Ошибка')->send();
                        }
                    }),

                ForceDeleteAction::make()->label('')->visible(),
            ])
            ->bulkActions([
                ForceDeleteBulkAction::make()->hidden(false)->label('Удалить выбранное'),
            ])
            ->headerActions([
                Action::make('create') // нет смысла использовать CreateAction, т.к. всё переопределяем вручную
                ->label('Создать')
                    ->steps(static::getSteps())
                    ->modalHeading('')
                    ->action(function (array $data, $livewire) {
                        if ($data['type'] === 'tts') $data['route'] = 'content.tts';
                        unset($data['type'], $data['parent_id']);
                        if (!$data['sort']) unset($data['sort']);
                        $item = new MenuItem($data);
                        $item->menu_type = $livewire->activeTab;

                        if ($item->save()) {
                            Notification::make()->success()->title('Сохранено')->send();

                        } else {
                            Notification::make()->danger()->title('Ошибка')->send();
                        }
                    }),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPage::route('/'),
        ];
    }

    protected static function getSteps(): array
    {
        return [
            Step::make('Основная информация')->schema([
                TextInput::make('title')
                    ->label('Название')
                    ->required(),
                TextInput::make('sort')
                    ->label('Сортировка')
                    ->placeholder('500')
                    ->numeric(),
            ]),

            Step::make('Тип элемента')->live()->schema([
                Select::make('type')
                    ->label('')
                    ->selectablePlaceholder(false)
                    ->default('link')
                    ->required()
                    ->live()
                    ->options([
                        'link' => 'Ссылка',
                        'page' => 'Страница сайта',
                        'tts'  => 'Типовая текстовая страница',
                    ]),
            ]),

            Step::make('Ссылка')
                ->hidden(fn(Get $get) => $get('type') !== 'link')
                ->schema([
                    TextInput::make('url')
                        ->label('Ссылка')
                        ->required()
                        ->prefix(url(config('app.url')) . '/'),
                ]),

            Step::make('Страница')
                ->hidden(fn(Get $get) => $get('type') !== 'page')
                ->schema([
                    Select::make('route')
                        ->label('Страница')
                        ->options(PageEnum::options())
                        ->selectablePlaceholder(false)
                        ->searchable(),
                ]),

            Step::make('Страница')
                ->hidden(fn(Get $get) => $get('type') !== 'tts')
                ->schema([
                    Select::make('route_params.page')
                        ->label('Страница')
                        ->options(Page::limit(50)->pluck('title', 'id')->toArray())
                        ->searchable()
                        ->getSearchResultsUsing(fn(string $search) => Page::where('title', 'like', "%{$search}%")->limit(50)->pluck('title', 'id')->toArray())
                        ->getOptionLabelsUsing(fn(array $values) => Page::whereIn('id', $values)->pluck('title', 'id')->toArray()),
                ]),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::menu->value);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }
}
