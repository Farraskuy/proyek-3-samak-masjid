# LAPORAN ANALISIS SISTEM INFORMASI MANAJEMEN MASJID

## Samak Pro - Aplikasi Manajemen Masjid

---

# BAB 1: RANCANGAN

## 1.1 Latar Belakang

Masjid sebagai pusat kegiatan umat Islam memerlukan pengelolaan yang efektif dan efisien. **Samak Pro** hadir sebagai solusi digital untuk membantu pengurus DKM mengelola berbagai aspek kegiatan masjid secara terintegrasi.

## 1.2 Tujuan Aplikasi

1. **Digitalisasi Pengelolaan Masjid** - Mengubah proses manual menjadi digital
2. **Transparansi Keuangan** - Laporan keuangan publik
3. **Penyebaran Informasi** - Publikasi berita dan kegiatan
4. **Layanan Jamaah** - Konsultasi online dan barang hilang
5. **Pengelolaan Donasi** - Donasi online dengan kalkulator zakat

## 1.3 Ruang Lingkup

| Aspek    | Cakupan                           |
| -------- | --------------------------------- |
| Domain   | Manajemen Masjid & Layanan Jamaah |
| Platform | Aplikasi Web (Browser-based)      |
| Pengguna | Pengurus DKM & Jamaah Umum        |

## 1.4 Definisi Pengguna

| Role                  | Deskripsi               | Akses Dashboard |
| --------------------- | ----------------------- | --------------- |
| Super Admin           | Akses penuh development | ✅              |
| Admin                 | Mengelola user & roles  | ✅              |
| Koordinator Humas     | Approval postingan      | ✅              |
| Humas                 | Konten & konsultasi     | ✅              |
| Bendahara Pemasukan   | Keuangan masuk          | ✅              |
| Bendahara Pengeluaran | Keuangan keluar         | ✅              |
| Sarpras               | Barang hilang           | ✅              |
| Jamaah                | Pengguna umum           | ❌              |

---

# BAB 2: REQUIREMENT ANALISIS

## 2.1 Modul Autentikasi

**Nama Fitur:** Sistem Autentikasi Pengguna

**Deskripsi:** Mengelola proses login, registrasi, verifikasi email via OTP, dan reset password untuk mengamankan akses aplikasi.

| Sub-Fitur       | Deskripsi Detail                                               |
| --------------- | -------------------------------------------------------------- |
| Login           | Validasi email/password, cek verifikasi email, buat session    |
| Register        | Daftar akun baru dengan verifikasi captcha, kirim OTP ke email |
| Verifikasi OTP  | Konfirmasi kode 6 digit untuk aktivasi akun                    |
| Forgot Password | Reset password via link email token                            |
| Logout          | Hapus session dan redirect ke halaman login                    |

---

## 2.2 Modul Postingan

**Nama Fitur:** Manajemen Konten Postingan/Berita

**Deskripsi:** Mengelola artikel, berita, dan tausiyah dengan sistem approval workflow sebelum dipublikasikan.

### Status Postingan (5 Status)

| Status        | Kode        | Deskripsi Detail                                                      |
| ------------- | ----------- | --------------------------------------------------------------------- |
| **Draft**     | `draft`     | Postingan masih dalam tahap penyusunan, belum disubmit untuk review   |
| **Pending**   | `pending`   | Postingan sudah disubmit dan menunggu approval dari Koordinator Humas |
| **Revisi**    | `revisi`    | Postingan ditolak dan dikembalikan ke penulis untuk diperbaiki        |
| **Published** | `published` | Postingan sudah disetujui dan tampil di halaman publik                |
| **Arsip**     | `arsip`     | Postingan dinonaktifkan/diarsipkan, tidak tampil di publik            |

### Kategori Postingan

| Kategori | Deskripsi                              |
| -------- | -------------------------------------- |
| Berita   | Informasi umum tentang kegiatan masjid |
| Artikel  | Tulisan keislaman dan edukasi          |
| Tausiyah | Ceramah dan nasihat keagamaan          |

### Alur Status Postingan

```
Draft → Pending → [Approved] → Published → Arsip
              ↓
         [Rejected] → Revisi → Pending (ulang)
```

---

## 2.3 Modul Konsultasi

**Nama Fitur:** Layanan Konsultasi Online dengan Ustadz

**Deskripsi:** Fitur tanya jawab antara jamaah dengan ustadz secara privat dengan sistem chat real-time.

### Status Konsultasi (5 Status)

| Status       | Kode       | Deskripsi Detail                                    |
| ------------ | ---------- | --------------------------------------------------- |
| **Draft**    | `draft`    | Konsultasi baru dibuat (tidak digunakan aktif)      |
| **Pending**  | `pending`  | Konsultasi sudah disubmit, menunggu ustadz menerima |
| **Active**   | `active`   | Konsultasi diterima ustadz, chat dapat berlangsung  |
| **Rejected** | `rejected` | Konsultasi ditolak ustadz dengan alasan tertentu    |
| **Closed**   | `closed`   | Konsultasi selesai, tidak bisa kirim pesan lagi     |

