<?php
require_once '../includes/init.php';
require_once '../classes/Risk.php';

$risk = new Risk($conn);

// Periksa role
$role = $_SESSION['role'] ?? '';

$is_admin = $_SESSION['role'] === 'admin' || $_SESSION['role'] === 'sub-admin';
$faculty_id = $_SESSION['faculty_id'];

// Tangani POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$is_admin) {
        header("HTTP/1.1 403 Forbidden");
        exit;
    }

    $data = [
        'objective' => $_POST['objective'],
        'process_business' => $_POST['process_business'],
        'risk_category' => $_POST['risk_category'],
        'risk_code' => $risk->generateRiskCode(),
        'risk_event' => $_POST['risk_event'],
        'risk_cause' => $_POST['risk_cause'],
        'risk_source' => $_POST['risk_source'],
        'qualitative' => $_POST['qualitative'],
        'quantitative' => str_replace(['Rp', '.', ' '], '', $_POST['quantitative']),
        'risk_owner' => $_POST['risk_owner'],
        'department' => $_POST['department'],
        'faculty_id' => $faculty_id,
        'created_by' => $_SESSION['user_id']
    ];

    $risk->addRisk($data);
    header("Location: identification.php");
    exit;
}

// Ambil data risiko
$risks = $risk->getAllRisks($faculty_id, $is_admin);

// Setelah logika, lanjutkan ke bagian require HTML
require_once '../includes/header.php';
?>


<!-- Bagian HTML (sama seperti sebelumnya) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identifikasi Risiko</title>
    <link rel="stylesheet" href="../assets/css/palette.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <style>

    </style>
