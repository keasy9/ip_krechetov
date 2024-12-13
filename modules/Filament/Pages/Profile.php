<?php

namespace Modules\Filament\Pages;

use App\Enums\MediaCollectionEnum;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Auth\EditProfile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use function Filament\Support\is_app_url;

/**
 * @property Form $form
 */
class Profile extends EditProfile
{

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    protected static bool $isDiscovered = false;

    protected function getRedirectUrl(): ?string
    {
        return null;
    }

    protected function getPasswordFormComponent(): Component
    {
        // todo подобным образом выносить всегда все поля. Если какие-то поля есть на разных страницах, вынести их в фабрику. То же касается и полей таблиц
        return parent::getPasswordFormComponent()
            ->helperText('Оставьте пустым, чтобы не менять пароль');
    }

    protected function getOldPasswordFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->label('Старый пароль')
            ->name('oldPassword');
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Fieldset::make('Основная информация')->schema([
                            SpatieMediaLibraryFileUpload::make('avatar')
                                ->label('Аватар')
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios([null, '1:1'])
                                ->circleCropper()
                                ->avatar()
                                ->collection(MediaCollectionEnum::userAvatar->value),

                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                        ])->columns(1),

                        Fieldset::make('Сменить пароль')->schema([
                            $this->getOldPasswordFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ])
                        ->columns(1),

                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(!static::isSimple()),
            ),
        ];
    }
}
