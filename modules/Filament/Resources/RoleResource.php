<?php

namespace Modules\Filament\Resources;

use App\Enums\PermissionEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Filament\Pages\Role\CreatePage;
use Modules\Filament\Pages\Role\EditPage;
use Modules\Filament\Pages\Role\ListPage;
use Spatie\Permission\Models\Role;

class RoleResource extends BaseResource
{
    public static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'роль';
    protected static ?string $pluralModelLabel = 'роли';
    protected static ?string $navigationGroup = 'Доступ';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('permissions.name')
                    ->label('Права')
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList(),

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
            ])
            ->actions([
                EditAction::make()->label('')->tooltip('Редактировать'),
                DeleteAction::make()->label('')->tooltip('Удалить'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function fields(): array
    {
        return [
            TextInput::make('name')
                ->label('Название')
                ->required(),

            CheckboxList::make('permissions')
                ->label('Разрешения')
                ->relationship('permissions', 'name')
                ->searchable()
                ->columns()
                ->bulkToggleable(),
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

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::roles->value);
    }
}
