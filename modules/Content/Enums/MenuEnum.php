<?php
namespace Modules\Content\Enums;

enum MenuEnum: string
{
    case socials = 'socials';

    public function title(): string
    {
        return match ($this) {
            static::socials => 'социальные сети',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (static::cases() as $case) {
            $options[$case->value] = $case->title();
        }

        return $options;
    }
}
