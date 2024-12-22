<?php
require_once '../includes/init.php';
require_once '../classes/UserManager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit("Anda harus login terlebih dahulu.");
}

try {
    // Inisialisasi UserManager
    $userManager = new UserManager();

    // Ambil data pengguna dari session
    $user_id = $_SESSION['user_id'];
    
    // Gunakan setter untuk mengatur ID pengguna
    $userManager->setId($user_id);
    
    // Ambil profil pengguna dengan method getUserProfile()
    $profile = $userManager->getUserProfile();

    // Gunakan setter untuk mengatur data dari hasil query
    $username = $profile['username'] ?? 'N/A';
    $nama_lengkap = $profile['nama_lengkap'] ?? 'Tidak Diketahui';  // Ganti dari $nama_lengkap
    $last_login = $profile['last_login'] ?? 'Belum Pernah Login';
    $last_login = $profile['last_login'] ?? 'Belum Pernah Login';
    $total_logins = $profile['total_logins'] ?? 0;

} catch (Exception $e) {
    // Tangani kesalahan koneksi atau query
    $error_message = $e->getMessage();
    echo "Terjadi kesalahan: " . htmlspecialchars($error_message);
    exit();
}

$username = $_SESSION['username'];
$role = ucfirst($_SESSION['role'] ?? 'unknown');

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/palette.css">
    <style>
        
    #userMenu {
        position: absolute; /* Agar dropdown muncul relatif terhadap parent */
        z-index: 50; /* Pastikan elemen berada di atas elemen lain */
        background-color: white; /* Warna background agar terlihat */
        border: 1px solid #ddd; /* Tambahkan border untuk visibilitas */
        padding: 10px; /* Tambahkan padding agar terlihat rapi */
        border-radius: 5px; /* Membuat sudut melengkung */
        display: none; /* Secara default, dropdown disembunyikan */
    }

    #userMenu.scale-100 {
        display: block; /* Ditampilkan saat class 'scale-100' ditambahkan */
    }


</style>
</head>
<body class="bg-light-green text-gray-800 flex flex-col min-h-screen">
    <!-- Navbar -->
    <header class="bg-beige shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center relative">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="../assets/img/logo.png" alt="Logo" class="w-10 h-10">
            <span class="text-xl font-bold text-gray-700">Risk Register</span>
        </div>

        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="md:hidden focus:outline-none transition-transform duration-300 transform hover:scale-110">
            <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Navigation Links -->
        <nav id="navMenu" class="hidden md:flex md:space-x-6 absolute md:static top-16 left-0 w-full md:w-auto bg-white md:bg-transparent shadow md:shadow-none z-20 transition-all duration-300 ease-in-out">
            <a href="../pages/dashboard.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Dashboard</a>
            <a href="../pages/identification.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Identification</a>
            <a href="../pages/mitigation.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Mitigation</a>
            <a href="../pagesmonitoring.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Monitoring</a>
            <a href="../pages/riskmap.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Risk Map</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="../pages/manage_users.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Manage Users</a>
                <a href="../pages/reports.php" class="block px-4 py-2 hover:bg-green-500 hover:text-white rounded transition-all duration-300">Reports</a>
            <?php endif; ?>
        </nav>

        <!-- User Profile -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center space-x-4 focus:outline-none transition-all duration-300 hover:scale-105">
                <!-- Gambar Profile -->
                <div class="bg-green-500 w-8 h-8 rounded-full flex justify-center items-center text-white font-bold text-sm">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <!-- Tulisan Profil dan Role -->
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-gray-700 font-semibold text-base leading-tight">Welcome, <?php echo htmlspecialchars($username); ?></span>
                    <span class="text-gray-500 text-sm leading-tight"><?php echo ucfirst($_SESSION['role']); ?></span>
                </div>
                <!-- Icon Dropdown -->
                <svg class="w-4 h-4 text-gray-500 ml-2 transition-transform duration-300 transform group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="userMenu" class="hidden absolute right-0 mt-2 bg-white border rounded-lg shadow-lg w-48 z-100 transform scale-0 origin-top-right transition-transform duration-300">
                <a href="../users/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Profile</a>
                <a href="../users/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Logout</a>
            </div>
        </div>
    </div>
</header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 flex-grow">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Profil Pengguna</h2>

            <!-- Informasi Profil -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Username:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($username); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Fakultas:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($nama_lengkap); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Login Terakhir:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($last_login); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Total Login:</span>
                    <span class="text-gray-900"><?php echo htmlspecialchars($total_logins); ?></span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (e) => {
            console.log('User menu button clicked'); // Debug
            console.log('Toggling user menu'); // Debug
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            userMenu.classList.toggle('scale-100');
        });

        window.addEventListener('click', () => {
            if (!userMenu.classList.contains('hidden')) {
                console.log('Hiding user menu'); // Debug
                userMenu.classList.add('hidden');
                userMenu.classList.remove('scale-100');
            }
        });
    }
});
    </script>
    <script src ="../assets\js\script.js"></script>
</body>
</html>
