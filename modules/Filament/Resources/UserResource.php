<?php

namespace Modules\Filament\Resources;

use App\Enums\MediaCollectionEnum;
use App\Enums\PermissionEnum;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Modules\Filament\Pages\User\CreatePage;
use Modules\Filament\Pages\User\EditPage;
use Modules\Filament\Pages\User\ListPage;

class UserResource extends BaseResource
{
    public static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'пользователь';
    protected static ?string $pluralModelLabel = 'пользователи';
    protected static ?string $navigationGroup = 'Доступ';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email скопирован'),

                TextColumn::make('name')
                    ->label('Имя')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('email_verified_at')
                    ->getStateUsing(fn (User $user) => $user->hasVerifiedEmail())
                    ->boolean()
                    ->label('Подтверждён')
                    ->tooltip(fn (User $user) => $user->email_verified_at)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('roles.name')
                    ->label('Роли')
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList(),

                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('Аватар')
                    ->collection(MediaCollectionEnum::userAvatar->value)
                    ->circular(),

                TextColumn::make('created_at')
                    ->label('Зарегистрирован')
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
                TernaryFilter::make('verified')
                    ->label('Статус')
                    ->nullable()
                    ->placeholder('Все')
                    ->trueLabel('Подтверждённые')
                    ->falseLabel('Неподтверждённые')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
                    ),
                TernaryFilter::make('avatar')
                    ->label('Аватар')
                    ->nullable()
                    ->placeholder('Все')
                    ->trueLabel('Есть')
                    ->falseLabel('Нет')
                    ->queries(
                        true: fn (Builder $query) => $query->withAvatar(),
                        false: fn (Builder $query) => $query->withoutAvatar(),
                    ),
                DateRangeFilter::make('created_at')
                    ->label('Зарегистрирован')
                    ->withIndicator(),
            ])
            ->deferFilters()
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

    public static function getPages(): array
    {
        return [
            'index' => ListPage::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function fields(): array
    {
        return [
            TextInput::make('name')
                ->label('Имя')
                ->required(),

            SpatieMediaLibraryFileUpload::make('avatar')
                ->label('Аватар')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios([null, '1:1'])
                ->circleCropper()
                ->avatar()
                ->collection(MediaCollectionEnum::userAvatar->value),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Select::make('roles')
                ->label('Роли')
                ->relationship('roles', 'name')
                ->multiple()
                ->searchable()
                ->preload()
                ->createOptionForm(RoleResource::fields())
                ->disabled(fn() => auth()->user()->cant(PermissionEnum::roles)), //todo валидация на бэке при сохранении
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo(PermissionEnum::users->value);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNot('id', auth()->id());
    }
}
