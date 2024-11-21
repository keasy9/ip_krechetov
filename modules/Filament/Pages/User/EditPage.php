<?php

namespace Modules\Filament\Pages\User;

use App\Enums\MediaCollectionEnum;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Filament\Resources\UserResource;

class EditPage extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
            ...$data,
            'avatar' => [static::getRecord()?->getFirstMedia(MediaCollectionEnum::userAvatar->value)?->getPath()],
        ];
    }
}
