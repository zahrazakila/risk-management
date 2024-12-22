<?php
require_once 'db.php';
require_once 'auth.php';

// Buat koneksi database
$db = new Database();
$conn = $db->getConnection();

// Inisialisasi autentikasi
$auth = new Auth($conn);
$auth->redirectIfNotLoggedIn(); // Pastikan user sudah login

// Variabel global
$role = $_SESSION['role'];
$faculty_id = $_SESSION['faculty_id'] ?? null;


