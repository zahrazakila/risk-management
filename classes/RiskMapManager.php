<?php
class RiskMapManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getRisks($role, $faculty_id = null) {
        if ($role === 'admin') {
            $query = $this->db->query("
                SELECT 
                    risk_code, 
                    inherent_likehood AS inherent_likelihood, inherent_impact, inherent_risk_level,
                    residual_likehood AS residual_likelihood, residual_impact, residual_risk_level,
                    after_mitigation_likehood AS after_mitigation_likelihood, after_mitigation_impact, after_mitigation_risk_level
                FROM mitigations
            ");
        } else {
            $query = $this->db->prepare("
                SELECT 
                    risk_code, 
                    inherent_likehood AS inherent_likelihood, inherent_impact, inherent_risk_level,
                    residual_likehood AS residual_likelihood, residual_impact, residual_risk_level,
                    after_mitigation_likehood AS after_mitigation_likelihood, after_mitigation_impact, after_mitigation_risk_level
                FROM mitigations
                WHERE faculty_id = :faculty_id
            ");
            $query->execute(['faculty_id' => $faculty_id]);
        }

        return $this->formatRisks($query->fetchAll());
    }

    private function formatRisks($rows) {
        $risks = [];
        foreach ($rows as $row) {
            $risks[] = [
                'type' => 'Inherent',
                'risk_code' => $row['risk_code'] . 'A',
                'likelihood' => $row['inherent_likelihood'],
                'impact' => $row['inherent_impact'],
                'risk_level' => $row['inherent_likelihood'] * $row['inherent_impact'],
            ];
            $risks[] = [
                'type' => 'Residual',
                'risk_code' => $row['risk_code'] . 'B',
                'likelihood' => $row['residual_likelihood'],
                'impact' => $row['residual_impact'],
                'risk_level' => $row['residual_likelihood'] * $row['residual_impact'],
            ];
            $risks[] = [
                'type' => 'After Mitigation',
                'risk_code' => $row['risk_code'] . 'C',
                'likelihood' => $row['after_mitigation_likelihood'],
                'impact' => $row['after_mitigation_impact'],
                'risk_level' => $row['after_mitigation_likelihood'] * $row['after_mitigation_impact'],
            ];
        }
        return $risks;
    }
}
?>
