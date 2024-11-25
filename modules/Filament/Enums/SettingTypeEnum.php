<?php
namespace Modules\Filament\Enums;

enum SettingTypeEnum: string
{
    case number = 'number';
    case text = 'text';
    case file = 'file';
    case files = 'files';
}
