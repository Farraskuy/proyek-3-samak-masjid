<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consultations = [
            [
                'question_subject' => 'Tata Cara Berbusana Islami',
                'question_text' => 'Assalamu alaikum. Bagaimana tata cara berbusana yang benar menurut agama Islam? Apakah ada perbedaan antara laki-laki dan perempuan?',
                'question_from' => 'Nur Khairani',
                'answer_text' => 'Wa alaikum assalam wa rahmatullahi wa barakatuhu. Dalam Islam, berbusana yang baik disebut dengan "libas al-takwa". Untuk perempuan, disunnahkan untuk menutup seluruh aurat dengan pakaian yang longgar dan tidak tembus pandang. Untuk laki-laki, minimal menutup dari pusar hingga lutut. Yang terpenting adalah niat dan kesadaran.',
                'status' => 'closed',
                'is_anonymous' => false,
                'inputted_by_admin_id' => 1,
                'answered_by_ustadz_id' => 2,
                'created_at' => now()->subDays(10),
                'answered_at' => now()->subDays(9),
                'closed_at' => now()->subDays(8),
                'conclusion' => 'Konsultasi mengenai busana Islami telah terjawab dengan baik dan jelas.',
            ],
            [
                'question_subject' => 'Doa untuk Orang Tua',
                'question_text' => 'Bagaimana cara berdoa yang baik untuk orang tua yang masih hidup dan yang sudah meninggal? Ada doa khusus yang disunnahkan?',
                'question_from' => 'Muhammad Rizki',
                'answer_text' => null,
                'status' => 'pending',
                'is_anonymous' => false,
                'inputted_by_admin_id' => 1,
                'answered_by_ustadz_id' => null,
                'created_at' => now()->subDays(3),
                'answered_at' => null,
                'closed_at' => null,
                'conclusion' => null,
            ],
            [
                'question_subject' => 'Hukum Menonton Film',
                'question_text' => 'Apakah dalam Islam diperbolehkan menonton film? Bagaimana jika film tersebut mengandung adegan yang tidak sesuai dengan nilai-nilai Islam?',
                'question_from' => 'Pengunjung Anonim',
                'answer_text' => 'Menonton film secara umum diperbolehkan selama konten yang ditonton tidak bertentangan dengan nilai-nilai Islam. Sebagai muslim, kita perlu selektif dalam memilih konten yang dikonsumsi karena mata adalah pintu hati.',
                'status' => 'answered',
                'is_anonymous' => true,
                'inputted_by_admin_id' => 1,
                'answered_by_ustadz_id' => 2,
                'created_at' => now()->subDays(7),
                'answered_at' => now()->subDays(6),
                'closed_at' => null,
                'conclusion' => null,
            ],
            [
                'question_subject' => 'Etika dalam Berjualan',
                'question_text' => 'Saya seorang pedagang, bagaimana etika berjualan yang baik menurut ajaran Islam? Apakah boleh menaikkan harga barang saat ada permintaan tinggi?',
                'question_from' => 'Ahmad Fadli',
                'answer_text' => null,
                'status' => 'in_progress',
                'is_anonymous' => false,
                'inputted_by_admin_id' => 1,
                'answered_by_ustadz_id' => 2,
                'created_at' => now()->subDays(2),
                'answered_at' => null,
                'closed_at' => null,
                'conclusion' => null,
            ],
            [
                'question_subject' => 'Zakat Fitrah',
                'question_text' => 'Kapan waktu yang tepat untuk membayar zakat fitrah? Berapa jumlahnya? Siapa saja yang wajib membayar?',
                'question_from' => 'Siti Nurhaliza',
                'answer_text' => null,
                'status' => 'rejected',
                'is_anonymous' => false,
                'inputted_by_admin_id' => 1,
                'answered_by_ustadz_id' => 2,
                'created_at' => now()->subDays(5),
                'answered_at' => null,
                'closed_at' => null,
                'rejection_reason' => 'Pertanyaan ini sudah pernah dijawab sebelumnya. Silahkan lihat di artikel FAQ kami.',
                'conclusion' => null,
            ],
        ];

        foreach ($consultations as $consultation) {
            Consultation::create($consultation);
        }

        // Add sample messages for the first consultation
        $firstConsultation = Consultation::first();
        if ($firstConsultation) {
            ConsultationMessage::create([
                'consultation_id' => $firstConsultation->consultation_id,
                'user_id' => 1,
                'message' => 'Assalamu alaikum, saya ingin menanyakan tentang busana islami',
                'message_type' => 'text',
                'is_read' => true,
                'read_at' => now(),
            ]);

            ConsultationMessage::create([
                'consultation_id' => $firstConsultation->consultation_id,
                'user_id' => 2,
                'message' => 'Wa alaikum assalam. Silahkan jelaskan lebih detail pertanyaan Anda',
                'message_type' => 'text',
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}