### Alur Status Konsultasi

```
Pending → [Ustadz Accept] → Active → [Chat] → Closed
      ↓
 [Ustadz Reject] → Rejected (dengan rejection_reason)
```

### Fitur Tambahan

-   **Anonim:** Jamaah bisa konsultasi tanpa menampilkan nama
-   **Attachment:** Bisa kirim file/gambar dalam chat
-   **Real-time:** Menggunakan WebSocket (Laravel Reverb)

---

## 2.4 Modul Donasi

**Nama Fitur:** Sistem Donasi Online (Zakat, Infaq, Sedekah)

**Deskripsi:** Memfasilitasi donasi online dengan kalkulator zakat otomatis dan verifikasi bukti transfer.

### Status Donasi (3 Status)

| Status       | Kode       | Deskripsi Detail                                            |
| ------------ | ---------- | ----------------------------------------------------------- |
| **Pending**  | `Pending`  | Konfirmasi donasi sudah disubmit, menunggu verifikasi admin |
| **Verified** | `Verified` | Donasi disetujui, saldo otomatis masuk ke rekening tujuan   |
| **Rejected** | `Rejected` | Donasi ditolak (bukti tidak valid/transfer tidak sesuai)    |

### Jenis Donasi

| Kategori  | Jenis         | Deskripsi                           |
| --------- | ------------- | ----------------------------------- |
| **Zakat** | Zakat Maal    | 2.5% dari harta yang mencapai nisab |
| **Zakat** | Zakat Fitrah  | 3 kg beras per jiwa                 |
| **Zakat** | Zakat Profesi | Zakat dari penghasilan bulanan      |
| **Infaq** | Infaq Umum    | Donasi tanpa akad zakat             |
| **Infaq** | Program Infaq | Donasi untuk program tertentu       |

### Alur Status Donasi

```
User Submit Form → Pending → [Admin Verify] → Verified → Saldo Bertambah
                         ↓
                   [Admin Reject] → Rejected
```

---

## 2.5 Modul Keuangan

**Nama Fitur:** Pencatatan Transaksi Keuangan Masjid

**Deskripsi:** Mengelola pemasukan dan pengeluaran keuangan masjid dengan bukti transaksi.

### Tipe Transaksi (2 Tipe)

| Tipe            | Kode          | Deskripsi Detail                            |
| --------------- | ------------- | ------------------------------------------- |
| **Pemasukan**   | `pemasukan`   | Dana masuk (donasi, kotak amal, dll)        |
| **Pengeluaran** | `pengeluaran` | Dana keluar (operasional, pembangunan, dll) |

### Kategori Transaksi

**Pemasukan:** Donasi Online, Kotak Amal, Infaq, Zakat, Sumbangan
**Pengeluaran:** Operasional, Listrik/Air, Pembangunan, Kegiatan, Lainnya

---

## 2.6 Modul Barang Hilang & Ditemukan

**Nama Fitur:** Layanan Lost & Found

**Deskripsi:** Pengelolaan laporan barang hilang dari jamaah dan barang ditemukan oleh pengurus.

### Status Found Item (Barang Ditemukan)

| Status       | Kode       | Deskripsi Detail                                |
| ------------ | ---------- | ----------------------------------------------- |
| **Tersedia** | `Tersedia` | Barang masih tersimpan di masjid, belum diambil |
| **Diambil**  | `Diambil`  | Barang sudah diambil oleh pemilik               |

### Status Lost Item (Laporan Kehilangan)

| Status         | Kode         | Deskripsi Detail                              |
| -------------- | ------------ | --------------------------------------------- |
| **Aktif**      | `aktif`      | Laporan masih berlaku, barang belum ditemukan |
| **Kadaluarsa** | `kadaluarsa` | Laporan sudah melewati 30 hari                |

### Kategori Barang

-   Kendaraan
-   Elektronik
-   Aksesoris
-   Dokumen
-   Lain-lain

---

## 2.7 Modul Jadwal Kegiatan

**Nama Fitur:** Manajemen Jadwal Kegiatan Masjid

**Deskripsi:** Mengelola jadwal kajian, seminar, dan kegiatan masjid dengan kalender interaktif.

### Tipe Kegiatan

| Field                 | Deskripsi                            |
| --------------------- | ------------------------------------ |
| is_recurring          | Kegiatan berulang (mingguan/bulanan) |
| requires_registration | Perlu pendaftaran peserta            |

---

## 2.8 Modul Galeri

**Nama Fitur:** Galeri Foto Kegiatan

**Deskripsi:** Mengelola album foto dokumentasi kegiatan masjid.

---

## 2.9 Modul Form Builder

**Nama Fitur:** Pembuat Form Dinamis

**Deskripsi:** Membuat form pendaftaran/survey dengan field yang dapat dikustomisasi.

### Tipe Field

-   Text, Textarea, Number, Email
-   Select, Radio, Checkbox
-   Date, File

---

## 2.10 Modul Bank & Infaq

