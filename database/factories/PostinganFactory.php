<?php

namespace Database\Factories;

use App\Models\Postingan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostinganFactory extends Factory
{
    protected $model = Postingan::class;

    public function definition()
    {
        $title = $this->faker->sentence(rand(4, 8));
        $slug = Str::slug($title) . '-' . strtolower(Str::random(6));

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => $slug,
            'keterangan' => $this->faker->paragraph(2),
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(rand(3, 7))) . '</p>',
            'featured_image_url' => null,
            'status' => $this->faker->randomElement(['draft', 'not published', 'pending']),
            'kategori' => $this->faker->randomElement(['Berita', 'Artikel', 'Tausiyah']),
            'published_at' => now()->subDays(rand(0, 30)),
            'created_at' => now()->subDays(rand(0, 30)),
            'updated_at' => now(),
        ];
    }
}
