<?php
require_once '../includes/init.php';
require_once '../classes/RiskManager.php';
require_once '../classes/MitigationManager.php';
require_once '../includes/header.php'; // Header dipanggil di sini

// Inisialisasi objek
$riskManager = new RiskManager($conn);
$mitigationManager = new MitigationManager($conn);

// Ambil data
$role = $_SESSION['role'];
$faculty_id = $_SESSION['faculty_id'] ?? null;

$risks = $riskManager->getRecentRisks($role, $faculty_id);
$riskSummary = $riskManager->getRiskSummary($role, $faculty_id);
$totalRisks = $riskManager->getTotalRisks($role, $faculty_id);

$mitigations = $mitigationManager->getRecentMitigations($role, $faculty_id);

$totalLoss = $mitigationManager->getTotalLoss($role, $faculty_id);
$activeRisks = $mitigationManager->getActiveRisks($role, $faculty_id);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/palette.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light-green text-gray-800">
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-0.8">
    <!-- Grafik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Bagian Kiri: Total Risks, Active Risks, Total Kerugian -->
            <div class="flex flex-col space-y-4">
                <!-- Total Risks -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 flex items-center space-x-4">
                    <div class="text-green-400 text-4xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-600">Total Risks</h2>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalRisks; ?></p>
                    </div>
                </div>

                <!-- Active Risks -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 flex items-center space-x-4">
                    <div class="text-purple-400 text-4xl">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-600">Active Risks</h2>
                        <p class="text-3xl font-bold text-gray-800"><?= $activeRisks; ?></p>
                    </div>
                </div>

                <!-- Total Kerugian -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 flex items-center space-x-4">
                    <div class="text-red-400 text-4xl">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-600">Severity (Quanitative)</h2>
                        <p class="text-3xl font-bold text-red-500">
                            Rp <?= number_format($totalLoss, 0, ',', '.'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chart Kategori Risiko -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 flex flex-col items-center">
                <h2 class="text-lg font-bold mb-4 text-center text-gray-800">Risk Category</h2>
                <div class="h-48 w-full flex justify-center">
                    <canvas id="riskCategoryChart"></canvas>
                </div>
            </div>

            <!-- Chart Sumber Risiko -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 flex flex-col items-center">
                <h2 class="text-lg font-bold mb-4 text-center text-gray-800">Risk Source</h2>
                <div class="h-48 w-full flex justify-center">
                    <canvas id="riskSourceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-y-6 md:gap-x-6 mt-6 mb-6">
            <!-- Ringkasan Data -->
            <div class="col-span-1 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-4 mr-0 ml-0">
                <h2 class="text-xl font-bold mb-4 text-center">Risk Data Summary</h2>
                <div class="overflow-auto">
                    <table class="table-auto w-full border border-gray-400">
                        <thead class="bg-beige-green">
                            <tr>
                                <th class="px-4 py-2 border border-gray-400 whitespace-nowrap text-left">Risk Category</th>
                                <th class="px-4 py-2 border border-gray-400 whitespace-nowrap text-center">Risk Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($riskSummary as $summary): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border border-gray-400"><?= htmlspecialchars($summary['risk_category'] ?? 'N/A'); ?></td>
                                <td class="px-4 py-2 border border-gray-400 text-center"><?= htmlspecialchars($summary['total'] ?? 0); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Tombol Download -->
                <div class="flex justify-left space-x-4 mt-4">
                    <!-- Download Excel -->
                    <a href="export_excel.php" 
                    class="bg-green-400 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                        Download Excel
                    </a>

                    <!-- Download PDF -->
                    <a href="export_pdf2.php" target="_blank"
                    class="bg-red-400 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                        Download PDF
                    </a>

                    <!-- Ekspor ke CSV -->
                    <a href="export_csv.php" 
                    class="bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                        Download CSV
                    </a>
                </div>
            </div>


            <!-- Recent Update Risks -->
            <div class="col-span-2 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-4">
                <h2 class="text-xl font-bold mb-4 text-center">Recent Risks</h2>
                <div class="overflow-auto">
                    <table class="table-auto w-full border border-gray-500">
                        <thead class="bg-beige-green">
                            <tr>
                                <th class="px-4 py-2 border border-gray-400">Risk Event</th>
                                <th class="px-4 py-2 border border-gray-400">Risk Cause</th>
                                <th class="px-4 py-2 border border-gray-400">Mitigation Plan</th>
                                <th class="px-4 py-2 border border-gray-400">Status</th>
                                <?php if ($role === 'admin'): ?>
                                    <th class="px-4 py-2 border border-gray-400">Faculty</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($risks); $i++): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border border-gray-400"><?= htmlspecialchars($risks[$i]['risk_event'] ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2 border border-gray-400"><?= htmlspecialchars($risks[$i]['risk_cause'] ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2 border border-gray-400"><?= htmlspecialchars($mitigations[$i]['mitigation_plan'] ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2 border text-center border-gray-400">
                                        <input type="checkbox" 
                                            <?= isset($mitigations[$i]['is_completed']) && $mitigations[$i]['is_completed'] ? 'checked' : ''; ?> 
                                            disabled>
                                    </td>
                                    <?php if ($role === 'admin'): ?>
                                        <td class="px-4 py-2 border border-gray-400"><?= htmlspecialchars($risks[$i]['faculty_name'] ?? 'N/A'); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                    <div class="mt-4 text-right">
                        <a href="identification.php#data-risiko" class="text-blue-500 hover:underline font-semibold">
                            View All ->
                        </a>
                    </div>
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


    <!-- Data Grafik -->
    <?php
    // Daftar kategori risiko statis
    $defaultCategories = ['Financial', 'Operational', 'Strategic'];
    $categories = $defaultCategories;
    $totals = array_fill(0, count($defaultCategories), 0); // Isi dengan 0 terlebih dahulu

    // Ambil faculty_id dari session
    $faculty_id = $_SESSION['faculty_id'] ?? null;

    // Query data kategori risiko dengan filter faculty_id
    if ($_SESSION['role'] === 'admin') {
        // Admin melihat semua data risiko tanpa filter
        $query = $conn->query("SELECT risk_category, COUNT(*) AS total FROM risks GROUP BY risk_category");
    } else {
        // Sub-Admin melihat data risiko sesuai faculty_id
        $query = $conn->prepare("SELECT risk_category, COUNT(*) AS total 
                                FROM risks 
                                WHERE faculty_id = :faculty_id 
                                GROUP BY risk_category");
        $query->execute(['faculty_id' => $faculty_id]);
    }

    while ($row = $query->fetch()) {
        $index = array_search($row['risk_category'], $categories);
        if ($index !== false) {
            $totals[$index] = (int)$row['total'];
        }
    }

    // Data sumber risiko (Internal & External)
    $riskSources = ['Internal', 'External'];
    $sourceCounts = [];

    foreach ($riskSources as $source) {
        if ($_SESSION['role'] === 'admin') {
            // Admin melihat semua data sumber risiko
            $query = $conn->prepare("SELECT COUNT(*) AS total FROM risks WHERE risk_source = :source");
            $query->execute(['source' => $source]);
        } else {
            // Sub-Admin melihat sumber risiko sesuai faculty_id
            $query = $conn->prepare("SELECT COUNT(*) AS total 
                                    FROM risks 
                                    WHERE risk_source = :source AND faculty_id = :faculty_id");
            $query->execute(['source' => $source, 'faculty_id' => $faculty_id]);
        }
        $result = $query->fetch();
        $sourceCounts[] = $result['total'];
    }
    ?>

    <script>
        const categories = <?php echo json_encode($categories); ?>;
        const totals = <?php echo json_encode($totals); ?>;
        const riskSources = <?php echo json_encode($riskSources); ?>;
        const sourceCounts = <?php echo json_encode($sourceCounts); ?>;

        const ctxCategory = document.getElementById('riskCategoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Jumlah Risiko',
                    data: totals,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxSource = document.getElementById('riskSourceChart').getContext('2d');
        new Chart(ctxSource, {
            type: 'doughnut',
            data: { labels: riskSources, datasets: [{ data: sourceCounts, backgroundColor: ['#36A2EB', '#FF6384'] }] }
        });
    </script>
</body>
</html>
