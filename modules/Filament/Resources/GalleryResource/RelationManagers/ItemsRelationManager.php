<?php

namespace Modules\Filament\Resources\GalleryResource\RelationManagers;

use App\Enums\MediaCollectionEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Content\Enums\GalleryItemTypeEnum;
use Modules\Content\Models\GalleryItem;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Элементы';
    protected static ?string $modelLabel = 'элемент';
    protected static ?string $pluralLabel = 'элементы';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('Тип элемента')
                    ->options(GalleryItemTypeEnum::options())
                    ->reactive()
                    ->default(GalleryItemTypeEnum::image->value)
                    ->required(),
                SpatieMediaLibraryFileUpload::make('media')
                    ->label('Файл')
                    ->acceptedFileTypes(['image/*', 'video/*'])
                    ->imageEditor()
                    ->collection(MediaCollectionEnum::galleryItem->value)
                    ->hidden(fn(callable $get) => $get('type') === GalleryItemTypeEnum::iframe->value),
                SpatieMediaLibraryFileUpload::make('thumb')
                    ->label('Обложка')
                    ->acceptedFileTypes(['image/*'])
                    ->imageEditor()
                    ->collection(MediaCollectionEnum::galleryItemThumb->value)
                    ->hidden(fn(callable $get) => $get('type') === GalleryItemTypeEnum::image->value),
                TextInput::make('short_description')
                    ->label('Заголовок/alt')
                    ->maxLength(255),
                TextArea::make('description')
                    ->label('Описание')
                    ->rows(5),
                TextArea::make('iframe')
                    ->label('Код вставки')
                    ->rows(5)
                    ->hidden(fn(callable $get) => $get('type') !== GalleryItemTypeEnum::iframe->value)
                    ->required(fn(callable $get) => $get('type') === GalleryItemTypeEnum::iframe->value),
                ToggleButtons::make('inline_video')
                    ->hintIcon('heroicon-o-question-mark-circle', 'Встроенное видео запустится сразу, без звука и зацикленно')
                    ->hiddenLabel()
                    ->options(['Проигрыватель', 'Встроенное видео'])
                    ->grouped()
                    ->hidden(fn(callable $get) => $get('type') !== GalleryItemTypeEnum::video->value)
                    ->default(false),
            ])
            ->columns(1);
        // todo обновлять таблицу после сохранения формы
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->label('Тип элемента')
                    ->getStateUsing(fn (GalleryItem $record) => $record->type->label())
                    ->sortable(),

                TextColumn::make('media')
                    ->label('Файл')
                    ->getStateUsing(fn (GalleryItem $record) => $record->media?->first()?->name ?? '')
                    ->url(fn (GalleryItem $record) => $record->media?->first()?->getUrl() ?? '')
                    ->openUrlInNewTab()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('short_description')
                    ->label('Заголовок/alt')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')->options(GalleryItemTypeEnum::options())->label('Тип элемента')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel(),
                Tables\Actions\DeleteAction::make()->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('to_player')
                        ->label('Проигрыватель')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => GalleryItem::whereIn('id', $records->pluck('id'))->update(['inline_video' => false])),
                    Tables\Actions\BulkAction::make('to_inline')
                        ->label('Встроенный')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => GalleryItem::whereIn('id', $records->pluck('id'))->update(['inline_video' => true])),
                ])->label('Переключить тип видео на'),

            ])
            ->modifyQueryUsing(fn (Builder|GalleryItem $query) => $query->with('media'))
            ->defaultSort('order')
            ->reorderable('order');
    }

}
