<?php

namespace Modules\Filament\Resources;

use App\Enums\PermissionEnum;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Filament\Pages\Role\CreatePage;
use Modules\Filament\Pages\Role\EditPage;
use Modules\Filament\Pages\Role\ListPage;
use Spatie\Permission\Models\Permission;
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
        /** @var User $user */
        $user = auth()->user();
        $canEditOwnRoles = $user->can(PermissionEnum::ownRole);

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
                EditAction::make()
                    ->label('')
                    ->tooltip('Редактировать')
                    ->disabled(fn (Role $role) => !$canEditOwnRoles && $user->hasRole($role)),

                DeleteAction::make()
                    ->label('')
                    ->tooltip('Удалить')
                    ->disabled(fn (Role $role) => !$canEditOwnRoles && $user->hasRole($role)),
            ])
            ->bulkActions([DeleteBulkAction::make()])
            ->recordUrl(fn (Role $role) => $canEditOwnRoles || !$user->hasRole($role) ? EditPage::getUrl([$role->getKey()]) : null)
            ->checkIfRecordIsSelectableUsing(fn (Role $role) => $canEditOwnRoles || !$user->hasRole($role));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function fields(): array
    {
        /** @var User $user */
        $user = auth()->user();

        return [
            TextInput::make('name')
                ->label('Название')
                ->required(),

            CheckboxList::make('permissions')
                ->label('Разрешения')
                ->relationship('permissions', 'name')
                ->searchable()
                ->columns()
                ->bulkToggleable()
                ->disableOptionWhen(fn ($label): bool => !($user->can(PermissionEnum::fullPermissions) || $user->can($label))),
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
