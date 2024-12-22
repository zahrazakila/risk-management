<?php
require_once '../../includes/init.php';
require_once '../../classes/Monitoring.php';

$username = $_SESSION['username'];
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}

$monitoring = new Monitoring($conn);

// Ambil data monitoring berdasarkan ID
$id = $_GET['edit'] ?? null;
if (!$id) {
    header("Location: ../../pages/monitoring.php");
    exit;
}
$editData = $monitoring->getMonitoringById($id);

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'risk_code' => $_POST['risk_code'],
        'risk_event' => $_POST['risk_event'],
        'mitigation_plan' => $_POST['mitigation_plan'],
        'month_status' => $_POST['month_status'],
        'evidence' => $_POST['evidence'],
        'pic' => $_POST['pic']
    ];
    $monitoring->updateMonitoring($id, $data);
    header("Location: ../../pages/monitoring.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/palette.css">
    <style>
        .green {
            background-color: #28a745;
            color: white;
        }
        .blue {
            background-color: #007bff;
            color: white;
        }
        .month-cell {
            cursor: pointer;
        }
        footer {
            background-color: #2563eb; /* Warna biru footer */
            color: white;
            text-align: center;
            padding: 1rem 0;
}
    </style>
</head>
<body class="bg-light-green text-gray-800">
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
                <a href="../../users/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Profile</a>
                <a href="../../users/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded transition-all duration-300">Logout</a>
            </div>
        </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-6 flex-grow ">
        <section class="bg-white p-6 rounded shadow mb-6">
        <form action="edit_monitoring.php?edit=<?php echo htmlspecialchars($id); ?>" method="POST" class="space-y-4" >
            <div>
                <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($editData['id'] ?? ''); ?>">
                
                <label for="risk_code" class="block text-gray-700 font-medium">Risk Code</label>
                <select id="risk_code" name="risk_code" required onchange="updateRiskDetails(this.value)" class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500">
                    <option value="" disabled selected>Pilih Risk Code</option>
                    <?php
                    $query = $conn->query("SELECT risks.risk_code, risks.risk_event, mitigations.mitigation_plan 
                    FROM risks 
                    LEFT JOIN mitigations ON risks.risk_code = mitigations.risk_code");
                    $risks = [];
                    while ($row = $query->fetch()) {
                        $risks[$row['risk_code']] = [
                            'risk_event' => $row['risk_event'],
                            'mitigation_plan' => $row['mitigation_plan']
                        ];
                        $selected = ($editData['risk_code'] ?? '') === $row['risk_code'] ? 'selected' : '';
                        echo "<option value='{$row['risk_code']}' $selected>{$row['risk_code']}</option>";
                    }
                    ?>
                </select>
            </div>

            <input type="hidden" id="risk_event" name="risk_event" value="<?php echo htmlspecialchars($editData['risk_event'] ?? ''); ?>">
            <input type="hidden" id="mitigation_plan" name="mitigation_plan" value="<?php echo htmlspecialchars($editData['mitigation_plan'] ?? ''); ?>">

            <h3 class="text-lg font-bold mb-2">Status Per Bulan</h3>
            <table class="table-auto w-full border border-gray-500">
                <thead class="bg-beige-green">
                    <tr>
                        <th class="border border-gray-500 px-4 py-2 text-center text-gray-800 font-bold">Bulan</th>
                        <th class="border border-gray-500 px-4 py-2 text-center text-gray-800 font-bold">Rencana Mitigasi</th>
                        <th class="border border-gray-500 px-4 py-2 text-center text-gray-800 font-bold">Pelaksanaan Mitigasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $monthStatus = json_decode($editData['month_status'] ?? '{}', true);

                    foreach ($months as $month) {
                        $rencana = $monthStatus[$month]['rencana'] ?? 'none';
                        $pelaksanaan = $monthStatus[$month]['pelaksanaan'] ?? 'none';

                        echo "<tr class='hover:bg-gray-50'>";
                            // Kolom Bulan
                            echo "<td class='border border-gray-500 px-4 py-2 text-center text-gray-700 font-medium'>$month</td>";

                            // Kolom Rencana Mitigasi
                            echo "<td
                                    class='month-cell editable border border-gray-500 px-4 py-2 text-center font-medium " . 
                                    ($rencana === 'rencana' ? 'bg-blue-500 text-white' : 'bg-blue-100 text-gray-700') . "' 
                                    data-month='$month' 
                                    data-type='rencana' 
                                    data-status='$rencana'>
                                &nbsp;
                                </td>";

                            // Kolom Pelaksanaan Mitigasi
                            echo "<td 
                                    class='month-cell editable border border-gray-500 px-4 py-2 text-center font-medium " . 
                                    ($pelaksanaan === 'pelaksanaan' ? 'bg-green-500 text-white' : 'bg-green-100 text-gray-700') . "' 
                                    data-month='$month' 
                                    data-type='pelaksanaan' 
                                    data-status='$pelaksanaan'>
                                &nbsp;
                                </td>";
                            echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <input type="hidden" id="month_status" name="month_status">

            <label for="evidence" class="block text-gray-700 font-medium">Evidence</label>
            <textarea id="evidence" name="evidence" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500"><?php echo htmlspecialchars($editData['evidence'] ?? ''); ?></textarea>

            <label for="pic" class="block text-gray-700 font-medium">PIC</label>
            <input type="text" id="pic" name="pic" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-blue-500" value="<?php echo htmlspecialchars($editData['pic'] ?? ''); ?>">

            <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">Update</button>
        </form>
        </section>
    </main>
    <script>
    const risks = <?php echo json_encode($risks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

    function updateRiskDetails(selectedCode) {
        const riskEventInput = document.getElementById('risk_event');
        const mitigationPlanInput = document.getElementById('mitigation_plan');
        riskEventInput.value = risks[selectedCode]?.risk_event || '';
        mitigationPlanInput.value = risks[selectedCode]?.mitigation_plan || '';
    }

    document.querySelectorAll('.month-cell.editable').forEach(cell => {
        cell.addEventListener('click', function () {
            toggleStatus(cell);
        });
    });


    document.querySelector('form').addEventListener('submit', function () {
        const monthCells = document.querySelectorAll('.month-cell.editable');
        const monthStatus = {};
        monthCells.forEach(cell => {
            const month = cell.getAttribute('data-month');
            const type = cell.getAttribute('data-type');
            const status = cell.getAttribute('data-status');
            if (!monthStatus[month]) {
                monthStatus[month] = {};
            }
            monthStatus[month][type] = status;
        });
        document.getElementById('month_status').value = JSON.stringify(monthStatus);
    });


    // Fungsi untuk toggle status (rencana atau pelaksanaan)
    function toggleStatus(cell) {
    const currentStatus = cell.getAttribute('data-status'); // Status saat ini
    const type = cell.getAttribute('data-type');           // Jenis: rencana/pelaksanaan
    let newStatus;

    // Logika untuk toggle status
    if (currentStatus === 'none') {
        newStatus = type; // Aktifkan status
    } else {
        newStatus = 'none'; // Matikan status
    }

    // Perbarui data-status
    cell.setAttribute('data-status', newStatus);

    // Hapus semua kelas warna dan tambahkan sesuai status baru
    cell.classList.remove('bg-blue-500', 'bg-green-500', 'text-white', 'bg-gray-100', 'text-gray-700');
    if (newStatus === 'rencana') {
        cell.classList.add('bg-blue-500', 'text-white');
    } else if (newStatus === 'pelaksanaan') {
        cell.classList.add('bg-green-500', 'text-white');
    } else {
        cell.classList.add('bg-gray-100', 'text-gray-700');
    }
}
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
</body>
</html>