**Nama Fitur:** Manajemen Rekening Bank & Program Infaq

**Deskripsi:** Mengelola rekening tujuan donasi dan program infaq khusus.

### Kategori Bank

| Kategori | Deskripsi            |
| -------- | -------------------- |
| zakat    | Rekening untuk zakat |
| infaq    | Rekening untuk infaq |

### Tipe Bank

| Tipe       | Deskripsi                      |
| ---------- | ------------------------------ |
| kas        | Kas tunai (tidak bisa dihapus) |
| bank_zakat | Rekening bank untuk zakat      |
| bank_infaq | Rekening bank untuk infaq      |

---

## 2.11 Kebutuhan Non-Fungsional

### Keamanan

-   Authentication: Laravel Auth + Email Verification
-   Authorization: RBAC dengan 36 Permission
-   Middleware: CheckPermission, auth
-   Captcha: Untuk registrasi

---

# BAB 3: DATABASE SCHEMA

## 3.1 Tabel `users`

| Kolom             | Tipe Data    | Nullable | Deskripsi         |
| ----------------- | ------------ | -------- | ----------------- |
| id                | bigint       | No       | Primary key       |
| role_id           | bigint       | Yes      | FK ke roles       |
| username          | varchar(255) | No       | Username unik     |
| full_name         | varchar(100) | No       | Nama lengkap      |
| phone_number      | varchar(20)  | No       | Nomor telepon     |
| image_url         | varchar(255) | Yes      | URL foto profil   |
| email             | varchar(255) | No       | Email unik        |
| email_verified_at | timestamp    | Yes      | Waktu verifikasi  |
| password          | varchar(255) | No       | Password hash     |
| remember_token    | varchar(100) | Yes      | Token remember me |
| created_at        | timestamp    | Yes      | Waktu dibuat      |
| updated_at        | timestamp    | Yes      | Waktu diupdate    |

## 3.2 Tabel `roles`

| Kolom       | Tipe Data    | Nullable | Deskripsi      |
| ----------- | ------------ | -------- | -------------- |
| id          | bigint       | No       | Primary key    |
| name        | varchar(255) | No       | Nama role      |
| alias       | varchar(255) | No       | Alias tampilan |
| description | text         | Yes      | Deskripsi role |
| created_at  | timestamp    | Yes      | Waktu dibuat   |
| updated_at  | timestamp    | Yes      | Waktu diupdate |

## 3.3 Tabel `permissions`

| Kolom | Tipe Data    | Nullable | Deskripsi       |
| ----- | ------------ | -------- | --------------- |
| id    | bigint       | No       | Primary key     |
| name  | varchar(255) | No       | Nama permission |
| group | varchar(255) | No       | Grup permission |

## 3.4 Tabel `postingans`

| Kolom              | Tipe Data    | Nullable | Deskripsi                              |
| ------------------ | ------------ | -------- | -------------------------------------- |
| id                 | bigint       | No       | Primary key                            |
| user_id            | bigint       | No       | FK ke users (penulis)                  |
| title              | varchar(255) | No       | Judul postingan                        |
| slug               | varchar(270) | No       | URL slug unik                          |
| keterangan         | text         | Yes      | Ringkasan/excerpt                      |
| content            | text         | No       | Isi konten (HTML)                      |
| featured_image_url | varchar(255) | Yes      | URL gambar utama                       |
| status             | enum         | No       | `draft,pending,revisi,published,arsip` |
| kategori           | enum         | No       | `Berita,Artikel,Tausiyah`              |
| approval_note      | text         | Yes      | Catatan approval/rejection             |
| approved_by        | bigint       | Yes      | FK ke users (approver)                 |
| approved_at        | timestamp    | Yes      | Waktu approval                         |
| published_at       | timestamp    | Yes      | Waktu publish                          |
| created_at         | timestamp    | Yes      | Waktu dibuat                           |
| updated_at         | timestamp    | Yes      | Waktu diupdate                         |

## 3.5 Tabel `consultations`

| Kolom                 | Tipe Data    | Nullable | Deskripsi                             |
| --------------------- | ------------ | -------- | ------------------------------------- |
| id                    | bigint       | No       | Primary key                           |
| question_subject      | varchar(150) | No       | Subjek pertanyaan                     |
| question_text         | text         | No       | Isi pertanyaan                        |
| question_from         | varchar(100) | No       | Nama penanya (default: 'Hamba Allah') |
| answer_text           | text         | Yes      | Jawaban ustadz                        |
| rejection_reason      | text         | Yes      | Alasan penolakan                      |
| conclusion            | text         | Yes      | Kesimpulan konsultasi                 |
| status                | varchar(20)  | No       | `pending,active,rejected,closed`      |
| is_anonymous          | boolean      | No       | Apakah anonim                         |
| user_id               | bigint       | No       | FK ke users (jamaah)                  |
| answered_by_ustadz_id | bigint       | Yes      | FK ke users (ustadz)                  |
| created_at            | timestamp    | No       | Waktu dibuat                          |
| answered_at           | timestamp    | Yes      | Waktu dijawab                         |
| published_at          | timestamp    | Yes      | Waktu publish                         |
| closed_at             | timestamp    | Yes      | Waktu ditutup                         |

