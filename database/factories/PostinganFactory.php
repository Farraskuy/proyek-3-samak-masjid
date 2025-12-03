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

        // 1. Pilih status dari daftar baru
        $status = $this->faker->randomElement(['published', 'arsip', 'pending', 'revisi', 'draft']);

        // 2. Siapkan variabel default (kosong)
        $approvedBy = null;
        $approvedAt = null;
        $publishedAt = null;
        $approvalNote = null;

        // 3. Logika pengisian data pendukung berdasarkan status
        if ($status === 'published') {
            // Jika published: Harus ada admin yang approve & tanggal publish
            $approvedBy = User::inRandomOrder()->value('id') ?? User::factory()->create()->id;
            $approvedAt = now()->subDays(rand(0, 30));
            $publishedAt = $approvedAt; // Tanggal tayang = tanggal disetujui
        } elseif ($status === 'revisi') {
            // Jika revisi: Harus ada catatan kenapa direvisi
            $approvalNote = 'Mohon perbaiki: ' . $this->faker->sentence();
            // Opsional: approved_by bisa diisi admin yang minta revisi, atau null
        } elseif ($status === 'arsip') {
            // Jika arsip: Anggap dulu pernah dipublish (ada approver), tapi sekarang ditarik
            $approvedBy = User::inRandomOrder()->value('id') ?? User::factory()->create()->id;
            $approvedAt = now()->subMonths(rand(1, 6)); 
            $publishedAt = null; // Tidak tayang
        }
        // Status 'pending' dan 'draft' biarkan null semua (approved_by, note, published_at)

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => $slug,
            'keterangan' => $this->faker->paragraph(2),
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(rand(3, 7))) . '</p>',
            'featured_image_url' => null, // Atau $this->faker->imageUrl() jika mau gambar dummy
            
            // Kolom Status Baru
            'status' => $status,
            'kategori' => $this->faker->randomElement(['Berita', 'Artikel', 'Tausiyah']),
            
            'published_at' => $publishedAt,
            'created_at' => now()->subDays(rand(60, 90)),
            'updated_at' => now(),

            // Kolom Approval (Tanpa approval_status)
            'approval_note' => $approvalNote,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedAt,
        ];
    }
}