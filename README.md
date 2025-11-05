# Samak Pro (Aplikasi Manajemen Masjid)

### Tentang Aplikasi
Samak Pro adalah aplikasi untuk membantu pengurus DKM masjid mengelola berbagai kegiatan masjid secara digital. Aplikasi ini memudahkan DKM untuk mengatur informasi, jadwal kajian, keuangan, layanan jamaah, dan konsultasi keagamaan. Dengan Samak Pro, jamaah juga bisa dengan mudah mendapatkan informasi terkini tentang kegiatan masjid.

### Apa Yang Bisa Dilakukan?
1. **Pengaturan Pengguna**
   - Super Admin: Pengurus utama yang mengatur seluruh sistem
   - Admin: Pengurus yang mengelola konten dan informasi
   - Ustadz: Pemateri kajian dan konsultan agama
   - Jamaah: Pengguna umum yang bisa mengakses informasi

2. **Informasi & Konten**
   - Menulis dan membagikan artikel keislaman
   - Mengupload foto kegiatan masjid
   - Membuat halaman informasi penting
   - Mengelompokkan konten sesuai kategori

3. **Jadwal & Kegiatan**
   - Mengatur jadwal kajian/seminar
   - Pendaftaran online untuk jamaah
   - Pemberitahuan kegiatan terbaru

4. **Keuangan Masjid**
   - Pencatatan infaq dan donasi
   - Konfirmasi transfer donasi
   - Laporan keuangan transparan
   - Informasi rekening masjid

5. **Layanan Jamaah**
   - Pencarian barang hilang/ketinggalan
   - Tanya jawab dengan ustadz
   - Konsultasi keagamaan online

## Panduan Kolaborasi

### Prerequisites
- PHP >= 8.1
- Composer
- PostgreSQL >= 14
- Git


### 1. Clone repository
```bash
git clone https://github.com/Farraskuy/proyek-3-samak-pro.git
cd samak-pro
```


### 2. Install dependency
```bash
composer install
```


### 3. Salin & edit file environment
Manual atau gunakan command berikut


```bash
cp .env.example .env
```


#### Setting .env untuk Mode Development
Pastikan beberapa variabel berikut di `.env` untuk pengembangan lokal:
```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
LOG_CHANNEL=stack
```

#### Setting .env konfigurasi PostgreSQL:


##### 1. Menyiapkan Database (Skip jika sudah paham)
1. Download dan install PostgreSQL dari [postgresql.org](https://www.postgresql.org/download/)
2. Buat database untuk menyimpan data aplikasi:
   ```sql
   # Masuk ke PostgreSQL (akan diminta password)
   psql -U postgres

   # Buat database baru
   CREATE DATABASE proyek3;

   # Buat pengguna baru (tidak wajib)
   CREATE USER samakuser WITH ENCRYPTED PASSWORD 'password_anda';
   GRANT ALL PRIVILEGES ON DATABASE proyek3 TO samakuser;

   # Keluar dari PostgreSQL
   \q
   ```

##### 2. Aktifkan ekstensi PostgreSQL di PHP (Opsional jika sudah dilakukan):
   - Buka file `php.ini`
   - Hapus tanda `;` pada:
     ```ini
     extension=pgsql
     extension=pdo_pgsql
     ```
   - Restart web server/terminal
   - Cek/Verifikasi dengan:
     ```bash
     php -m | findstr pgsql
     ```

##### 3. Konfigurasi .env lanjutan
Cari pada .env 

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
