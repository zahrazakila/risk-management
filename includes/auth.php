<?php
require_once 'db.php';

class Auth {
    private $conn;

    public function __construct() {
        $db = new Database(); // Panggil koneksi database
        $this->conn = $db->getConnection();
        session_start();
    }
    
    // Fungsi untuk mengecek apakah user sudah login
    public function redirectIfNotLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php"); // Redirect ke halaman login
            exit();
        }
    }

    // Fungsi untuk mengecek role pengguna
    public function checkRole() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login_process.php");
            exit;
        }

        $query = $this->conn->prepare("SELECT role FROM users WHERE id = :id");
        $query->execute(['id' => $_SESSION['user_id']]);
        $user = $query->fetch();

        if (!$user) {
            header("Location: login_process.php");
            exit;
        }

        // Simpan role dalam sesi
        $_SESSION['role'] = $user['role'];
    }
}
?>
