<?php

namespace App\Models;

use App\Interfaces\HasImgInterface;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Page extends Model implements HasImgInterface
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'url',
        'redirect_url',
        'seo_title',
        'seo_description',
        'intro_h1',
        'intro_p',
        'navigation',
        'block1',
        'block2',
        'block3',
        'block4',
        'block5',
        'block6',
        'block7',
        'block8',
        'block9',
        'block10',
        'block11',
        'block12',
        'block13',
        'block14',
        'block15',
        'block16',
        'block17',
        'block18',
        'block19',
        'block20',
        'block21',
        'faq',
        'template',
        'author_id'
    ];

    public function sluggable(): array
    {
        return [];
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imgable');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function getUrl(): string
    {
        return config('app.url') . '/' . $this->slug . '/';
    }
}
