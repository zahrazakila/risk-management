<?php
require_once '../includes/init.php';
require_once '../classes/RiskMapManager.php';
require_once '../includes/header.php';

// Ambil role dan faculty_id dari session
$role = $_SESSION['role'] ?? '';
$faculty_id = $_SESSION['faculty_id'] ?? null;

// Inisialisasi RiskMapManager
$riskMapManager = new RiskMapManager($conn);
$risks = $riskMapManager->getRisks($role, $faculty_id);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Map</title>
    <link rel="stylesheet" href="../assets/css/palette.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .grid {
            gap: 2px;
            width: 50%;
            margin: 0;
            padding: 0;
        }

        .grid-cols-5 {
            grid-template-columns: repeat(5, 1fr);
        }

        .cell {
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            position: relative;
            height: 100px;
        }

        .low {
            background-color:rgb(42, 114, 59);
        }

        .minor { 
            background-color:rgb(33, 182, 67); 
        } /* Hijau Muda */

        .medium {
            background-color: #ffc107;
        }

        .high {
            background-color: #dc3545;
        }

        .bubble-container {
            display: flex;
            flex-wrap: wrap; /* Agar bubble tidak tumpang tindih */
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            position: relative;
        }

        .risk-bubble {
            border-radius: 50%;
            font-size: 12px;
            padding: 5px 10px;
            margin: 5px;
            text-align: center;
            background: white;
            border: 2px solid black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 30px;
            width: 30px;
        }

        .inherent {
            background-color: #007bff;
            color: white;
        }

        .residual {
            background-color: #ffc107;
            color: black;
        }

        .after {
            background-color: #dc3545;
            color: white;
        }



    </style>
</head>
<body class="bg-light-green text-gray-800">
        
    <main class="container mx-auto px-4 py-6 flex-grow ">
    <section class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Risk Map Visualization</h2>
            <div class="w-full">
                <!-- Container Grid -->
                <div class="flex items-center justify-center w-full">
                    <!-- Label Impact (Sisi Kiri) -->
                    <div class="hidden md:flex flex-col justify-between text-sm font-semibold pr-4">
                        <div class="h-24 flex items-center justify-end">Very High</div>
                        <div class="h-24 flex items-center justify-end">High</div>
                        <div class="h-24 flex items-center justify-end">Medium</div>
                        <div class="h-24 flex items-center justify-end">low</div>
                        <div class="h-24 flex items-center justify-end">Very low</div>
                    </div>

                    <!-- Grid Risiko Mepet Kiri -->
                    <div class="grid grid-cols-5 gap-1 flex-1 w-full rounded-lg max-w-xs md:max-w-full mx-auto">
                        <?php
                        for ($impact = 5; $impact >= 1; $impact--) {
                            for ($likelihood = 1; $likelihood <= 5; $likelihood++) {
                                // Filter risiko dengan Likelihood dan Impact saat ini
                                $cellRisks = array_filter($risks, function ($risk) use ($impact, $likelihood) {
                                    return $risk['impact'] == $impact && $risk['likelihood'] == $likelihood;
                                });

                               // Modifikasi logika pewarnaan
                                if (($impact == 5 && $likelihood == 1) || ($impact == 1 && $likelihood == 5)) {
                                    $class = 'medium'; // Warna kuning untuk kotak kiri atas dan kanan bawah
                                } else {
                                    $riskLevel = $impact * $likelihood; // Hitung risk level
                                    if ($riskLevel >= 16) {
                                        $class = 'high'; // Warna merah
                                    } elseif ($riskLevel >= 10) {
                                        $class = 'medium'; // Warna kuning
                                    } elseif ($riskLevel >= 5) {
                                        $class = 'minor'; // Warna hijau muda
                                    } else {
                                        $class = 'low'; // Warna hijau tua
                                    }
                                }

                                // Mulai Sel Grid
                                echo "<div class='flex justify-center items-center border border-gray-300 $class text-white h-24 relative'>";

                                // Tambahkan Bubble Risiko
                                echo "<div class='flex flex-wrap justify-center items-center'>";
                                foreach ($cellRisks as $risk) {
                                    $bubbleClass = strtolower($risk['type']); // inherent, residual, or after mitigation
                                    echo "<div class='risk-bubble $bubbleClass'>{$risk['risk_code']}</div>";
                                }
                                echo "</div>";

                                echo "</div>"; // Tutup Sel Grid
                            }
                        }
                        
                        ?>
                        <!-- Label Likelihood (Bawah) -->
                        <div class="text-center text-sm font-semibold">Very low</div>
                        <div class="text-center text-sm font-semibold">Low</div>
                        <div class="text-center text-sm font-semibold">Medium</div>
                        <div class="text-center text-sm font-semibold">High</div>
                        <div class="text-center text-sm font-semibold">Very High</div>
                    </div>
                </div>
            </div>


        </section>
        <section  class="bg-white p-6 rounded shadow mb-6">
        <div class="bg-white mt-6 p-4 border rounded shadow">
                <h3 class="text-lg font-bold mb-2">Information</h3>
                <ul class="list-disc ml-4">
                    <li><span class="font-bold">A</span>: Inherent Risk (Before Mitigation)</li>
                    <li><span class="font-bold">B</span>: Residual Risk (After Initial Mitigation)</li>
                    <li><span class="font-bold">C</span>: After Mitigation Risk</li>
                </ul>
                <h4 class="font-semibold mt-4">Color Code:</h4>
                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="inline-block w-5 h-5 bg-green-800"></span> <span>Low</span>
                    <span class="inline-block w-5 h-5 bg-green-600"></span> <span>Minor</span>
                    <span class="inline-block w-5 h-5 bg-yellow-400"></span> <span>Medium</span>
                    <span class="inline-block w-5 h-5 bg-red-600"></span> <span>High</span>
                </div>
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

</body>
</html>