## 3.6 Tabel `donation_confirmations`

| Kolom                  | Tipe Data     | Nullable | Deskripsi                                  |
| ---------------------- | ------------- | -------- | ------------------------------------------ |
| confirmation_id        | bigint        | No       | Primary key                                |
| user_id                | bigint        | Yes      | FK ke users (donatur login)                |
| guest_name             | varchar(100)  | Yes      | Nama donatur guest                         |
| donation_type          | varchar(100)  | No       | Jenis donasi (zakat_maal, infaq_umum, dll) |
| amount                 | decimal(15,2) | No       | Jumlah donasi                              |
| transfer_date          | date          | No       | Tanggal transfer                           |
| destination_account_id | bigint        | No       | FK ke bank_accounts                        |
| source_bank            | varchar(50)   | No       | Bank pengirim                              |
| proof_image_url        | varchar(255)  | No       | URL bukti transfer                         |
| notes                  | varchar(255)  | No       | Catatan                                    |
| status                 | varchar(20)   | No       | `Pending,Verified,Rejected`                |
| verified_by            | bigint        | Yes      | FK ke users (verifikator)                  |
| verified_at            | timestamp     | Yes      | Waktu verifikasi                           |
| created_at             | timestamp     | Yes      | Waktu dibuat                               |
| updated_at             | timestamp     | Yes      | Waktu diupdate                             |

## 3.7 Tabel `bank_accounts`

| Kolom               | Tipe Data     | Nullable | Deskripsi                   |
| ------------------- | ------------- | -------- | --------------------------- |
| account_id          | bigint        | No       | Primary key                 |
| bank_name           | varchar(50)   | No       | Nama bank                   |
| account_number      | varchar(50)   | Yes      | Nomor rekening              |
| account_holder_name | varchar(100)  | No       | Nama pemilik rekening       |
| logo_url            | varchar(255)  | Yes      | URL logo bank               |
| category            | enum          | No       | `zakat,infaq`               |
| type                | enum          | No       | `kas,bank_zakat,bank_infaq` |
| balance             | decimal(15,2) | No       | Saldo saat ini              |
| is_deletable        | boolean       | No       | Bisa dihapus?               |
| is_active           | boolean       | No       | Aktif?                      |
| deleted_at          | timestamp     | Yes      | Soft delete                 |

## 3.8 Tabel `financial_transactions`

| Kolom            | Tipe Data     | Nullable | Deskripsi               |
| ---------------- | ------------- | -------- | ----------------------- |
| id               | bigint        | No       | Primary key             |
| type             | enum          | No       | `pemasukan,pengeluaran` |
| bank_name        | varchar(255)  | No       | Nama bank terkait       |
| amount           | decimal(15,2) | No       | Jumlah transaksi        |
| category         | varchar(255)  | No       | Kategori transaksi      |
| description      | text          | Yes      | Deskripsi               |
| transaction_date | date          | No       | Tanggal transaksi       |
| proof_image_url  | varchar(255)  | No       | URL bukti               |
| user_id          | bigint        | No       | FK ke users (pencatat)  |
| created_at       | timestamp     | Yes      | Waktu dibuat            |
| updated_at       | timestamp     | Yes      | Waktu diupdate          |

## 3.9 Tabel `lost_items` (Laporan Kehilangan)

| Kolom                | Tipe Data    | Nullable | Deskripsi             |
| -------------------- | ------------ | -------- | --------------------- |
| id                   | bigint       | No       | Primary key           |
| reported_by_admin_id | bigint       | No       | FK ke users           |
| category_id          | bigint       | No       | FK ke item_categories |
| item_name            | varchar(255) | No       | Nama barang           |
| description          | text         | No       | Deskripsi             |
| location_lost        | varchar(255) | Yes      | Lokasi hilang         |
| lost_at              | date         | No       | Tanggal hilang        |
| expiry_date          | date         | No       | Tanggal kadaluarsa    |
| status               | enum         | No       | `aktif,kadaluarsa`    |
| created_at           | timestamp    | Yes      | Waktu dibuat          |
| updated_at           | timestamp    | Yes      | Waktu diupdate        |

## 3.10 Tabel `found_items` (Barang Ditemukan)

| Kolom               | Tipe Data    | Nullable | Deskripsi          |
| ------------------- | ------------ | -------- | ------------------ |
| item_id             | bigint       | No       | Primary key        |
| inputted_by_user_id | bigint       | No       | FK ke users        |
| item_name           | varchar(100) | No       | Nama barang        |
| description         | text         | No       | Deskripsi          |
| location_found      | varchar(100) | No       | Lokasi ditemukan   |
| featured_image_url  | varchar(255) | Yes      | URL gambar         |
| category            | varchar(50)  | No       | Kategori barang    |
| status              | varchar(30)  | No       | `Tersedia,Diambil` |
| created_at          | timestamp    | No       | Waktu dibuat       |
| updated_at          | timestamp    | Yes      | Waktu diupdate     |

