<?php

namespace App\Models;

use App\Collections\PagePartialCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePartial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static string $collectionClass = PagePartialCollection::class;

    public static function booted(): void
    {
        parent::booted();
        static::bootCacheTrait();
    }

    public function getValue(string $defaultValue = ''): string
    {
        return $this->value ?: $defaultValue;
    }

    public function getArray(array $defaultValue = []): array
    {
        return json_decode($this->value, true) ?: $defaultValue ?: [];
    }

    public function getBool(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
    }

    public function getAttachment(): Collection
    {
        return $this->attachments;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
