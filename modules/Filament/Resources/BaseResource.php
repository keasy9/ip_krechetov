<?php

namespace Modules\Filament\Resources;

use Filament\Forms\Components\Field;
use Filament\Forms\Form;
use Filament\Resources\Resource;

abstract class BaseResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form->schema(static::fields());
    }

    /**
     * @return Field[]
     */
    abstract public static function fields(): array;
}
