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

        $status = $this->faker->randomElement(['published', 'not published', 'pending', 'revisi']);

        $approvalStatus = 'pending';
        $approvedBy = null;
        $approvedAt = null;

        if ($status === 'published') {
            $approvalStatus = 'approved';
            $approvedBy = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
            $approvedAt = now()->subDays(rand(0, 30));
        } elseif ($status === 'revisi') {
            $approvalStatus = 'revision';
        } elseif ($status === 'not published') {
            // Could be rejected or just draft (but draft isn't in status enum, so maybe 'not published' acts as draft)
            // Let's say random between pending or rejected if not published
            $approvalStatus = $this->faker->randomElement(['pending', 'rejected']);
        }

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => $slug,
            'keterangan' => $this->faker->paragraph(2),
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(rand(3, 7))) . '</p>',
            'featured_image_url' => null,
            'status' => $status,
            'kategori' => $this->faker->randomElement(['Berita', 'Artikel', 'Tausiyah']),
            'published_at' => $status === 'published' ? $approvedAt : null,
            'created_at' => now()->subDays(rand(0, 30)),
            'updated_at' => now(),
            'approval_status' => $approvalStatus,
            'approval_note' => $approvalStatus === 'revision' || $approvalStatus === 'rejected' ? $this->faker->sentence() : null,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedAt,
        ];
    }
}
