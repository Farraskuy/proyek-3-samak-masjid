<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default "Tentang Kami" page if it doesn't exist
        \App\Models\WebsiteInformation::firstOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Kami',
                'description' => 'Pelajari lebih lanjut tentang SAMAK Masjid dan misi kami',
                'content' => '<h2>Selamat Datang di SAMAK Masjid</h2>
<p>Kami adalah komunitas yang berdedikasi untuk melayani kebutuhan spiritual dan sosial masyarakat. Melalui berbagai program dan kegiatan, kami berusaha membangun masyarakat yang lebih baik dan lebih beriman.</p>
<h3>Visi Kami</h3>
<p>Membangun komunitas muslim yang kuat, bersatu, dan berdampak positif bagi lingkungan sekitar.</p>
<h3>Misi Kami</h3>
<p>
<ul>
<li>Menyelenggarakan kegiatan ibadah dan dakwah yang berkualitas</li>
<li>Memberikan layanan pendidikan agama yang komprehensif</li>
<li>Memfasilitasi program-program sosial untuk masyarakat</li>
<li>Membangun solidaritas dan persatuan dalam komunitas</li>
</ul>
</p>
<p>Bergabunglah dengan kami dalam perjalanan ini untuk menciptakan perubahan positif.</p>',
                'featured_image_url' => "website-information/images/default_masjid.jpg",
                'footer_address' => 'Jl. Masjid No. 1, Kota Samak',
                'footer_phone' => '08123456789',
                'footer_email' => 'info@samakmasjid.com',
                'footer_social_links' => json_encode(['facebook' => 'https://facebook.com', 'instagram' => 'https://instagram.com']),
                'updated_by_admin' => 1
            ]
        );
    }
}
