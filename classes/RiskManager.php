<?php
class RiskManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRecentRisks($role, $faculty_id = null) {
        if ($role === 'admin') {
            $query = $this->conn->query("
                SELECT risks.risk_event, risks.risk_cause, risks.risk_category, faculties.faculty_name
                FROM risks 
                LEFT JOIN faculties ON risks.faculty_id = faculties.id
                ORDER BY risks.id DESC LIMIT 5
            ");
        } else {
            $query = $this->conn->prepare("
                SELECT risk_event, risk_cause, risk_category
                FROM risks WHERE faculty_id = :faculty_id
                ORDER BY id DESC LIMIT 5
            ");
            $query->execute(['faculty_id' => $faculty_id]);
        }
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRiskSummary($role, $faculty_id = null) {
        $defaultCategories = ['Strategic', 'Financial', 'Operational'];
        $result = [];
    
        // Query data dari database
        if ($role === 'admin') {
            $query = $this->conn->query("SELECT risk_category, COUNT(*) AS total FROM risks GROUP BY risk_category");
        } else {
            $query = $this->conn->prepare("SELECT risk_category, COUNT(*) AS total 
                                         FROM risks 
                                         WHERE faculty_id = :faculty_id 
                                         GROUP BY risk_category");
            $query->execute(['faculty_id' => $faculty_id]);
        }
    
        // Fetch hasil query
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Bandingkan dengan kategori default dan tambahkan jika tidak ada
        foreach ($defaultCategories as $category) {
            $found = false;
            foreach ($data as $row) {
                if ($row['risk_category'] === $category) {
                    $result[] = $row;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $result[] = ['risk_category' => $category, 'total' => 0];
            }
        }
    
        return $result;
    }
    

    public function getTotalRisks($role, $faculty_id = null) {
        if ($role === 'admin') {
            $query = $this->conn->query("SELECT COUNT(*) AS total_risks FROM risks");
        } else {
            $query = $this->conn->prepare("SELECT COUNT(*) AS total_risks FROM risks WHERE faculty_id = :faculty_id");
            $query->execute(['faculty_id' => $faculty_id]);
        }
        return $query->fetch(PDO::FETCH_ASSOC)['total_risks'] ?? 0;
    }
}
?>
