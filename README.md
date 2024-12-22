Risk Management Web Application
Aplikasi Manajemen Risiko berbasis web yang dirancang untuk mengelola, memantau, dan melaporkan risiko organisasi atau institusi. Aplikasi ini menggunakan PHP OOP (Object-Oriented Programming) dan MySQL untuk database.

Daftar Isi
Fitur
Teknologi yang Digunakan
Struktur Proyek
Instalasi dan Konfigurasi
Penggunaan
Setup Database
Kontributor
Fitur
Manajemen Pengguna

Tambah, Edit, dan Hapus pengguna.
Hak akses berbasis role (Admin, Editor, Viewer).
Identifikasi Risiko

Input dan kelola data risiko berdasarkan fakultas/departemen.
Asosiasi risiko dengan mitigasi dan kontrol yang ada.
Laporan Mitigasi

Lihat daftar mitigasi yang sedang berjalan atau selesai.
Update status mitigasi langsung dari dashboard.
Profil Pengguna

Halaman profil pengguna yang menampilkan informasi personal dan fakultas.
Teknologi yang Digunakan
Frontend: HTML, CSS, JavaScript
Backend: PHP (OOP)
Database: MySQL
Server Lokal: Laragon/XAMPP
Struktur Proyek
bash
Copy code
/risk_management_project
│
├── /classes
│   ├── Database.php         # Koneksi database
│   ├── UserManager.php      # CRUD untuk manajemen pengguna
│   ├── ReportManager.php    # Laporan mitigasi dan risiko
│
├── /includes
│   ├── init.php             # Inisialisasi session dan autoload
│   ├── header.php           # Header HTML
│
├── /pages
│   ├── manage_users.php     # Manajemen pengguna
│   ├── profile.php          # Halaman profil pengguna
│   ├── edit_profile.php     # Edit profil pengguna
│
├── /public
│   ├── style.css            # File CSS
│
└── /sql
    ├── risk_management.sql  # File SQL untuk setup database
│
└── index.php                # Dashboard utama
└── README.md                # File dokumentasi
Instalasi dan Konfigurasi
1. Clone Proyek
bash
Copy code
git clone https://github.com/username/risk-management
cd risk-management
2. Setup Database (Import SQL File)
Buka phpMyAdmin (atau command line) dan buat database baru:

sql
Copy code
CREATE DATABASE risk_management;
Import File SQL dari folder /sql:

Masuk ke phpMyAdmin.
Pilih database risk_management yang telah dibuat.
Klik Import.
Upload file risk_management.sql dari folder /sql.
Klik Go.
3. Konfigurasi Koneksi Database
Edit file Database.php di folder /classes:

php
Copy code
private $host = 'localhost';
private $dbname = 'risk_management';
private $username = 'root';
private $password = '';
Jika menggunakan Laragon atau XAMPP default, cukup biarkan seperti ini.

Penggunaan
1. Jalankan Proyek di Laragon atau XAMPP
Letakkan folder proyek di dalam direktori www (Laragon) atau htdocs (XAMPP).
Akses proyek di browser:
bash
Copy code
http://localhost/risk_management_project/
2. Akses Halaman Manajemen Pengguna
Login sebagai admin dan akses halaman:

bash
Copy code
http://localhost/risk_management_project/pages/manage_users.php
Tambah pengguna baru dengan role (Admin, Editor, Viewer).

Edit pengguna melalui link "Edit".

Hapus pengguna melalui tombol "Delete".

3. Lihat Profil Pengguna
Akses halaman profil pengguna:
bash
Copy code
http://localhost/risk_management_project/pages/profile.php
Setup Database
Buka phpMyAdmin atau command line.

Buat database baru:

sql
Copy code
CREATE DATABASE risk_management;
Import file SQL ke dalam database:

Masuk ke tab Import di phpMyAdmin.
Upload file risk_management.sql dari folder /sql.
Klik Go untuk menjalankan proses import.
Pastikan tabel users dan faculties sudah muncul di database setelah proses import selesai.

Kontributor
Nama Anda (Developer Backend)
Tim Anda (Frontend/Fullstack)
Organisasi/Institusi
Jika ada bug atau saran pengembangan lebih lanjut, jangan ragu untuk menghubungi kami di email@example.com.

Lisensi
Proyek ini berlisensi di bawah MIT License. Anda bebas menggunakan dan memodifikasi untuk keperluan pribadi dan komersial.
