<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
        Storage::makeDirectory('posts');
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
            'image' => "posts/" . $this->faker->image("public/storage/posts", 640, 480, null, false)
        ];
    }
}
