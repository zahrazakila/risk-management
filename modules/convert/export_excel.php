<?php
require_once '../includes/db.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Koneksi database
$db = new Database();
$conn = $db->getConnection();

session_start();
$role = $_SESSION['role'] ?? null;
$faculty_id = $_SESSION['faculty_id'] ?? null;

if (!$role) {
    die("Anda tidak memiliki izin untuk mengakses halaman ini.");
}

// Query Data
if ($role === 'admin') {
    $queryRisks = $conn->query("SELECT * FROM risks");
    $queryMitigations = $conn->query("SELECT * FROM mitigations");
} else {
    $queryRisks = $conn->prepare("SELECT * FROM risks WHERE faculty_id = :faculty_id");
    $queryRisks->execute(['faculty_id' => $faculty_id]);

    $queryMitigations = $conn->prepare("SELECT * FROM mitigations WHERE faculty_id = :faculty_id");
    $queryMitigations->execute(['faculty_id' => $faculty_id]);
}

$dataRisks = $queryRisks->fetchAll(PDO::FETCH_ASSOC);
$dataMitigations = $queryMitigations->fetchAll(PDO::FETCH_ASSOC);

if (empty($dataRisks) && empty($dataMitigations)) {
    die("Tidak ada data untuk diekspor!");
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

function setHeaderStyle($sheet, $cellRange) {
    $sheet->getStyle($cellRange)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);
}

function setTableStyle($sheet, $range) {
    $sheet->getStyle($range)->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
}

$row = 1;
$sheet->setCellValue('A' . $row, 'Risks Table');
$sheet->mergeCells("A{$row}:L{$row}");
$sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header Risks
$row++;
$headersRisks = !empty($dataRisks) ? array_keys($dataRisks[0]) : [];
$lastColRisk = !empty($headersRisks) ? chr(64 + count($headersRisks)) : 'A';

$sheet->fromArray($headersRisks, null, 'A' . $row);
setHeaderStyle($sheet, "A{$row}:{$lastColRisk}{$row}");

$row++;
$sheet->fromArray($dataRisks, null, 'A' . $row);
setTableStyle($sheet, "A" . ($row - 1) . ":{$lastColRisk}" . ($row + count($dataRisks) - 1));

$row += count($dataRisks) + 2;

// Mitigations Table
$sheet->setCellValue('A' . $row, 'Mitigations Table');
$sheet->mergeCells("A{$row}:L{$row}");
$sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header Mitigations
$row++;
$headersMitigations = !empty($dataMitigations) ? array_keys($dataMitigations[0]) : [];
$lastColMitigation = !empty($headersMitigations) ? chr(64 + count($headersMitigations)) : 'A';

$sheet->fromArray($headersMitigations, null, 'A' . $row);
setHeaderStyle($sheet, "A{$row}:{$lastColMitigation}{$row}");

$row++;
$sheet->fromArray($dataMitigations, null, 'A' . $row);
setTableStyle($sheet, "A" . ($row - 1) . ":{$lastColMitigation}" . ($row + count($dataMitigations) - 1));

// Output file
$filename = "Data_Export_" . date('YmdHis') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
