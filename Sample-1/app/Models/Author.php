<?php

namespace App\Models;

use App\Interfaces\HasImgInterface;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Author extends Model implements HasImgInterface
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'job',
        'first_name',
        'last_name',
        'slug',
        'birthday',
        'gender',
        'image',
        'profile_url',
        'linkedin_url',
        'twitter_url',
        'works_for'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['first_name', 'last_name'],
                'unique' => true
            ]
        ];
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imgable');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeAuthorDashIndex(Builder $query): void
    {
        $query->select(['id', 'first_name', 'last_name', 'job'])
            ->withCount(['pages'])
            ->orderBy('last_name')
            ->orderBy('id');
    }
}
