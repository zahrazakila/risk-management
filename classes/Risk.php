<?php
require_once __DIR__ . '/../includes/db.php';

class Risk {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mengambil semua risk codes dari database
    public function getAllRiskCodes() {
        $query = "SELECT risk_code, risk_event FROM risks";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil Semua Data Risiko
    public function getAllRisks($faculty_id = null, $is_admin = false) {
        if ($is_admin) {
            $query = "SELECT risks.*, faculties.faculty_name 
                      FROM risks 
                      LEFT JOIN faculties ON risks.faculty_id = faculties.id";
            return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT * FROM risks WHERE faculty_id = :faculty_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['faculty_id' => $faculty_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Tambah Risiko Baru
    public function addRisk($data) {
        $query = "INSERT INTO risks 
            (objective, process_business, risk_category, risk_code, risk_event, risk_cause, risk_source, qualitative, quantitative, risk_owner, department, faculty_id, created_by) 
            VALUES 
            (:objective, :process_business, :risk_category, :risk_code, :risk_event, :risk_cause, :risk_source, :qualitative, :quantitative, :risk_owner, :department, :faculty_id, :created_by)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    // Ambil Data Risiko Berdasarkan ID
    public function getRiskById($id) {
        $query = "SELECT * FROM risks WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Risiko
    public function updateRisk($id, $data) {
        $query = "UPDATE risks SET 
            objective = :objective, 
            process_business = :process_business, 
            risk_category = :risk_category, 
            risk_code = :risk_code, 
            risk_event = :risk_event, 
            risk_cause = :risk_cause, 
            risk_source = :risk_source, 
            qualitative = :qualitative, 
            quantitative = :quantitative, 
            risk_owner = :risk_owner, 
            department = :department
            WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $data['id'] = $id; // Tambahkan ID ke data array
        return $stmt->execute($data);
    }

    // Hapus Risiko
    public function deleteRisk($id) {
        $query = "DELETE FROM risks WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    // Generate Risk Code
    public function generateRiskCode() {
        $query = $this->conn->query("SELECT MAX(risk_code) AS max_code FROM risks");
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result['max_code']) {
            $lastNumber = (int)filter_var($result['max_code'], FILTER_SANITIZE_NUMBER_INT);
            return 'R' . ($lastNumber + 1);
        } else {
            return 'R1';
        }
    }
}
?>
