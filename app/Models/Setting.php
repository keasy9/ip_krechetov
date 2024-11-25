<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    use CacheTrait;

    protected $guarded = ['id'];

    public function getValue(string $default = ''): string
    {
        return $this->value ?: $default;
    }

    // todo получение файлов
    // todo метод для построения дерева из ключей настроек
}
