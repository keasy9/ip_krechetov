<?php

namespace Modules\Filament\Resources;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Modules\Filament\Pages\Pages\BasePage;
use Modules\Filament\Pages\Pages\Text\CreatePage;
use Modules\Filament\Pages\Pages\Text\EditPage;
use Modules\Filament\Pages\Pages\Text\ListPages;
use Modules\TextContent\Models\Page;

class PageResource extends BaseResource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'страница';
    protected static ?string $pluralModelLabel = 'типовые текстовые';
    protected static ?string $navigationGroup = 'Страницы';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('url')
                    ->label('Адрес')
                    ->sortable()
                    ->searchable()
                    ->url(fn (Page $page) =>$page->getUrl())
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->prefix(url(config('app.url')) . '/'),

                TextColumn::make('title')
                    ->label('Заголовок')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('h1')
                    ->label('Заголовок H1')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('SEO-описание')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('keywords')
                    ->label('Ключевые слова для SEO')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->sortable()
                    ->toggleable()
                    ->since()
                    ->dateTimeTooltip(),

                TextColumn::make('updated_at')
                    ->label('Последнее редактирование')
                    ->sortable()
                    ->toggleable()
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->filters([
                TrashedFilter::make()
                    ->placeholder('Активные')
                    ->trueLabel('Все')
                    ->falseLabel('Архив')
                    ->label('Архив'),

                DateRangeFilter::make('created_at')
                    ->label('Создано')
                    ->withIndicator(),
            ])
            ->actions([
                EditAction::make()
                    ->label('')
                    ->tooltip('Редактировать'),

                DeleteAction::make()
                    ->label('')
                    ->tooltip('Архивировать')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->modalHeading('Архивировать страницу'),
                // TODO: узнать как убрать подтверждение

                ForceDeleteAction::make()
                    ->label('')
                    ->tooltip('Удалить'),

                RestoreAction::make()
                    ->label('')
                    ->tooltip('Восстановить')
                    ->icon('heroicon-o-archive-box-x-mark'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Архивировать выбранное')->icon('heroicon-o-archive-box-arrow-down'),
                ForceDeleteBulkAction::make()->label('Удалить выбранное'),
                RestoreBulkAction::make()->icon('heroicon-o-archive-box-x-mark'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function fields(): array
    {
        return [
            ...BasePage::defaultFields(true),

            TextInput::make('url')
                ->label('Адрес')
                ->columnSpan(2)
                ->required()
                ->helperText('Ссылка, по которой будет доступна эта страница')
                ->prefix(url(config('app.url')) . '/'),
            /**
             * todo tinymce. Чтобы загрузка файлов работала со spatie/laravel-medialibrary возможно и не придётся свой
             *  плагин писать. Попробовать этот: https://filamentphp.com/plugins/amid-tinyeditor
             *  там, кстати, есть вроде бы поддержка сниппетов, но не компонентов
             */

            RichEditor::make('content')
                ->label('Контент')
                ->columnSpan(2)
                ->required(),
        ];
    }
}
