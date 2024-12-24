<?php

namespace App\Models;

use App\Enums\MediaCollectionEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, HasAvatar, FilamentUser
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $pic = $this->getFirstMedia(MediaCollectionEnum::userAvatar->value);

        return $pic ? asset($pic->getUrl()) : null;
    }

    public function scopeWithAvatar(Builder $query)
    {
        $query->whereHas('media', fn (Builder $query) => $query->whereCollectionName(MediaCollectionEnum::userAvatar->value));
    }

    public function scopeWithoutAvatar(Builder $query)
    {
        $query->whereDoesntHave('media', fn (Builder $query) => $query->whereCollectionName(MediaCollectionEnum::userAvatar->value));
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // todo отдельное разрешение для этого, или проверять есть ли у юзера разрешения на что-то, что есть в админке
    }
}