## 3.11 Tabel `events` (Jadwal Kegiatan)

| Kolom                 | Tipe Data    | Nullable | Deskripsi              |
| --------------------- | ------------ | -------- | ---------------------- |
| event_id              | bigint       | No       | Primary key            |
| event_name            | varchar(200) | No       | Nama kegiatan          |
| ustadz_user_id        | bigint       | No       | FK ke users (pemateri) |
| theme                 | varchar(255) | No       | Tema kegiatan          |
| start_time            | timestamp    | No       | Waktu mulai            |
| end_time              | timestamp    | No       | Waktu selesai          |
| location              | varchar(100) | No       | Lokasi                 |
| is_recurring          | boolean      | No       | Kegiatan berulang?     |
| requires_registration | boolean      | No       | Perlu registrasi?      |
| poster_url            | varchar(255) | Yes      | URL poster             |
| created_by            | bigint       | No       | FK ke users            |
| created_at            | timestamp    | No       | Waktu dibuat           |

---

# BAB 4: USER INTERFACE

## 4.1 Halaman Admin (53 View Files)

### Modul Postingan

| File                      | Path             | Deskripsi                                      |
| ------------------------- | ---------------- | ---------------------------------------------- |
| index.blade.php           | admin/postingan/ | Daftar semua postingan dengan filter & search  |
| tambah.blade.php          | admin/postingan/ | Form tambah postingan baru dengan Quill editor |
| edit.blade.php            | admin/postingan/ | Form edit postingan existing                   |
| approval_index.blade.php  | admin/postingan/ | Daftar postingan pending approval              |
| approval_detail.blade.php | admin/postingan/ | Detail postingan untuk review & approve/reject |

### Modul Konsultasi

| File                   | Path                 | Deskripsi                              |
| ---------------------- | -------------------- | -------------------------------------- |
| index.blade.php        | admin/consultations/ | Daftar konsultasi dengan filter status |
| show_partial.blade.php | admin/consultations/ | Panel chat konsultasi (AJAX loaded)    |

### Modul Donasi

| File              | Path          | Deskripsi                                   |
| ----------------- | ------------- | ------------------------------------------- |
| index.blade.php   | admin/donasi/ | Daftar konfirmasi donasi dengan tabs status |
| offline.blade.php | admin/donasi/ | Form input donasi offline                   |

### Modul Keuangan

| File            | Path            | Deskripsi                                          |
| --------------- | --------------- | -------------------------------------------------- |
| index.blade.php | admin/keuangan/ | Dashboard keuangan dengan grafik & input transaksi |

### Modul Bank

| File             | Path         | Deskripsi            |
| ---------------- | ------------ | -------------------- |
| index.blade.php  | admin/banks/ | Daftar rekening bank |
| create.blade.php | admin/banks/ | Form tambah rekening |
| edit.blade.php   | admin/banks/ | Form edit rekening   |

### Modul Infaq

| File             | Path          | Deskripsi                 |
| ---------------- | ------------- | ------------------------- |
| index.blade.php  | admin/infaqs/ | Daftar program infaq      |
| create.blade.php | admin/infaqs/ | Form tambah program infaq |
| edit.blade.php   | admin/infaqs/ | Form edit program infaq   |

### Modul Galeri

| File             | Path          | Deskripsi                       |
| ---------------- | ------------- | ------------------------------- |
| index.blade.php  | admin/galeri/ | Daftar album galeri             |
| create.blade.php | admin/galeri/ | Form tambah album + upload foto |
| edit.blade.php   | admin/galeri/ | Form edit album, kelola foto    |

### Modul Kegiatan

| File             | Path            | Deskripsi              |
| ---------------- | --------------- | ---------------------- |
| index.blade.php  | admin/kegiatan/ | Daftar jadwal kegiatan |
| create.blade.php | admin/kegiatan/ | Form tambah kegiatan   |
| edit.blade.php   | admin/kegiatan/ | Form edit kegiatan     |

### Modul Lost & Found

| File             | Path                    | Deskripsi                    |
| ---------------- | ----------------------- | ---------------------------- |
| index.blade.php  | admin/lost-found/found/ | Daftar barang ditemukan      |
| create.blade.php | admin/lost-found/found/ | Form tambah barang ditemukan |
| edit.blade.php   | admin/lost-found/found/ | Form edit barang ditemukan   |
| index.blade.php  | admin/lost-found/lost/  | Daftar laporan kehilangan    |
| create.blade.php | admin/lost-found/lost/  | Form tambah laporan          |
| edit.blade.php   | admin/lost-found/lost/  | Form edit laporan            |

### Modul Form Builder

| File                    | Path         | Deskripsi                           |
| ----------------------- | ------------ | ----------------------------------- |
| index.blade.php         | admin/forms/ | Daftar form yang dibuat             |
| create.blade.php        | admin/forms/ | Form builder dengan drag-drop field |
| edit.blade.php          | admin/forms/ | Edit form existing                  |
| responses.blade.php     | admin/forms/ | Daftar respons form                 |
| response_show.blade.php | admin/forms/ | Detail respons individual           |

