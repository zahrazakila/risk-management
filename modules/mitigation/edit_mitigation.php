<?php
require_once '../../includes/init.php';
require_once '../../classes/Mitigation.php';

$username = $_SESSION['username'];

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}


$mitigation = new Mitigation($conn);

// Tangkap ID dari GET atau POST
$id = $_GET['edit'] ?? $_POST['edit'] ?? null;

// Validasi ID
if (!$id || !is_numeric($id)) {
    die("ID tidak valid atau tidak ditemukan.");
}

// Ambil data mitigasi untuk diedit
$editData = $mitigation->getMitigationById($id);
if (!$editData) {
    die("Data tidak ditemukan di database untuk ID: " . htmlspecialchars($id));
}

// Update mitigasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'risk_code' => $_POST['risk_code'],
        'inherent_likehood' => $_POST['inherent_likehood'],
        'inherent_impact' => $_POST['inherent_impact'],
        'inherent_risk_level' => $_POST['inherent_likehood'] * $_POST['inherent_impact'],
        'existing_control' => $_POST['existing_control'],
        'control_quality' => $_POST['control_quality'],
        'execution_status' => $_POST['execution_status'],
        'residual_likehood' => $_POST['residual_likehood'],
        'residual_impact' => $_POST['residual_impact'],
        'residual_risk_level' => $_POST['residual_likehood'] * $_POST['residual_impact'],
        'risk_treatment' => $_POST['risk_treatment'],
        'mitigation_plan' => $_POST['mitigation_plan'],
        'after_mitigation_likehood' => $_POST['after_mitigation_likehood'],
        'after_mitigation_impact' => $_POST['after_mitigation_impact'],
        'after_mitigation_risk_level' => $_POST['after_mitigation_likehood'] * $_POST['after_mitigation_impact']
    ];

    // Eksekusi Update
    if ($mitigation->updateMitigation($id, $data)) {
        header("Location: ../../pages/mitigation.php");
        exit;
    } else {
        $error = "Gagal memperbarui data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitigasi Risiko</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/palette.css">
</head>
<body class="bg-light-green">
    <!-- Navbar -->
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
            <form action="edit_mitigation.php" method="POST" target="_self" class="space-y-4">
                <input type="hidden" name="edit" value="<?php echo htmlspecialchars($id); ?>">

                <!-- Kode Risiko -->
              <!-- Input Hidden untuk Risk Code -->
                <input type="hidden" name="risk_code" value="<?php echo htmlspecialchars($editData['risk_code'] ?? ''); ?>">


                <!-- Inherent Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">Inherent Risk</h3>
                <div class="space-y-4">
                    <label for="inherent_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                    <select id="inherent_likehood" name="inherent_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($editData['inherent_likehood']) && $editData['inherent_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="inherent_impact" class="block text-gray-700 font-medium">Impact</label>
                    <select id="inherent_impact" name="inherent_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($editData['inherent_impact']) && $editData['inherent_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Existing Control -->
                <h3 class="text-lg font-bold mt-6 mb-2">Existing Control</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="existing_control" class="block text-gray-700 font-medium"v>Yes/No</label>
                            <select id="existing_control" name="existing_control" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                                <option value="Yes" <?php echo (isset($editData['existing_control']) && $editData['existing_control'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                <option value="No" <?php echo (isset($editData['existing_control']) && $editData['existing_control'] == 'No') ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="control_quality" class="block text-gray-700 font-medium">Control Quality</label>
                            <select id="control_quality" name="control_quality" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                                <option value="Sufficient" <?php echo (isset($editData['control_quality']) && $editData['control_quality'] == 'Sufficient') ? 'selected' : ''; ?>>Sufficient</option>
                                <option value="Not Sufficient" <?php echo (isset($editData['control_quality']) && $editData['control_quality'] == 'Not Sufficient') ? 'selected' : ''; ?>>Not Sufficient</option>
                            </select>
                        </div>
                        <div>
                            <label for="execution_status" class="block text-gray-700 font-medium">Execution Status</label>
                            <select id="execution_status" name="execution_status" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                                <option value="On Progress" <?php echo (isset($editData['execution_status']) && $editData['execution_status'] == 'On Progress') ? 'selected' : ''; ?>>On Progress</option>
                                <option value="Pending" <?php echo (isset($editData['execution_status']) && $editData['execution_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Completed" <?php echo (isset($editData['execution_status']) && $editData['execution_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>

                <!-- Residual Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">Residual Risk</h3>
                <div class="space-y-4">
                    <div>
                        <label for="residual_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="residual_likehood" name="residual_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['residual_likehood']) && $editData['residual_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div>
                        <label for="residual_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="residual_impact" name="residual_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['residual_impact']) && $editData['residual_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- Risk Treatment -->
                <h3 class="text-lg font-bold mt-6 mb-2">Risk Treatment</h3>
                <div class="space-y-4">
                    <div>
                        <label for="risk_treatment" class="block text-gray-700 font-medium">Risk Treatment</label>
                        <select id="risk_treatment" name="risk_treatment" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                            <option value="Accept" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Accept') ? 'selected' : ''; ?>>Accept</option>
                            <option value="Share" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Share') ? 'selected' : ''; ?>>Share</option>
                            <option value="Reduce" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Reduce') ? 'selected' : ''; ?>>Reduce</option>
                            <option value="Avoid" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Avoid') ? 'selected' : ''; ?>>Avoid</option>
                        </select>
                    </div>

                    <div>
                        <label for="mitigation_plan" class="block text-gray-700 font-medium">Mitigation Plan</label>
                        <textarea id="mitigation_plan" name="mitigation_plan" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500"><?php echo $editData['mitigation_plan'] ?? ''; ?></textarea>
                    </div>
                </div>

                <!-- After Mitigation Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">After Mitigation Risk</h3>
                <div class="space-y-4">
                    <div>
                        <label for="after_mitigation_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="after_mitigation_likehood" name="after_mitigation_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['after_mitigation_likehood']) && $editData['after_mitigation_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                <div>
                    <div class="space-y-4">
                    <label for="after_mitigation_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="after_mitigation_impact" name="after_mitigation_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['after_mitigation_impact']) && $editData['after_mitigation_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                </div>
                <!-- Tombol Simpan -->
                <button type="submit"  class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">Update</button>
            </form>
        </section>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>

    <script src ="../../assets\js\script.js"></script>
</body>
</html>

