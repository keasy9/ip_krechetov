<?php

namespace Modules\Content\Enums;

enum GalleryTemplateEnum: string
{
    case slider = 'slider';
    case gallery = 'gallery';
    case cards = 'cards';

    public static function values(): array
    {
        $values = [];
        foreach (static::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }

    public function label(): string
    {
        return match ($this) {
            static::slider  => 'Полноэкранный слайдер',
            static::gallery => 'Галерея',
            static::cards   => 'Карточки',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (static::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }

    public function helperText(): string
    {
        return match ($this) {
            static::cards   => 'Поддерживает только изображения, встроенное видео или можно ничего не задавать',
            default         => 'Поддерживает любой тип контента',
        };
    }
}
