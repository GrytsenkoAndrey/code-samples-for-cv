<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'is_default',
        'permissions'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title'],
                'unique' => true
            ]
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeCreateUserList(Builder $query): void
    {
        $query->select(['id', 'title'])->orderBy('id');
    }
}
