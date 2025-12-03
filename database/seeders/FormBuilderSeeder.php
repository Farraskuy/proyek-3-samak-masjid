<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\FormField;

class FormBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Form Pendaftaran Kajian
        $form = Form::create([
            'title' => 'Formulir Pendaftaran Kajian Rutin',
            'slug' => 'pendaftaran-kajian-rutin',
            'description' => 'Silakan isi formulir ini untuk mendaftar sebagai peserta kajian rutin.',
            'settings' => [],
        ]);

        $fields = [
            [
                'label' => 'Nama Lengkap',
                'name' => 'nama_lengkap',
                'type' => 'text',
                'placeholder' => 'Masukkan nama lengkap Anda',
                'is_required' => true,
                'order' => 0,
            ],
            [
                'label' => 'Nomor WhatsApp',
                'name' => 'no_wa',
                'type' => 'text',
                'placeholder' => 'Contoh: 081234567890',
                'is_required' => true,
                'validation_rules' => ['numeric'],
                'order' => 1,
            ],
            [
                'label' => 'Jenis Kelamin',
                'name' => 'jenis_kelamin',
                'type' => 'radio',
                'options' => ['Laki-laki', 'Perempuan'],
                'is_required' => true,
                'order' => 2,
            ],
            [
                'label' => 'Domisili',
                'name' => 'domisili',
                'type' => 'select',
                'options' => ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 'Lainnya'],
                'is_required' => true,
                'order' => 3,
            ],
            [
                'label' => 'Alasan Mengikuti Kajian',
                'name' => 'alasan',
                'type' => 'textarea',
                'placeholder' => 'Ceritakan motivasi Anda...',
                'is_required' => false,
                'order' => 4,
            ],
        ];

        foreach ($fields as $field) {
            FormField::create(array_merge(['form_id' => $form->id], $field));
        }

        // 2. Form Kuisioner Kepuasan
        $form2 = Form::create([
            'title' => 'Kuisioner Kepuasan Jamaah',
            'slug' => 'kuisioner-kepuasan',
            'description' => 'Bantu kami meningkatkan layanan masjid dengan mengisi kuisioner ini.',
            'settings' => [],
        ]);

        $fields2 = [
            [
                'label' => 'Seberapa sering Anda mengunjungi masjid?',
                'name' => 'frekuensi_kunjungan',
                'type' => 'radio',
                'options' => ['Setiap hari', 'Beberapa kali seminggu', 'Seminggu sekali', 'Jarang'],
                'is_required' => true,
                'order' => 0,
            ],
            [
                'label' => 'Aspek apa yang perlu ditingkatkan?',
                'name' => 'aspek_perbaikan',
                'type' => 'checkbox',
                'options' => ['Kebersihan', 'Kenyamanan', 'Fasilitas Wudhu', 'Sound System', 'Kajian'],
                'is_required' => false,
                'order' => 1,
            ],
            [
                'label' => 'Saran dan Masukan',
                'name' => 'saran',
                'type' => 'textarea',
                'placeholder' => 'Tuliskan saran Anda di sini...',
                'is_required' => true,
                'order' => 2,
            ],
        ];

        foreach ($fields2 as $field) {
            FormField::create(array_merge(['form_id' => $form2->id], $field));
        }
    }
}
