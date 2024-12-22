<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../classes/Risk.php';

$username = $_SESSION['username'];

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}
$risk = new Risk($db);

$id = $_GET['id'];
$currentRisk = $risk->getRiskById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'objective' => $_POST['objective'],
        'process_business' => $_POST['process_business'],
        'risk_category' => $_POST['risk_category'],
        'risk_code' => $currentRisk['risk_code'],
        'risk_event' => $_POST['risk_event'],
        'risk_cause' => $_POST['risk_cause'],
        'risk_source' => $_POST['risk_source'],
        'qualitative' => $_POST['qualitative'],
        'quantitative' => str_replace(['Rp', '.', ' '], '', $_POST['quantitative']),
        'risk_owner' => $_POST['risk_owner'],
        'department' => $_POST['department'],
    ];

    $risk->updateRisk($id, $data);
    header("Location: ../../pages/identification.php");
    exit;
}


?>

<!-- Bagian HTML Edit Risiko -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Risiko</title>
    <link rel="stylesheet" href="../../assets/css/palette.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    </head>
<body>
<body class="bg-light-green">

    <header class="bg-beige shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="../../assets/img/logo.png" alt="Logo" class="w-10 h-10">
                <span class="text-xl font-bold text-gray-700">Risk Management</span>
            </div>

            <!-- Hamburger Button for Mobile -->
            <button id="hamburgerButton" class="md:hidden focus:outline-none">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Navigation Links -->
            <nav id="navMenu" class="hidden flex-col md:flex md:flex-row md:space-x-6 absolute md:static top-16 left-0 w-full md:w-auto bg-white shadow md:shadow-none z-10">
                <!-- Dashboard -->
                <a href="../../pages/dashboard.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Dashboard</a>

                <!-- Identification, Mitigation, Monitoring, Risk Map -->
                <a href="../../pages/identification.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Identification</a>
                <a href="../../pages/mitigation.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Mitigation</a>
                <a href="../../pages/monitoring.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Monitoring</a>
                <a href="../../pages/riskmap.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Risk Map</a>

                <!-- Menu Khusus Admin -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="manage_users.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Manage Users</a>
                    <a href="reports.php" class="block text-gray-700 px-4 py-2 md:py-0 rounded hover:bg-green-700 hover:text-white transition-all duration-300">Reports</a>
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
                        <span class="text-gray-500 text-sm leading-tight"><?php echo ucfirst($_SESSION['role']); ?></span>
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


    <main class="container mx-auto px-4 py-6 flex-grow">
        <section class="bg-white rounded shadow p-6 mb-8">
        <form action="edit_risk.php?id=<?php echo $id; ?>" method="POST" class="space-y-4">
                <input type="hidden" id="risk_code" name="risk_code" value="<?php echo $currentRisk['risk_code']; ?>" required>
            <div>
                <label for="objective" class="block text-gray-700 font-medium">Objective/Tujuan</label>
                <input type="text" id="objective" name="objective" value="<?php echo $currentRisk['objective']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="process_business" class="block text-gray-700 font-medium">Proses Bisnis</label>
                <select id="process_business" name="process_business"class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                    <option value="akademik" <?php if ($currentRisk['process_business'] === 'akademik') echo 'selected'; ?>>Akademik</option>
                    <option value="finansial" <?php if ($currentRisk['process_business'] === 'finansial') echo 'selected'; ?>>Finansial</option>
                    <option value="kepegawaian" <?php if ($currentRisk['process_business'] === 'kepegawaian') echo 'selected'; ?>>Kepegawaian</option>
                </select>
            </div>

            <div>
            <label for="risk_category"  class="block text-gray-700 font-medium">Risk Category</label>
            <select id="risk_category" name="risk_category"class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                <option value="strategic" <?php if ($currentRisk['risk_category'] === 'strategic') echo 'selected'; ?>>Strategic</option>
                <option value="financial" <?php if ($currentRisk['risk_category'] === 'financial') echo 'selected'; ?>>Financial</option>
                <option value="operational" <?php if ($currentRisk['risk_category'] === 'operational') echo 'selected'; ?>>Operational</option>
            </select>
            </div>

            <div>
                <label for="risk_event"class="block text-gray-700 font-medium">Risk Event</label>
                <input type="text" id="risk_event" name="risk_event" value="<?php echo $currentRisk['risk_event']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="risk_cause" class="block text-gray-700 font-medium">Risk Cause</label>
                <input type="text" id="risk_cause" name="risk_cause" value="<?php echo $currentRisk['risk_cause']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="risk_source" class="block text-gray-700 font-medium">Risk Source</label>
                <select id="risk_source" name="risk_source" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                    <option value="internal" <?php if ($currentRisk['risk_source'] === 'internal') echo 'selected'; ?>>Internal</option>
                    <option value="external" <?php if ($currentRisk['risk_source'] === 'external') echo 'selected'; ?>>External</option>
                </select>
            </div>

            <div>
                <label for="qualitative" class="block text-gray-700 font-medium">Severity (Qualitative)</label>
                <input type="text" id="qualitative" name="qualitative" value="<?php echo $currentRisk['qualitative']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="quantitative" class="block text-gray-700 font-medium">Severity (Quantitative)</label>
                <input type="text" id="quantitative" name="quantitative" value="<?php echo $currentRisk['quantitative']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="risk_owner" class="block text-gray-700 font-medium">Risk Owner</label>
                <input type="text" id="risk_owner" name="risk_owner" value="<?php echo $currentRisk['risk_owner']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="department" class="block text-gray-700 font-medium">Nama Dept./Unit Terkait</label>
                <input type="text" id="department" name="department" value="<?php echo $currentRisk['department']; ?>" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
            </div>

            <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Update</button>
        </form>
        </section>
    </main>
    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>
</body>
</html>
