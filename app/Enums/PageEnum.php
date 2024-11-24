<?php
namespace App\Enums;

enum PageEnum
{
    case home;

    public function title()
    {
        return match ($this) {
            static::home => 'Главная',
        };
    }

    public function routeName()
    {
        return match ($this) {
            static::home => 'index',
        };
    }

    public static function options(): array
    {
        foreach (static::cases() as $case) {
            $options[$case->routeName()] = $case->title();
        }

        return $options;
    }
}
