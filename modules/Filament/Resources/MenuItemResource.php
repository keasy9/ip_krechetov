<?php

namespace Modules\Filament\Resources;

use App\Enums\PageEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Modules\Filament\Pages\MenuItem\ListPage;
use App\Models\MenuItem;
use Filament\Tables\Table;
use Modules\TextContent\Models\Page;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-c-list-bullet';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'Элемент меню';
    protected static ?string $pluralModelLabel = 'Меню сайта';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Создать')
                    ->steps(static::getSteps())
                    ->modalHeading('')
                    ->action(function (array $data, $livewire) {
                        unset($data['type']);
                        $item = new MenuItem($data);
                        $item->menu_type = $livewire->activeTab;

                        if ($item->save()) {
                            Notification::make()->success()->title('Сохранено')->send();

                        } else {
                            Notification::make()->danger()->title('Ошибка')->send();
                        }
                    })
            ]);
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
                    ])
            ]),

            Step::make('Ссылка')
                ->hidden(fn (Get $get) => $get('type') !== 'link')
                ->schema([
                    TextInput::make('url')
                        ->label('Ссылка')
                        ->required()
                        ->prefix(url(config('app.url')) . '/'),
                ]),

            Step::make('Страница')
                ->hidden(fn (Get $get) => $get('type') !== 'page')
                ->schema([
                    Select::make('route')
                        ->label('Страница')
                        ->options(PageEnum::options())
                        ->selectablePlaceholder(false)
                        ->searchable(),
                ]),

            Step::make('Страница')
                ->hidden(fn (Get $get) => $get('type') !== 'tts')
                ->schema([
                    TextInput::make('route')
                        ->hidden()
                        ->default('content.tts'),

                    Select::make('route_params.page')
                        ->label('Страница')
                        ->model(Page::class)
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => Page::where('title', 'like', "%{$search}%")->limit(50)->pluck('title', 'id')->toArray())
                        ->getOptionLabelsUsing(fn (array $values) => Page::whereIn('id', $values)->pluck('title', 'id')->toArray()),
                ]),
        ];
    }
}
