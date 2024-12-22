# Risk Management Web Application

Aplikasi Manajemen Risiko berbasis web yang dirancang untuk mengelola, memantau, dan melaporkan risiko organisasi atau institusi. Aplikasi ini menggunakan PHP OOP (Object-Oriented Programming) dan MySQL untuk database.

---

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Instalasi dan Konfigurasi](#instalasi-dan-konfigurasi)
- [Penggunaan](#penggunaan)
- [Setup Database](#setup-database)
- [Kontributor](#kontributor)

---

## Fitur

1. **Manajemen Pengguna**

   - Tambah, Edit, dan Hapus pengguna.
   - Hak akses berbasis role (Admin, Editor, Viewer).

2. **Identifikasi Risiko**

   - Input dan kelola data risiko berdasarkan fakultas/departemen.
   - Asosiasi risiko dengan mitigasi dan kontrol yang ada.

3. **Laporan Mitigasi**

   - Lihat daftar mitigasi yang sedang berjalan atau selesai.
   - Update status mitigasi langsung dari dashboard.

4. **Profil Pengguna**

   - Halaman profil pengguna yang menampilkan informasi personal dan fakultas.

5. **Dashboard Interaktif**

   - Visualisasi data risiko dalam bentuk grafik dan tabel.
   - Filter dan cari data berdasarkan kategori dan status.
     
6. **Dan Masih Banyak Fitur Lainnya**

---

## Teknologi yang Digunakan

- **Frontend**: HTML, CSS, JavaScript, Tailwind CSS
- **Backend**: PHP (OOP)
- **Database**: MySQL
- **Server Lokal**: Laragon/XAMPP

---

## Instalasi dan Konfigurasi

1. **Clone repositori**

   ```bash
   git clone https://github.com/zahrazakila/risk-management.git
   ```

2. **Masuk ke direktori proyek**

   ```bash
   cd risk-management
   ```

3. **Konfigurasi Database**

   - Buat database baru melalui phpMyAdmin atau terminal MySQL.
   - Import file `risk_management_db.sql` ke dalam database.

4. **Konfigurasi file koneksi**  
   Edit file `includes/db.php` dan sesuaikan kredensial sesuai database Anda.

5. **Install dependensi tambahan (Tailwind CSS)**
   ```bash
   npm install
   npm run build
   ```

6. **Jalankan di server lokal**

   - Gunakan Laragon atau XAMPP, letakkan proyek di folder `www` atau `htdocs`.

---

## Penggunaan

1. **Akses aplikasi**
   - Buka browser dan akses `http://localhost/risk-management`.
2. **Login sebagai Admin**
   - Default akun Admin:
     - Username: admin
     - Password: admin123
3. **Mulai tambahkan data risiko dan mitigasi**
   - Gunakan menu navigasi untuk menambah pengguna, risiko, dan laporan.
   - Gunakan dashboard interaktif untuk memantau risiko secara real-time.

---

## Setup Database

1. **Buat database**
   ```sql
   CREATE DATABASE risk_management;
   ```
2. **Import file SQL**
   ```sql
   USE risk_management;
   SOURCE risk_management_db.sql;
   ```

---

## Kontributor

- **Zahra Zakila A. R.**  
  - Fullstack Developer  
  - 23106050019@student@uin-suka.ac.id

- **Nadine Riskia W. P.**  
  - Testing & Documentation  
  - 23106050022@student@uin-suka.ac.id

- **Ara Rosalia S.**  
  - Testing & Front-end  
  - 23106050021@student@uin-suka.ac.id

- **Nayy**  
  - UI/UX  
  - 23106050065@student@uin-suka.ac.id

- **Zaza**  
  - UI/UX & Front-end   
  - 23106050030@student@uin-suka.ac.id



