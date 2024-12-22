<?php
require_once '../includes/init.php';
require_once '../classes/Mitigation.php';

$username = $_SESSION['username'];

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

$mitigation = new Mitigation($conn);

// Cek role
$is_admin = $_SESSION['role'] === 'admin' || $_SESSION['role'] === 'sub-admin';
$faculty_id = $_SESSION['faculty_id'];

$role = $_SESSION['role'] ?? '';

// Ambil data untuk diedit
$id = $_GET['edit'] ?? null;
$editData = $id ? $mitigation->getMitigationById($id) : null;

// Ambil data mitigasi
$mitigations = $mitigation->getAllMitigations($faculty_id, $is_admin);

// Ambil semua risk_code
$risk_codes = $mitigation->getAllRiskCodes($role, $faculty_id);

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jika form checkbox dikirim
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'checkbox') {
        $risk_code = $_POST['risk_code'];
        $is_completed = isset($_POST['is_completed']) ? 1 : 0;

        // Update status is_completed
        $mitigation->updateMitigationStatus($risk_code, $is_completed);

        // Redirect kembali ke halaman
        header("Location: mitigation.php");
        exit;
    }

    // Jika form untuk tambah data mitigasi dikirim
    $data = [
        'risk_code' => $_POST['risk_code'],
        'inherent_likehood' => (int)$_POST['inherent_likehood'],
        'inherent_impact' => (int)$_POST['inherent_impact'],
        'inherent_risk_level' => (int)($_POST['inherent_likehood'] * $_POST['inherent_impact']),
        'existing_control' => $_POST['existing_control'],
        'control_quality' => $_POST['control_quality'],
        'execution_status' => $_POST['execution_status'],
        'residual_likehood' => (int)$_POST['residual_likehood'],
        'residual_impact' => (int)$_POST['residual_impact'],
        'residual_risk_level' => (int)($_POST['residual_likehood'] * $_POST['residual_impact']),
        'risk_treatment' => $_POST['risk_treatment'],
        'mitigation_plan' => $_POST['mitigation_plan'],
        'after_mitigation_likehood' => (int)$_POST['after_mitigation_likehood'],
        'after_mitigation_impact' => (int)$_POST['after_mitigation_impact'],
        'after_mitigation_risk_level' => (int)($_POST['after_mitigation_likehood'] * $_POST['after_mitigation_impact']),
        'faculty_id' => (int)$faculty_id,
        'is_completed' => (int)0
    ];
    
    // Tambahkan data mitigasi
    $mitigation->addMitigation($data);

    header("Location: mitigation.php");
    exit;
}
?>




