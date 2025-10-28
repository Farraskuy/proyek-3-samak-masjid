## Samak Pro (Laravel Project)


### Deskripsi Singkat
Samak Pro adalah aplikasi manajemen masjid berbasis Laravel yang mendukung pengelolaan konten, jadwal kegiatan, keuangan, layanan jamaah, dan konsultasi. Proyek ini bertujuan membantu DKM dalam digitalisasi layanan dan transparansi informasi untuk jamaah.

### Struktur Proyek

```
├── app/
│   ├── Http/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── assets/
│   └── index.php
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── web.php
│   └── console.php
├── storage/
├── tests/
│   ├── Feature/
│   ├── Unit/
│   └── TestCase.php
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
└── README.md
```

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

## Troubleshooting: Error "could not find driver" (PostgreSQL)

Jika saat migrate muncul error:

```
could not find driver (Connection: pgsql, SQL: ...)
```

Solusi:

1. Buka file `php.ini` (lokasi PHP yang digunakan di terminal).
2. Pastikan baris berikut tidak dikomentari (hilangkan tanda `;` di depannya):
	```
	extension=pgsql
	extension=pdo_pgsql
	```
3. Simpan file dan restart web server/terminal.
4. Cek dengan perintah:
	```bash
	php -m | findstr pgsql
	```
	Output harus memunculkan `pgsql` dan `pdo_pgsql`.
5. Jalankan ulang:
	```bash
	php artisan migrate
	```

Jika masih error, pastikan database PostgreSQL berjalan dan kredensial di `.env` sudah benar.

---
