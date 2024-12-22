<?php
require_once __DIR__ . '/../includes/db.php';

class Mitigation {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua risk_code dari tabel risks
    public function getAllRiskCodes($role, $faculty_id = null) {
        if ($role === 'admin') {
            // Admin dapat mengakses semua risk_code
            $query = "SELECT risk_code FROM risks";
            $stmt = $this->conn->prepare($query);
        } elseif ($role === 'sub-admin') {
            // Sub-admin hanya dapat mengakses risk_code yang sesuai dengan faculty_id mereka
            $query = "SELECT risk_code FROM risks WHERE faculty_id = :faculty_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
        } else {
            // Role user atau role lain tidak dapat mengakses risk_code
            return [];
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan semua risk_code yang sesuai
    }



    // Ambil semua data mitigasi
    public function getAllMitigations($faculty_id = null, $is_admin = false) {
        if ($is_admin) {
            $query = "SELECT mitigations.*, faculties.faculty_name 
                      FROM mitigations 
                      LEFT JOIN faculties ON mitigations.faculty_id = faculties.id";
            return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT * FROM mitigations WHERE faculty_id = :faculty_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['faculty_id' => $faculty_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Ambil data mitigasi berdasarkan ID
    public function getMitigationById($id) {
        try {

            $stmt = $this->conn->prepare("SELECT * FROM mitigations WHERE id = :id");
            $stmt->execute(['id' => $id]);
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Debugging
            // if (!$result) {
            //     echo "Data tidak ditemukan di database untuk ID: $id";
            // } else {
            //     echo "Data ditemukan:<br>";
            //     print_r($result);
            // }
    
            return $result;
        } catch (PDOException $e) {
            echo "Query Error: " . $e->getMessage();
            return null;
        }
    }
    
    
    // Tambah mitigasi
    public function addMitigation($data) {
        $query = "INSERT INTO mitigations (
            risk_code, inherent_likehood, inherent_impact, inherent_risk_level, 
            existing_control, control_quality, execution_status, residual_likehood, 
            residual_impact, residual_risk_level, risk_treatment, mitigation_plan, 
            after_mitigation_likehood, after_mitigation_impact, after_mitigation_risk_level, 
            faculty_id, is_completed
        ) VALUES (
            :risk_code, :inherent_likehood, :inherent_impact, :inherent_risk_level, 
            :existing_control, :control_quality, :execution_status, :residual_likehood, 
            :residual_impact, :residual_risk_level, :risk_treatment, :mitigation_plan, 
            :after_mitigation_likehood, :after_mitigation_impact, :after_mitigation_risk_level, 
            :faculty_id, :is_completed
        )";
    
        $stmt = $this->conn->prepare($query);
    
        return $stmt->execute($data);
    }
    
    

    // Update mitigasi
    public function updateMitigation($id, $data) {
        $query = "UPDATE mitigations SET 
                    risk_code = :risk_code,
                    inherent_likehood = :inherent_likehood,
                    inherent_impact = :inherent_impact,
                    inherent_risk_level = :inherent_risk_level,
                    existing_control = :existing_control,
                    control_quality = :control_quality,
                    execution_status = :execution_status,
                    residual_likehood = :residual_likehood,
                    residual_impact = :residual_impact,
                    residual_risk_level = :residual_risk_level,
                    risk_treatment = :risk_treatment,
                    mitigation_plan = :mitigation_plan,
                    after_mitigation_likehood = :after_mitigation_likehood,
                    after_mitigation_impact = :after_mitigation_impact,
                    after_mitigation_risk_level = :after_mitigation_risk_level
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $data['id'] = $id; // Tambahkan ID ke data array
        return $stmt->execute($data);
    }
    
    public function updateMitigationStatus($risk_code, $is_completed) {
        $query = "UPDATE mitigations SET is_completed = :is_completed WHERE risk_code = :risk_code";
        $stmt = $this->conn->prepare($query);
    
        return $stmt->execute([
            'is_completed' => $is_completed,
            'risk_code' => $risk_code
        ]);
    }
    

    // Hapus mitigasi
    public function deleteMitigation($id) {
        $stmt = $this->conn->prepare("DELETE FROM mitigations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