<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitigasi Risiko</title>
    <link rel="stylesheet" href="../assets/css/palette.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
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
<body class="bg-light-green text-gray-800 flex flex-col min-h-screen overflow:visible">
<div class="flex-grow">
    <main class="container mx-auto px-4 py-6 flex-grow">
        <!-- Form Tambah Mitigasi (Hanya untuk Admin) -->
        <?php if ($is_admin): ?>
        <section class="bg-white rounded shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Add Mitigation</h2>
            <!-- Link Panduan -->
            <div class="flex justify-left mb-4">
                <button id="openGuide" class="text-blue-500 underline hover:text-blue-700">
                    Data Entry Guide
                </button>
            </div>

            <!-- Modul Pop-Up -->
            <div id="guideModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg overflow-y-auto max-h-screen">
                    <h2 class="text-xl font-bold mb-4">Guide to Add Risk Mitigation</h2>
                    
                    <!-- Penjelasan -->
                    <p class="mb-2"><strong>Inherent Risk</strong>: Risiko bawaan sebelum ada tindakan mitigasi.</p>
                    <p class="mb-2"><strong>Residual Risk</strong>: Risiko yang tersisa setelah kontrol atau mitigasi dilakukan.</p>
                    <p class="mb-2"><strong>After Mitigation Risk</strong>: Risiko akhir setelah semua rencana mitigasi dijalankan.</p>

                    <!-- Tabel Skala Likelihood dan Impact -->
                    <div class="my-4 overflow-auto">
                        <h3 class="font-bold">Tabel Skala Impact</h3>
                        <img src="../assets/img/table_impact.png" alt="Tabel Skala Impact" class="w-full my-2">
                    </div>
                    <div class="my-4 overflow-auto">
                        <h3 class="font-bold">Tabel Skala Likelihood</h3>
                        <img src="../assets/img/table_likelihood.png" alt="Tabel Skala Likelihood" class="w-full my-2" style="max-width: 500px; height: auto; display: block; margin: 0 auto;">
                    </div>

                    <!-- Tombol Tutup -->
                    <div class="flex justify-end">
                        <button id="closeGuide" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <form action="mitigation.php" method="POST" class="space-y-4">
                <input type="hidden" name="form_type" value="add_mitigation">
                <!-- Kode Risiko -->
                <div>
                    <label for="risk_code" class="block text-gray-700">Risk Code</label>
                    <select id="risk_code" name="risk_code" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                        <option value="" disabled selected>Select Risk Code</option>
                        <?php foreach ($risk_codes as $risk): ?>
                            <option value="<?php echo htmlspecialchars($risk['risk_code']); ?>"><?php echo htmlspecialchars($risk['risk_code']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <h3 class="text-lg font-bold mt-6 mb-2">Inherent Risk</h3>
                <div class="space-y-4">
                    <!-- Likelihood -->
                    <div>
                        <label for="inherent_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="inherent_likehood" name="inherent_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['inherent_likehood']) && $editData['inherent_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Impact -->
                    <div>
                        <label for="inherent_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="inherent_impact" name="inherent_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['inherent_impact']) && $editData['inherent_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- Existing Control -->
                <h3 class="text-lg font-bold mt-6 mb-2">Existing Control</h3>
                <div class="space-y-4">
                    <!-- Ada/Tidak Ada -->
                    <div>
                        <label for="existing_control" class="block text-gray-700 font-medium">Yes/No</label>
                        <select id="existing_control" name="existing_control" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <option value="Yes" <?php echo (isset($editData['existing_control']) && $editData['existing_control'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                            <option value="No" <?php echo (isset($editData['existing_control']) && $editData['existing_control'] == 'No') ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>

                    <!-- Kualitas Kontrol -->
                    <div>
                        <label for="control_quality" class="block text-gray-700 font-medium">Quality Control</label>
                        <select id="control_quality" name="control_quality" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <option value="Sufficient" <?php echo (isset($editData['control_quality']) && $editData['control_quality'] == 'Sufficient') ? 'selected' : ''; ?>>Sufficient</option>
                            <option value="Not Sufficient" <?php echo (isset($editData['control_quality']) && $editData['control_quality'] == 'Not Sufficient') ? 'selected' : ''; ?>>Not Sufficient</option>
                        </select>
                    </div>

                    <!-- Status Eksekusi -->
                    <div>
                        <label for="execution_status" class="block text-gray-700 font-medium">Execution Status</label>
                        <select id="execution_status" name="execution_status" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <option value="On Progress">On Progress</option>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Residual Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">Residual Risk</h3>
                <div class="space-y-4">
                    <!-- Likelihood -->
                    <div>
                        <label for="residual_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="residual_likehood" name="residual_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['residual_likehood']) && $editData['residual_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Impact -->
                    <div>
                        <label for="residual_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="residual_impact" name="residual_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
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
                        <label for="risk_treatment" class="block text-gray-700 font-medium">Perlakuan Risiko</label>
                        <select id="risk_treatment" name="risk_treatment" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <option value="Accept" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Acept') ? 'selected' : ''; ?>>Accept</option>
                            <option value="Share" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Share') ? 'selected' : ''; ?>>Share</option>
                            <option value="Reduce" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Reduce') ? 'selected' : ''; ?>>Reduce</option>
                            <option value="Avoid" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Avoid') ? 'selected' : ''; ?>>Avoid</option>
                        </select>
                    </div>
                </div>

                <!-- Mitigation Plan -->
                <h3 class="text-lg font-bold mt-6 mb-2">Mitigation Plan</h3>
                <div>
                    <label for="mitigation_plan" class="block text-gray-700 font-medium">Mitigation Plan</label>
                    <textarea id="mitigation_plan" name="mitigation_plan" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500"><?php echo $editData['mitigation_plan'] ?? ''; ?></textarea>
                </div>

                <!-- After Mitigation Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">After Mitigation Risk</h3>
                <div class="space-y-4">
                    <!-- Likelihood -->
                    <div>
                        <label for="after_mitigation_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="after_mitigation_likehood" name="after_mitigation_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['after_mitigation_likehood']) && $editData['after_mitigation_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Impact -->
                    <div>
                        <label for="after_mitigation_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="after_mitigation_impact" name="after_mitigation_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['after_mitigation_impact']) && $editData['after_mitigation_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">Save</button>
            </form>
        </section>
        <?php endif; ?>

        <!-- tabel data -->
            
        <section class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4">Mitigation List</h2>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead class="bg-beige-green">
                        <tr>
                            <th class="border border-gray-400 px-4 py-2">Risk Code</th>
                            <th class="border border-gray-400 px-4 py-2">Inherent Likehood</th>
                            <th class="border border-gray-400 px-4 py-2">Inherent Impact</th>
                            <th class="border border-gray-400 px-4 py-2">Inherent Risk Level</th>
                            <th class="border border-gray-400 px-4 py-2">Existing Control</th>
                            <th class="border border-gray-400 px-4 py-2">Control Quality</th>
                            <th class="border border-gray-400 px-4 py-2">Execution Status</th>
                            <th class="border border-gray-400 px-4 py-2">Residual Likehood</th>
                            <th class="border border-gray-400 px-4 py-2">Residual Impact</th>
                            <th class="border border-gray-400 px-4 py-2">Residual Risk Level</th>
                            <th class="border border-gray-400 px-4 py-2">Risk Treatment</th>
                            <th class="border border-gray-400 px-4 py-2">Mitigation Plan</th>
                            <th class="border border-gray-400 px-4 py-2">After Mitigation Likehood</th>
                            <th class="border border-gray-400 px-4 py-2">After Mitigation Impact</th>
                            <th class="border border-gray-400 px-4 py-2">After Mitigation Risk Level</th>
                            <?php if ($role === 'admin'): ?>
                            <th class="border border-gray-400 px-4 py-2">Faculty</th>
                            <?php endif; ?>
                            <th class="border border-gray-400 px-4 py-2">Status</th>
                            <?php if ($is_admin): ?>
                            <th class="border border-gray-400 px-4 py-2">Option</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $role = $_SESSION['role'] ?? '';
                        $faculty_id = $_SESSION['faculty_id'] ?? null;
                        if ($role === 'admin') {
                            // Admin melihat semua data mitigasi
                            $query = $conn->query("SELECT mitigations.*, faculties.faculty_name FROM mitigations LEFT JOIN faculties ON mitigations.faculty_id = faculties.id");
                        } else {
                            // Sub-Admin dan User melihat data mitigasi sesuai faculty_id
                            $query = $conn->prepare("SELECT * FROM mitigations WHERE faculty_id = :faculty_id");
                            $query->execute(['faculty_id' => $_SESSION['faculty_id']]);
                        }
                        
                        while ($row = $query->fetch()) {
                            echo "<tr class='hover:bg-gray-50'>
                            <td class='border border-gray-400 px-4 py-2'>{$row['risk_code']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['inherent_likehood']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['inherent_impact']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['inherent_risk_level']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['existing_control']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['control_quality']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['execution_status']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['residual_likehood']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['residual_impact']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['residual_risk_level']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['risk_treatment']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['mitigation_plan']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['after_mitigation_likehood']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['after_mitigation_impact']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['after_mitigation_risk_level']}</td>";
                    
                        // Tampilkan fakultas hanya jika role adalah admin
                        if ($role === 'admin') {
                            echo "<td class='border border-gray-400 px-4 py-2'>{$row['faculty_name']}</td>";
                        }
                        
                        // Tambahkan checkbox untuk status mitigasi
                        echo "<td class='border border-gray-400 px-4 py-2 text-center'>
                                <form method='POST' action='mitigation.php'>
                                    <input type='hidden' name='form_type' value='checkbox'>
                                    <input type='hidden' name='risk_code' value='" . htmlspecialchars($row['risk_code']) . "'>
                                    <input 
                                        type='checkbox' 
                                        name='is_completed' 
                                        " . ($row['is_completed'] ? "checked" : "") . " 
                                        " . ($_SESSION['role'] === 'user' ? "disabled" : "") . " 
                                        onchange='this.form.submit()'
                                    >
                                </form>
                            </td>";


                        
                        // Tombol edit dan hapus hanya untuk admin
                        if ($is_admin) {
                            echo "<td class='border border-gray-400 px-4 py-2'>
                                    <a href='../modules/mitigation/edit_mitigation.php?edit={$row['id']}' class='text-blue-500 hover:underline'>Edit</a>
                                    <a href='../modules/mitigation/delete_mitigation.php?delete={$row['id']}' class='text-red-500 hover:underline' onclick=\"return confirm('Hapus data mitigasi ini?')\">Hapus</a>
                                </td>";
                        }
                        
                        echo "</tr>";
                        
                            }

                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
    <!-- Footer -->
    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>


    <!-- Js -->
    <script src ="../assets\js\script.js"></script>
    <script>
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const formData = new FormData();
            formData.append('form_type', 'checkbox');
            formData.append('risk_code', this.dataset.riskCode);
            formData.append('is_completed', this.checked ? 1 : 0);

            fetch('mitigation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Status berhasil diperbarui!');
                } else {
                    alert('Gagal memperbarui data!');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

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


// Modal Guide Logic
const openGuide = document.getElementById('openGuide');
const closeGuide = document.getElementById('closeGuide');
const guideModal = document.getElementById('guideModal');

if (openGuide && closeGuide && guideModal) {
    // Open modal
    openGuide.addEventListener('click', () => {
        guideModal.classList.remove('hidden');
    });

    // Close modal
    closeGuide.addEventListener('click', () => {
        guideModal.classList.add('hidden');
    });

    // Close modal on outside click
    window.addEventListener('click', (event) => {
        if (event.target === guideModal) {
            guideModal.classList.add('hidden');
        }
    });

    // Close modal on Escape key
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            guideModal.classList.add('hidden');
        }
    });
}

</script>
</body>
</html>