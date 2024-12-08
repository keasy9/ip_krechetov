<?php

namespace Modules\Content\Enums;

use Illuminate\Support\Str;

enum GalleryItemTypeEnum: string
{
    case image = 'image';
    case video = 'video';
    case iframe = 'iframe';

    public static function values(): array
    {
        $values = [];
        foreach (static::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }

    public static function fromMime(string $mime): static
    {
        if (Str::startsWith($mime, 'image/')) {
            return static::image;
        } elseif (Str::startsWith($mime, 'video/')) {
            return static::video;
        }

        return static::iframe;
    }

    public function label(): string
    {
        return match ($this) {
            static::image  => 'Картинка',
            static::video  => 'Видео',
            static::iframe => 'Встроенный контент с другого сайта (iframe)',
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
}