</head>
<body class="bg-light-green text-gray-800 min-h-screen flex flex-col">
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 flex-grow">
        <?php if ($is_admin): ?>
        <!-- Form Tambah Risiko -->
        <section class="bg-white rounded shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Add Risk</h2>
            <form action="identification.php" method="POST" class="space-y-4">
                <!-- Kode Risiko dihapus karena otomatis -->
                <!-- <div>
                    <label for="risk_code"class="block text-gray-700">Kode Risiko</label>
                    <input type="text" id="risk_code" name="risk_code" required placeholder="Contoh: R1"  class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div> -->
                <div>
                    <label for="objective" class="block text-gray-700">Objective</label>
                    <input type="text" id="objective" name="objective" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div>
                <div>
                    <label for="process_business" class="block text-gray-700">Business Process</label>
                    <select id="process_business" name="process_business" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                        <option value="Akademik">Academic</option>
                        <option value="Finansial">Financial</option>
                        <option value="Kepegawaian">Staff</option>
                    </select>
                </div>
                <div>
                    <label for="risk_category" class="block text-gray-700">Risk Category</label>
                    <select id="risk_category" name="risk_category" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                        <option value="Strategic">Strategic</option>
                        <option value="Financial">Financial</option>
                        <option value="Operational">Operational</option>
                    </select>
                </div>
                <div>
                    <label for="risk_event" class="block text-gray-700">Risk Event</label>
                    <input type="text" id="risk_event" name="risk_event" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div>
                <div>
                    <label for="risk_cause" class="block text-gray-700">Risk Cause</label>
                    <input type="text" id="risk_cause" name="risk_cause" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div>
                <div>
                    <label for="risk_source" class="block text-gray-700">Risk Source</label>
                    <select id="risk_source" name="risk_source" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                        <option value="Internal">Internal</option>
                        <option value="External">External</option>
                    </select>
                </div>
                <div>
                    <label for="qualitative" class="block text-gray-700">Severity (Qualitative)</label>
                    <input type="text" id="qualitative" name="qualitative" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div>
                <div>
                    <label for="quantitative" class="block text-gray-700">Severity (Quantitative)</label>
                    <input type="text" id="quantitative" name="quantitative" required 
                        placeholder="Masukkan nominal (contoh: 1000000)"
                        class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500" 
                        oninput="formatRupiah(this)">
                </div>
                <div>
                    <label for="risk_owner" class="block text-gray-700">Risk Owner</label>
                    <input type="text" id="risk_owner" name="risk_owner" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div>
                <div>
                    <label for="department" class="block text-gray-700">Department</label>
                    <input type="text" id="department" name="department" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-green-500">
                </div>
                

                <!-- Tambahkan elemen lainnya dari form -->
                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">Save</button>
            </form>
        </section>
        <?php endif; ?>

        <!-- Tabel Risiko -->
        <section id="data-risiko" class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4">Risk List</h2>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-400">
                    <thead class="bg-beige-green">
                        <tr>
                            <th class="border border-gray-400 px-4 py-2">Risk Code</th>
                            <th class="border border-gray-400 px-4 py-2">Objective</th>
                            <th class="border border-gray-400 px-4 py-2">Business Prosess</th>
                            <th class="border border-gray-400 px-4 py-2">Risk Category</th>
                            <th class="border border-gray-400 px-4 py-2">Risk Event</th>
                            <th class="border border-gray-400 px-4 py-2">Risk Cause</th>
                            <th class="border border-gray-400 px-4 py-2">Risk Source</th>
                            <th class="border border-gray-400 px-4 py-2">Severity (Qualitative)</th>
                            <th class="border border-gray-400 px-4 py-2">Severity (Quantitative)</th>
                            <th class="border border-gray-400 px-4 py-2">Risk Owner</th>
                            <th class="border border-gray-400 px-4 py-2">Department</th>
                            <?php if (strtolower($role) === 'admin'): ?>
                                <th class="border border-gray-400 px-4 py-2">Faculty</th>
                            <?php endif; ?>
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
                            // Admin Utama: Ambil semua data risiko dan nama fakultas
                            $query = $conn->query("
                                SELECT risks.*, faculties.faculty_name 
                                FROM risks
                                LEFT JOIN faculties ON risks.faculty_id = faculties.id
                            ");
                        } else {
                            // Sub-Admin atau User: Ambil data sesuai faculty_id dari session
                            $query = $conn->prepare("
                                SELECT * 
                                FROM risks 
                                WHERE faculty_id = :faculty_id
                            ");
                            $query->execute(['faculty_id' => $_SESSION['faculty_id']]);
                        }
                        
                        while ($row = $query->fetch()) {
                            echo "<tr class='hover:bg-gray-50'>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['risk_code']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['objective']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['process_business']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['risk_category']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['risk_event']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['risk_cause']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['risk_source']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['qualitative']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>Rp " . number_format($row['quantitative'], 0, ',', '.') . "</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['risk_owner']}</td>
                                    <td class='border border-gray-400 px-4 py-2'>{$row['department']}</td>";
                            if ($role === 'admin') {
                                echo "<td class='border border-gray-400 px-4 py-2'>{$row['faculty_name']}</td>";
                            }
                            if ($is_admin) {
                                echo "<td class='border border-gray-400 px-4 py-2'>
                                        <a href='../modules/identification/edit_risk.php?id={$row['id']}' class='text-blue-500 hover:underline'>Edit</a>
                                        <a href='../modules/identification/delete_risk.php?id={$row['id']}' class='text-red-500 hover:underline' onclick='return confirm(\"Hapus risiko ini?\")'>Hapus</a>
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

    <!-- Footer -->
    <footer class="bg-dark-green text-white text-center">
        <div class="bg-medium-green py-2"></div> <!-- Warna hijau muda di atas -->
            <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
                © 2024 Risk Management Dashboard. All rights reserved.
            </div>
    </footer>

    <!-- JavaScript -->
    <script>
    function formatRupiah(input) {
        let value = input.value.replace(/[^0-9]/g, ""); // Hapus semua karakter kecuali angka
        let formattedValue = "";

        if (value) {
            formattedValue = "Rp " + parseInt(value, 10).toLocaleString("id-ID"); // Format angka dengan pemisah ribuan
        }

        input.value = formattedValue; // Set kembali nilai input dengan format Rupiah
    }
    </script>

</body>
</html>