<?php
require_once '../includes/init.php';
require_once '../classes/UserManager.php';

// Pastikan session hanya dimulai jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Anda tidak memiliki izin untuk mengakses halaman ini.");
}

try {
    // Inisialisasi UserManager
    $userManager = new UserManager();

    // Tambah/Edit User (POST Request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $username = $_POST['username'];
        $role = $_POST['role'];
        $faculty_id = $_POST['faculty_id'];
        $password = $_POST['password'] ?? null;

        // Gunakan setter untuk mengatur data pengguna
        if ($id) {
            $userManager->setId($id);  // Set ID untuk update
        }
        $userManager->setUsername($username);
        $userManager->setRole($role);
        $userManager->setFacultyId($faculty_id);

        // Set password jika ada
        if (!empty($password)) {
            $userManager->setPassword($password);
        }

        // Simpan atau perbarui pengguna
        $userManager->saveUser();
        header("Location: manage_users.php");
        exit();
    }

    // Hapus User (GET Request)
    if (isset($_GET['delete'])) {
        $userManager->setId($_GET['delete']);
        $userManager->deleteUser();
        header("Location: manage_users.php");
        exit();
    }

    // Ambil semua pengguna dan fakultas
    $users = $userManager->getAllUsers();
    $faculties = $userManager->getAllFaculties();

} catch (Exception $e) {
    // Tangani error
    echo "Terjadi kesalahan: " . htmlspecialchars($e->getMessage());
    exit();
}

require_once '../includes/header.php';
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/palette.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-light-green text-gray-800">
<!-- Main Content -->
<main class="container mx-auto px-4 py-6">

    <!-- Form Tambah/Edit User -->
    <section class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-4">Add/Edit User</h2>
        <form method="POST" action="manage_users.php" class="space-y-4">
            <input type="hidden" name="id" id="id">
            <div>
                <label for="username" class="block font-medium">Username</label>
                <input type="text" name="username" id="username"required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
            </div>
            <div>
                <label for="password" class="block font-medium">Password (Optional)</label>
                <input type="password" name="password" id="password" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            <div>
                <label for="role" class="block font-medium">Role</label>
                <select name="role" id="role" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                    <option value="admin">Admin</option>
                    <option value="sub-admin">Sub-Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div>
                <label for="faculty_id" class="block font-medium">Fakulty</label>
                <select id="faculty_id" name="faculty_id" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                    <option value="" disabled>Select Faculty</option>
                    <?php
                    // Ambil daftar fakultas dari database
                    $facultyQuery = $conn->query("SELECT id, faculty_name FROM faculties");
                    while ($faculty = $facultyQuery->fetch()) {
                        echo "<option value='{$faculty['id']}'>{$faculty['faculty_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Simpan</button>
        </form>
    </section>

    <!-- Tabel Users -->
    <section class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">User List</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full border border-gray-300">
                <thead class="bg-beige-green">
                    <tr>
                        <th class="border border-gray-400 px-4 py-2">ID</th>
                        <th class="border border-gray-400 px-4 py-2">Username</th>
                        <th class="border border-gray-400 px-4 py-2">Role</th>
                        <th class="border border-gray-400 px-4 py-2">Fakulty</th>
                        <th class="border border-gray-400 px-4 py-2">Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="border border-gray-400 px-4 py-2"><?= htmlspecialchars($user['id']); ?></td>
                            <td class="border border-gray-400 px-4 py-2"><?= htmlspecialchars($user['username']); ?></td>
                            <td class="border border-gray-400 px-4 py-2"><?= htmlspecialchars($user['role']); ?></td>
                            <td class="border border-gray-400 px-4 py-2"><?= htmlspecialchars($user['faculty_name'] ?? 'N/A'); ?></td>
                            <td class="border border-gray-400 px-4 py-2">
                                <a href="?edit=<?= $user['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                                <a href="?delete=<?= $user['id']; ?>" onclick="return confirm('Hapus user ini?');" class="text-red-500 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>


<!-- JavaScript -->

<script>
    document.querySelectorAll('[href^="?edit="]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            // Ambil baris data yang ingin di-edit
            const row = link.closest('tr');

            // Isi form dengan data dari baris tabel
            document.getElementById('id').value = row.cells[0].innerText.trim();
            document.getElementById('username').value = row.cells[1].innerText.trim();
            document.getElementById('role').value = row.cells[2].innerText.trim();
            
            const facultyValue = row.cells[3].innerText.trim(); // Ambil nama fakultas dari tabel

            // Set dropdown Fakultas
            const facultyDropdown = document.getElementById('faculty_id');
            Array.from(facultyDropdown.options).forEach(option => {
                if (option.text === facultyValue) {
                    option.selected = true; // Pilih opsi yang cocok dengan fakultas
                }
            });

            // Tampilkan notifikasi berwarna merah
            let notifDiv = document.getElementById('editNotif');
            if (!notifDiv) {
                notifDiv = document.createElement('div');
                notifDiv.id = 'editNotif';
                notifDiv.style.color = 'red';
                notifDiv.style.marginTop = '10px';
                notifDiv.style.fontWeight = 'bold';
                notifDiv.innerText = 'Silahkan edit data di form.';
                const formTitle = document.querySelector('h2'); // Lokasi notifikasi di bawah judul
                formTitle.insertAdjacentElement('afterend', notifDiv);
            } else {
                notifDiv.innerText = 'Silahkan edit data di form.';
            }

            // Scroll ke bagian atas form
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Smooth scroll effect
            });
        });
    });
</script>
</body>
</html>
