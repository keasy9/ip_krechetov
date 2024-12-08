<?php

namespace Modules\Filament\Resources;

use App\Enums\MediaCollectionEnum;
use App\Enums\PermissionEnum;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Modules\Content\Enums\GalleryItemTypeEnum;
use Modules\Content\Models\Gallery;
use Modules\Content\Models\GalleryItem;
use Modules\Filament\Pages\Gallery\CreatePage;
use Modules\Filament\Pages\Gallery\EditPage;
use Modules\Filament\Pages\Gallery\ListPage;
use Modules\Filament\Resources\GalleryResource\RelationManagers\ItemsRelationManager;

class GalleryResource extends BaseResource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $modelLabel = 'медиагелерея';
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

                TextColumn::make('items_count')->label('Элементов')
            ])
            ->filters([
                TrashedFilter::make()
                    ->placeholder('Активные')
                    ->trueLabel('Все')
                    ->falseLabel('Архив')
                    ->label('Архив'),
                // todo попраавить лейбл применённого фильтра

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
                    ->modalHeading('Архивировать медиагалерею'),
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
            ])->modifyQueryUsing(fn (Builder $query) => $query->withCount('items'))
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPage::route('/'),
            'create' => CreatePage::route('/create'),
            'edit'   => EditPage::route('/{record}/edit'),
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

            SpatieMediaLibraryFileUpload::make('items')
                ->label('Элементы')
                ->acceptedFileTypes(['image/*', 'video/*'])
                ->imageEditor()
                ->collection(MediaCollectionEnum::galleryItem->value)
                ->multiple()
                ->maxFiles(10)
                ->helperText('Загрузите до 10 фото и видео общим объёмом до 100 Мб, чтобы добавить их к элементам галереи')
                ->loadStateFromRelationshipsUsing(null)
                ->getUploadedFileUsing(null)
                ->reorderUploadedFilesUsing(null)
                ->saveRelationshipsUsing(fn(SpatieMediaLibraryFileUpload $component) => $component->saveUploadedFiles())
                ->saveUploadedFileUsing(function (SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Gallery $record): ?string {
                    try {
                        if (!$file->exists()) return null;
                    } catch (UnableToCheckFileExistence $exception) {
                        return null;
                    }

                    $item = new GalleryItem([
                        'gallery_id' => $record->getKey(),
                        'type'       => GalleryItemTypeEnum::fromMime($file->getMimeType()),
                    ]);
                    if (!method_exists($item, 'addMediaFromString')) return $file;
                    if (!$item->save()) return null;

                    return $item->addMediaFromString($file->get())
                        ->addCustomHeaders($component->getCustomHeaders())
                        ->usingFileName($component->getUploadedFileNameForStorage($file))
                        ->usingName($component->getMediaName($file) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                        ->storingConversionsOnDisk($component->getConversionsDisk() ?? '')
                        ->withCustomProperties($component->getCustomProperties())
                        ->withManipulations($component->getManipulations())
                        ->withResponsiveImagesIf($component->hasResponsiveImages())
                        ->withProperties($component->getProperties())
                        ->toMediaCollection($component->getCollection() ?? 'default')
                        ->getAttributeValue('uuid');
                }),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::pages->value);
    }
}
