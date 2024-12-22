<?php
session_start();
include '../includes/db.php'; // Koneksi database

// Cek apakah user yang mengakses file ini adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak! Anda bukan admin.");
}

try {
    // Loop untuk menambahkan 8 user baru
    for ($i = 1; $i <= 8; $i++) {
        $username = "user_subadmin" . $i;
        $password = password_hash("password_sub" . $i, PASSWORD_DEFAULT); // Hash password
        $role = "user";

        // Query untuk menambahkan user baru
        $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
            'password' => $password,
            'role' => $role
        ]);
    }

    echo "8 User baru berhasil ditambahkan ke database!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
