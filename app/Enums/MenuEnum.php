<?php
namespace App\Enums;

enum MenuEnum: string
{
    case header = 'header';
    case footer = 'footer';

    public function title(): string
    {
        return match ($this) {
            static::header => 'Шапка',
            static::footer => 'Подвал',
        };
    }
}
