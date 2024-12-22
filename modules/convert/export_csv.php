<?php
require_once '../includes/db.php';

$db = new Database();
$conn = $db->getConnection();

class CSVExporter {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fetchRiskData() {
        $query = $this->conn->query("SELECT 
            risks.risk_code, risks.risk_event, risks.risk_category, risks.risk_source, 
            mitigations.inherent_likehood AS likehood, mitigations.inherent_impact AS impact
            FROM risks 
            LEFT JOIN mitigations ON risks.risk_code = mitigations.risk_code");

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exportToCSV() {
        $data = $this->fetchRiskData();

        if (empty($data)) {
            die("Tidak ada data untuk diekspor.");
        }

        // Nama file dengan timestamp
        $filename = 'risk_register_' . date('Y-m-d_H-i-s') . '.csv';

        // Set header untuk file CSV
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Buat file CSV
        $output = fopen('php://output', 'w');

        // Tulis header CSV (ambil kunci dari array pertama)
        fputcsv($output, array_keys($data[0]));

        // Tulis data ke file CSV
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}

$csvExporter = new CSVExporter($conn);
$csvExporter->exportToCSV();
?>
