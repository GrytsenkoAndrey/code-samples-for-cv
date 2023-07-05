<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => ucfirst($title = $this->faker->words(random_int(1, 3), true)),
            'slug' => SlugService::createSlug(Post::class, 'slug', $title, [
                'unique' => true,
                'separator' => '-'
            ]),
            'description' => $this->faker->sentences(7, true),
            'user_id' => User::get('id')->first(),
            'published_at' => $this->faker->dateTimeBetween('-3 month')
        ];
    }
}
