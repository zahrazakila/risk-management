
<?php 
$username = $_SESSION['username'];
$role = ucfirst($_SESSION['role'] ?? 'unknown');

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}
?>
<header class="bg-beige shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center relative">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="../assets/img/logo.png" alt="Logo" class="w-10 h-10">
            <span class="text-xl font-bold text-gray-700">Risk Management</span>
        </div>

        <!-- Hamburger Button -->
        <button id="hamburgerButton" class="md:hidden focus:outline-none transition-transform duration-300 transform hover:scale-110">
            <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Navigation Links -->
        <nav id="navMenu" class="hidden md:flex md:space-x-6 absolute md:static top-16 left-0 w-full md:w-auto bg-white md:bg-transparent shadow md:shadow-none z-20 transition-all duration-300 ease-in-out">
            <a href="dashboard.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Dashboard</a>
            <a href="identification.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Identification</a>
            <a href="mitigation.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Mitigation</a>
            <a href="monitoring.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Monitoring</a>
            <a href="riskmap.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Risk Map</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="manage_users.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Manage Users</a>
                <a href="reports.php" class="block px-4 py-2 hover:bg-green-700 hover:text-white rounded transition-all duration-300">Reports</a>
            <?php endif; ?>
        </nav>

        <!-- User Profile -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center space-x-4 focus:outline-none transition-all duration-300 hover:scale-105">
                <!-- Gambar Profile -->
                <div class="bg-green-700 w-8 h-8 rounded-full flex justify-center items-center text-white font-bold text-sm">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <!-- Tulisan Profil dan Role -->
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-gray-700 font-semibold text-base leading-tight">Welcome, <?php echo htmlspecialchars($username); ?></span>
                    <span class="text-gray-500 text-sm leading-tight">
                        <?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?>
                    </span>
                </div>
                <!-- Icon Dropdown -->
                <svg class="w-4 h-4 text-gray-500 ml-2 transition-transform duration-300 transform group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="userMenu" class="hidden absolute right-0 mt-2 bg-white border rounded-lg shadow-lg w-48 z-50 transform scale-0 origin-top-right transition-transform duration-300">
                <a href="../users/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Profile</a>
                <a href="../users/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Logout</a>
            </div>
        </div>
    </div>
</header>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Hamburger Menu Logic
    const hamburgerButton = document.getElementById('hamburgerButton');
    const navMenu = document.getElementById('navMenu');

    if (hamburgerButton && navMenu) {
        hamburgerButton.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
        });
    }

    // User Dropdown Logic
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            userMenu.classList.toggle('scale-100'); // Add transition effect
        });

        window.addEventListener('click', () => {
            if (!userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
                userMenu.classList.remove('scale-100');
            }
        });

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});

</script>



