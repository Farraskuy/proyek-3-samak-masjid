## Samak Pro (Laravel Project)

### Deskripsi Singkat
Samak Pro adalah aplikasi manajemen masjid berbasis Laravel yang mendukung pengelolaan konten, jadwal kegiatan, keuangan, layanan jamaah, dan konsultasi. Proyek ini bertujuan membantu DKM dalam digitalisasi layanan dan transparansi informasi untuk jamaah.

### Fitur Utama
- Manajemen pengguna (Super Admin, Admin, Jamaah, Ustadz)
- Modul CMS: artikel, galeri, kategori, halaman statis
- Manajemen event/kegiatan dan pendaftaran online
- Modul keuangan: rekening bank, konfirmasi donasi, transaksi
- Layanan lost & found barang jamaah
- Konsultasi publik (Q&A) antara jamaah dan ustadz

---



## Cara Kontribusi & Setup Lokal


Ingin berkontribusi? Ikuti langkah berikut untuk menjalankan proyek ini secara lokal:


### 1. Clone repository
```bash
git clone https://github.com/Farraskuy/proyek-3-samak-pro.git
cd samak-pro
```


### 2. Install dependency
```bash
composer install
npm install
```


### 3. Salin & edit file environment
```bash
cp .env.example .env
```
Edit `.env` dan sesuaikan konfigurasi PostgreSQL Anda:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```


### 4. Generate application key
```bash
php artisan key:generate
```


### 5. Jalankan migrasi database
```bash
php artisan migrate
```


### 6. Jalankan server pengembangan
```bash
php artisan serve
```


### 7. Akses aplikasi
Buka [http://localhost:8000](http://localhost:8000) di browser Anda.

---