### Modul Users & Roles

| File             | Path         | Deskripsi                            |
| ---------------- | ------------ | ------------------------------------ |
| index.blade.php  | admin/users/ | Daftar pengguna dengan filter role   |
| create.blade.php | admin/users/ | Form tambah pengguna                 |
| edit.blade.php   | admin/users/ | Form edit pengguna                   |
| index.blade.php  | admin/roles/ | Daftar role                          |
| create.blade.php | admin/roles/ | Form tambah role + assign permission |
| edit.blade.php   | admin/roles/ | Form edit role                       |

### Modul Kotak Amal

| File             | Path              | Deskripsi                              |
| ---------------- | ----------------- | -------------------------------------- |
| index.blade.php  | admin/kotak_amal/ | Daftar pendataan kotak amal            |
| create.blade.php | admin/kotak_amal/ | Form input dengan tanda tangan digital |
| show.blade.php   | admin/kotak_amal/ | Detail pendataan                       |

### Modul Website Information

| File            | Path                       | Deskripsi             |
| --------------- | -------------------------- | --------------------- |
| index.blade.php | admin/website-information/ | Kelola halaman statis |

### Modul Profil Admin

| File               | Path           | Deskripsi          |
| ------------------ | -------------- | ------------------ |
| index.blade.php    | admin/profile/ | Lihat profil admin |
| edit.blade.php     | admin/profile/ | Edit profil        |
| password.blade.php | admin/profile/ | Ubah password      |

---

## 4.2 Halaman Client/Publik (26 View Files)

### Halaman Umum

| File             | Path    | Deskripsi                          |
| ---------------- | ------- | ---------------------------------- |
| home.blade.php   | client/ | Landing page website               |
| layout.blade.php | client/ | Master layout untuk halaman client |

### Modul Postingan

| File             | Path              | Deskripsi                    |
| ---------------- | ----------------- | ---------------------------- |
| index.blade.php  | client/postingan/ | Daftar berita/artikel publik |
| detail.blade.php | client/postingan/ | Detail artikel lengkap       |

### Modul Donasi

| File                      | Path           | Deskripsi                           |
| ------------------------- | -------------- | ----------------------------------- |
| index.blade.php           | client/donasi/ | Form donasi dengan kalkulator zakat |
| informasi/index.blade.php | client/donasi/ | Informasi tentang donasi            |
| konfirmasi.blade.php      | client/donasi/ | Form konfirmasi transfer            |
| sukses.blade.php          | client/donasi/ | Halaman sukses donasi               |

### Modul Konsultasi

| File              | Path                  | Deskripsi               |
| ----------------- | --------------------- | ----------------------- |
| landing.blade.php | client/consultations/ | Landing page konsultasi |
| show.blade.php    | client/consultations/ | Chat room konsultasi    |
| history.blade.php | client/consultations/ | Riwayat konsultasi user |

### Modul Jadwal Kegiatan

| File             | Path                   | Deskripsi                    |
| ---------------- | ---------------------- | ---------------------------- |
| jadwal.blade.php | client/jadwalKegiatan/ | Kalender kegiatan interaktif |
| detail.blade.php | client/jadwalKegiatan/ | Detail kegiatan              |
| today.blade.php  | client/jadwalKegiatan/ | Kegiatan hari ini (partial)  |

### Modul Galeri

| File             | Path           | Deskripsi                |
| ---------------- | -------------- | ------------------------ |
| index.blade.php  | client/galeri/ | Daftar album galeri      |
| detail.blade.php | client/galeri/ | Detail album dengan foto |

### Modul Keuangan

| File            | Path             | Deskripsi               |
| --------------- | ---------------- | ----------------------- |
| index.blade.php | client/keuangan/ | Laporan keuangan publik |

### Modul Lost & Found

| File            | Path                                    | Deskripsi                         |
| --------------- | --------------------------------------- | --------------------------------- |
| index.blade.php | client/layanan/barang-hilang-ditemukan/ | Pencarian barang hilang/ditemukan |

### Modul Form

| File           | Path          | Deskripsi             |
| -------------- | ------------- | --------------------- |
| fill.blade.php | client/forms/ | Pengisian form publik |

### Modul Profil

| File                           | Path            | Deskripsi            |
| ------------------------------ | --------------- | -------------------- |
| edit.blade.php                 | client/profile/ | Edit profil user     |
| password.blade.php             | client/profile/ | Ubah password        |
| consultation-create.blade.php  | client/profile/ | Buat konsultasi baru |
| consultation-history.blade.php | client/profile/ | Riwayat konsultasi   |

### Halaman Statis

| File               | Path                        | Deskripsi            |
| ------------------ | --------------------------- | -------------------- |
| about-us.blade.php | client/website-information/ | Halaman Tentang Kami |

---

# BAB 5: RANCANGAN MODUL (Detailed Design)

## 5.1 Modul Postingan - State Machine

