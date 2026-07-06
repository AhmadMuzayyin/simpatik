# SIMPATIK - Sistem Prediksi Tauladan & Ranking Kelas

SIMPATIK adalah sebuah aplikasi berbasis web yang dibangun menggunakan **Laravel** dan **Livewire** dengan antarmuka **Flux UI**. Aplikasi ini dirancang khusus untuk jenjang pendidikan (MI, MTs, MA, dll) untuk membantu sekolah dalam menentukan **Siswa Tauladan** terbaik secara keseluruhan dan **Ranking 1-10** dari setiap kelas menggunakan algoritma machine learning **Naive Bayes**.

## 🚀 Fitur Utama

- **Dashboard Informatif**: Menampilkan ringkasan statistik sekolah dan menyoroti Siswa Tauladan tahun ini.
- **Master Data Lengkap**:
  - **Data Kelas**: Kelola kelas sekolah.
  - **Mata Pelajaran**: Kelola mata pelajaran yang diajarkan.
  - **Siswa**: Kelola data siswa lengkap (bisa *import* via Excel yang sudah berisi ID kelas).
  - **Master Nilai**: Pengelolaan nilai secara efisien melalui format *Group by Siswa*. Mendukung *import* dari Excel untuk mempercepat pengisian nilai Mapel dan Nilai Harian (Pengetahuan, Keterampilan, Sikap).
- **Modul Machine Learning**:
  - **Preprocessing**: Sistem secara otomatis merekap dan menormalisasi seluruh komponen nilai (Rata-rata Mapel, Pengetahuan, Keterampilan, Sikap) dari setiap siswa sebagai persiapan *dataset*.
  - **Prediksi Naive Bayes**: Algoritma menghitung skor probabilitas (*posterior*) berdasarkan kriteria nilai untuk menentukan peringkat (ranking) dan kualifikasi siswa tauladan.
- **Laporan Otomatis**:
  - Menyajikan penobatan 1 Siswa Tauladan Terbaik.
  - Menyajikan Top 10 Ranking di masing-masing kelas dalam bentuk tab.
  - Fitur cetak laporan (*Print*).
- **Pengaturan Aplikasi (Settings)**: Atur Nama Aplikasi, Nama Lembaga, Upload Logo, dan Favicon sesuai dengan identitas instansi pendidikan.
- **Tema Dinamis**: Mendukung *Light Mode* (tema latar biru langit cerah/sky) dan *Dark Mode* (sky + abu-abu pekat) menggunakan *Tailwind CSS*.

## 🛠️ Teknologi yang Digunakan

- [Laravel](https://laravel.com/) (v11)
- [Livewire](https://livewire.laravel.com/)
- [Flux UI](https://fluxui.dev/) (UI Components)
- [Tailwind CSS](https://tailwindcss.com/) (v4)
- Database (MySQL / SQLite / PostgreSQL)

## 📦 Instalasi

Ikuti langkah-langkah berikut untuk menjalankan SIMPATIK di environment lokal Anda:

1. **Clone repository ini:**
   ```bash
   git clone <repo-url> simpatik
   cd simpatik
   ```

2. **Install dependensi PHP (Composer):**
   ```bash
   composer install
   ```

3. **Install dependensi Node.js (NPM):**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env` dan atur konfigurasi database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Jalankan Migrasi dan Seeder:**
   *(Seeder sudah termasuk akun admin, data kelas dummy, mapel, siswa, dsb)*
   ```bash
   php artisan migrate --seed
   ```

6. **Link Storage (Wajib untuk Logo/Favicon):**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses pada `http://localhost:8000`.

## 🔑 Akun Default (Seeder)

Anda dapat masuk ke dalam aplikasi menggunakan akun default administrator:
- **Email:** admin@admin.com
- **Password:** password

## 📝 Alur Penggunaan

1. Login sebagai **Admin**.
2. Jika belum ada, masukkan/import **Data Kelas**, **Mata Pelajaran**, dan **Siswa**.
3. Inputkan **Nilai Mapel** dan **Nilai Harian** untuk setiap siswa. Anda bisa menggunakan format *import Excel* untuk kecepatan.
4. Masuk ke menu **Preprocessing** dan tekan tombol **Proses Preprocessing**. Sistem akan mengumpulkan dan merata-ratakan nilai.
5. Masuk ke menu **Prediksi** dan lakukan Prediksi Naive Bayes.
6. Lihat hasil perhitungan algoritma secara lengkap di menu **Laporan**.
7. Sesuaikan identitas sekolah (Logo, Lembaga, dsb) melalui menu Profil (Pojok kanan atas -> **Settings**).