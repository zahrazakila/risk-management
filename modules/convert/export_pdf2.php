<?php
ob_start();

// Tambahkan timestamp untuk nama file unik
$filename = 'risk_register_report_' . date('Y-m-d_H-i-s') . '.pdf';

// Set header
header('Content-Type: application/pdf');
header("Content-Disposition: attachment; filename=\"$filename\"");

// Hapus cache
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Library dan Koneksi Database
require_once '../includes/db.php';
require_once '../vendor/tecnickcom/tcpdf/tcpdf.php';

$db = new Database();
$conn = $db->getConnection();

class PDFGenerator {
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

    public function generatePDF() {
        $data = $this->fetchRiskData();

        if (empty($data)) die('No data to display.');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetFont('times', 'B', 16);
        $pdf->Cell(0, 10, 'Laporan Risiko', 0, 1, 'C');

        // Header
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(30, 8, 'Kode Risiko', 1, 0, 'C');
        $pdf->Cell(70, 8, 'Deskripsi', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Likelihood', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Impact', 1, 1, 'C');

        // Isi
        $pdf->SetFont('times', '', 10);
        foreach ($data as $row) {
            $pdf->Cell(30, 8, $row['risk_code'], 1, 0, 'C');
            $pdf->Cell(70, 8, $row['risk_event'], 1, 0, 'L');
            $pdf->Cell(30, 8, $row['likehood'] ?? '-', 1, 0, 'C');
            $pdf->Cell(30, 8, $row['impact'] ?? '-', 1, 1, 'C');
        }

        $pdf->Output('Risk_Register_Report.pdf', 'D');
    }
}

$pdfGenerator = new PDFGenerator($conn);
$pdfGenerator->generatePDF();
?>
