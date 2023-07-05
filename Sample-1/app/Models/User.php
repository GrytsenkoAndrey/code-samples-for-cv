<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Interfaces\HasImgInterface;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasImgInterface
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'slug',
        'role_id',
        'super_admin',
        'is_default',
        'profile_photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name'],
                'unique' => true
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imgable');
    }

    public function scopeIsSuperAdmin(Builder $query): void
    {
        $query->where('super_admin', 1);
    }

    public function scopeIsDefault(Builder $query): void
    {
        $query->where('is_default', 1);
    }

    public function scopeUsersList(Builder $query): void
    {
        $query->orderBy('id')->with('role');
    }
    public function hasAccess(string $access): bool
    {
        if (is_null($this->role)) {
            return false;
        }

        return str_contains($this->role->permissions, $access);
    }
}
