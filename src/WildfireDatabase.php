<?php

namespace src;

use PDO;

class WildfireDatabase
{
    private PDO $db;

    public function __construct() {
        $this->db = new PDO('sqlite:../database/FPA_FOD_20170508.sqlite');
    }

    public function getForestCount($forestName): int {
        $countStmt = $this->db->prepare("SELECT COUNT(DISTINCT NWCG_REPORTING_UNIT_NAME) FROM fires WHERE NWCG_REPORTING_UNIT_NAME LIKE :query");
        $countStmt->bindParam(':query', $forestName);
        $countStmt->execute();
        return $countStmt->fetchColumn();
    }

    public function getForests($forestName, $start, $limit): array {
        $stmt = $this->db->prepare("SELECT DISTINCT NWCG_REPORTING_UNIT_NAME FROM fires WHERE NWCG_REPORTING_UNIT_NAME LIKE :query LIMIT :start, :limit");
        $stmt->bindParam(':query', $forestName);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $forests = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $forests[] = new Forest($row['NWCG_REPORTING_UNIT_NAME']);
        }

        return $forests;
    }
}