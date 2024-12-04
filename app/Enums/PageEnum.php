<?php
namespace App\Enums;

use Illuminate\Support\Facades\Route;

enum PageEnum
{
    case home;
    case shared;

    public function title()
    {
        return match ($this) {
            static::home   => 'Главная',
            static::shared => 'Общие',
        };
    }

    public function routeName()
    {
        return match ($this) {
            static::home   => 'index',
            static::shared => null,
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (static::cases() as $case) {
            $route = $case->routeName();

            if (Route::has($route)) {
                $options[$route] = $case->title();
            }
        }

        return $options;
    }
}
