<?php
session_start();
include '../includes/db.php';

for ($i = 1; $i <= 8; $i++) {
    $username = "subadmin" . $i;
    $password = password_hash("password" . $i, PASSWORD_DEFAULT);
    $role = 'user';

    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'username' => $username,
        'password' => $password,
        'role' => $role
    ]);
}

echo "8 Sub-Admin berhasil ditambahkan!";
?>
