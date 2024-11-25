<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    use HasFactory;
    use CacheTrait;

    protected $guarded = ['id'];

    public function getValue(string $default = ''): string
    {
        return $this->value ?: $default;
    }

    public static function getSections(): Collection
    {
        return static::query()
            ->selectRaw('DISTINCT LEFT(code, LENGTH(code) - LOCATE(\'.\', REVERSE(code))) as code')
            ->orderBy('code')
            ->pluck('code');
    }

    // todo получение файлов
}