### Diagram Transisi Status

```
┌─────────┐    Submit     ┌─────────┐    Approve    ┌───────────┐
│  DRAFT  │──────────────►│ PENDING │──────────────►│ PUBLISHED │
└─────────┘               └─────────┘               └───────────┘
                               │                         │
                          Reject│                    Archive│
                               ▼                         ▼
                          ┌─────────┐               ┌─────────┐
                          │ REVISI  │               │  ARSIP  │
                          └─────────┘               └─────────┘
                               │
                          Re-submit
                               ▼
                          ┌─────────┐
                          │ PENDING │ (kembali ke pending)
                          └─────────┘
```

### Detail Input/Output per Aksi

#### Aksi: Buat Postingan Baru

| Input          | Tipe   | Validasi                             |
| -------------- | ------ | ------------------------------------ |
| title          | string | required, max:255                    |
| featured_image | file   | required, image, max:5MB             |
| content        | html   | required                             |
| kategori       | enum   | required, in:Berita,Artikel,Tausiyah |

| Output                       | Kondisi                            |
| ---------------------------- | ---------------------------------- |
| Redirect ke daftar + success | Postingan tersimpan status=pending |
| Error validasi               | Data tidak valid                   |

#### Aksi: Approve Postingan

| Input  | Tipe   | Validasi                  |
| ------ | ------ | ------------------------- |
| action | string | required, value='approve' |

| Output       | Perubahan Database   |
| ------------ | -------------------- |
| status       | pending → published  |
| approved_by  | ID user yang approve |
| approved_at  | Timestamp sekarang   |
| published_at | Timestamp sekarang   |

#### Aksi: Reject Postingan

| Input         | Tipe   | Validasi                 |
| ------------- | ------ | ------------------------ |
| action        | string | required, value='reject' |
| approval_note | string | required, min:10         |

| Output        | Perubahan Database  |
| ------------- | ------------------- |
| status        | pending → revisi    |
| approval_note | Catatan rejection   |
| approved_by   | ID user yang reject |

---

## 5.2 Modul Konsultasi - State Machine

### Diagram Transisi Status

```
┌─────────┐   Accept    ┌────────┐    Close     ┌────────┐
│ PENDING │────────────►│ ACTIVE │─────────────►│ CLOSED │
└─────────┘             └────────┘              └────────┘
     │
     │ Reject
     ▼
┌──────────┐
│ REJECTED │
└──────────┘
```

### Detail per Status

| Status   | Aksi Yang Tersedia  | Siapa           |
| -------- | ------------------- | --------------- |
| pending  | accept, reject      | Ustadz          |
| active   | send_message, close | Jamaah & Ustadz |
| rejected | - (final)           | -               |
| closed   | - (final)           | -               |

#### Aksi: Accept Konsultasi

| Validasi         | Deskripsi                      |
| ---------------- | ------------------------------ |
| status=pending   | Hanya bisa accept jika pending |
| active_count < 5 | Ustadz max 5 konsultasi aktif  |

| Output                | Perubahan Database |
| --------------------- | ------------------ |
| status                | pending → active   |
| answered_by_ustadz_id | ID ustadz          |
| answered_at           | Timestamp sekarang |

#### Aksi: Kirim Pesan

| Input      | Validasi                |
| ---------- | ----------------------- |
| message    | required, max:5000      |
| attachment | optional, file, max:5MB |

| Validasi Pre-condition              |
| ----------------------------------- |
| Konsultasi status = active          |
| User = pemilik atau ustadz assigned |

---

## 5.3 Modul Donasi - State Machine

### Diagram Transisi Status

```
┌─────────┐   Verify    ┌──────────┐
│ PENDING │────────────►│ VERIFIED │ ──► Saldo bank bertambah
└─────────┘             └──────────┘
     │
     │ Reject
     ▼
┌──────────┐
│ REJECTED │
└──────────┘
```

### Side Effect saat Verified

Ketika admin meng-approve donasi:

1. `status` → `Verified`
2. `verified_by` → ID admin
3. `verified_at` → Timestamp
4. Bank account `balance` += `amount`
5. Record `financial_transaction` type=pemasukan

---

# BAB 6: MODEL IMPLEMENTASI

## 6.1 RBAC (Role-Based Access Control)

```php
// Cek permission di Controller
if ($user->hasPermission('create_posts')) {
    // Aksi diizinkan
}

// Cek permission di Middleware
Route::get('/admin/posts', [...])->middleware('permission:view_posts');

// Multiple permission (OR)
->middleware('permission:manage_income|manage_expense')
```

## 6.2 Status Enum Pattern

```php
// Model Postingan
const STATUS_DRAFT = 'draft';
const STATUS_PENDING = 'pending';
const STATUS_REVISI = 'revisi';
const STATUS_PUBLISHED = 'published';
const STATUS_ARSIP = 'arsip';

// Transisi status di Controller
public function approvalUpdate(Request $request, $id)
{
    $post = Postingan::findOrFail($id);

    if ($request->action === 'approve') {
        $post->status = 'published';
        $post->approved_by = Auth::id();
        $post->approved_at = now();
        $post->published_at = now();
    } else {
        $post->status = 'revisi';
        $post->approval_note = $request->approval_note;
    }

    $post->save();
}
```

