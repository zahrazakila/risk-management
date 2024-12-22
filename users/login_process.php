<?php
require_once '../includes/db.php'; // Koneksi ke database

session_start(); // Pastikan sesi dimulai

// Buat koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

try {
    // Query untuk cek username
    $query = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $query->execute(['username' => $username]);
    $user = $query->fetch();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['faculty_id'] = $user['faculty_id']; // Ambil dari database

        // Update last_login di database
        $updateLoginTime = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = :user_id");
        $updateLoginTime->execute(['user_id' => $user['id']]);

        // Update total logins
        $updateTotalLogins = $conn->prepare("UPDATE users SET total_logins = total_logins + 1 WHERE id = :user_id");
        $updateTotalLogins->execute(['user_id' => $user['id']]);

        // Redirect ke dashboard
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        // Login gagal
        $_SESSION['error_message'] = "Username atau password salah!";
        header("Location: ../index.php");
        exit();
    }
} catch (Exception $e) {
    // Tangani error
    $_SESSION['error_message'] = "Terjadi kesalahan pada sistem!";
    header("Location: ../index.php");
    exit();
}
?>
