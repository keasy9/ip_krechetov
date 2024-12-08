<?php

namespace Modules\Filament\Resources;

use App\Enums\PermissionEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Modules\Content\Models\Gallery;
use Modules\Filament\Pages\Gallery\CreatePage;
use Modules\Filament\Pages\Gallery\EditPage;
use Modules\Filament\Pages\Gallery\ListPage;

class GalleryResource extends BaseResource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'медиагелерею';
    protected static ?string $pluralModelLabel = 'медиагалереи';
    protected static ?string $navigationGroup = 'Контент';
    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->sortable()
                    ->searchable(),

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
                // TODO: как убрать подтверждение?

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
            'index' => ListPage::route('/'),
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
            TextInput::make('name')
                ->label('Название')
                ->columnSpan(2)
                ->required(),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::pages->value);
    }
}