---

# BAB 7: SKENARIO PENGUJIAN

## 7.1 Pengujian Status Postingan

| No  | Status Awal | Aksi      | Data Input                | Status Akhir | Hasil                     |
| --- | ----------- | --------- | ------------------------- | ------------ | ------------------------- |
| 1   | -           | Create    | Data valid                | pending      | Postingan tersimpan       |
| 2   | pending     | Approve   | action=approve            | published    | Tampil di publik          |
| 3   | pending     | Reject    | action=reject, note="..." | revisi       | Dikembalikan ke penulis   |
| 4   | revisi      | Re-submit | Edit & submit             | pending      | Kembali menunggu approval |
| 5   | published   | Archive   | Klik arsip                | arsip        | Tidak tampil di publik    |

## 7.2 Pengujian Status Konsultasi

| No  | Status Awal | Aksi         | Aktor         | Status Akhir | Hasil                   |
| --- | ----------- | ------------ | ------------- | ------------ | ----------------------- |
| 1   | -           | Create       | Jamaah        | pending      | Konsultasi dibuat       |
| 2   | pending     | Accept       | Ustadz        | active       | Chat bisa dimulai       |
| 3   | pending     | Reject       | Ustadz        | rejected     | Dengan rejection_reason |
| 4   | active      | Send Message | Jamaah/Ustadz | active       | Pesan tersimpan         |
| 5   | active      | Close        | Ustadz        | closed       | Chat tidak bisa lagi    |

## 7.3 Pengujian Status Donasi

| No  | Status Awal | Aksi    | Data        | Status Akhir | Side Effect               |
| --- | ----------- | ------- | ----------- | ------------ | ------------------------- |
| 1   | -           | Submit  | Bukti valid | Pending      | Record confirmation       |
| 2   | Pending     | Approve | -           | Verified     | Saldo bank +amount        |
| 3   | Pending     | Reject  | -           | Rejected     | Tidak ada perubahan saldo |

## 7.4 Pengujian Status Barang Ditemukan

| No  | Status Awal | Aksi          | Status Akhir | Deskripsi                    |
| --- | ----------- | ------------- | ------------ | ---------------------------- |
| 1   | -           | Create        | Tersedia     | Barang baru dicatat          |
| 2   | Tersedia    | Update status | Diambil      | Barang sudah diklaim pemilik |

## 7.5 Pengujian Status Laporan Kehilangan

| No  | Status Awal | Kondisi             | Status Akhir | Deskripsi                                |
| --- | ----------- | ------------------- | ------------ | ---------------------------------------- |
| 1   | -           | Create              | aktif        | Laporan baru, expiry = lost_at + 30 hari |
| 2   | aktif       | now() > expiry_date | kadaluarsa   | Otomatis expired                         |

## 7.6 Pengujian Permission

| No  | Role       | Route                          | Permission    | Expected   |
| --- | ---------- | ------------------------------ | ------------- | ---------- |
| 1   | Humas      | POST /admin/postingan          | create_posts  | ✅ Allowed |
| 2   | Humas      | POST /admin/postingan/approval | approve_posts | ❌ 403     |
| 3   | Koor Humas | POST /admin/postingan/approval | approve_posts | ✅ Allowed |
| 4   | Bendahara  | GET /admin/keuangan            | view_finance  | ✅ Allowed |
| 5   | Bendahara  | POST /admin/postingan          | create_posts  | ❌ 403     |
| 6   | Jamaah     | GET /admin/\*                  | -             | ❌ 403     |

## 7.7 Pengujian Keuangan

| No  | Aksi        | Saldo Awal | Amount    | Saldo Akhir | Validation           |
| --- | ----------- | ---------- | --------- | ----------- | -------------------- |
| 1   | Pemasukan   | 0          | 1.000.000 | 1.000.000   | ✅ OK                |
| 2   | Pengeluaran | 1.000.000  | 500.000   | 500.000     | ✅ OK                |
| 3   | Pengeluaran | 500.000    | 1.000.000 | ERROR       | ❌ Saldo tidak cukup |

---

# LAMPIRAN

## A. Akun Demo

| Role                  | Email                | Password    |
| --------------------- | -------------------- | ----------- |
| Super Admin           | superadmin@samak.com | password123 |
| Admin                 | admin@samak.com      | password123 |
| Humas                 | humas@samak.com      | password123 |
| Bendahara Pemasukan   | bendaharaM@samak.com | password123 |
| Bendahara Pengeluaran | bendaharaK@samak.com | password123 |
| Sarpras               | sarpras@samak.com    | password123 |
| Jamaah                | jamaah@samak.com     | password123 |
| Koordinator Humas     | koor_humas@samak.com | password123 |

---

_Dokumen ini dibuat berdasarkan analisis kode sumber proyek Samak Pro - Desember 2025_
