<?php

namespace src;

use PDO;

class WildfireDatabase
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO('sqlite:../database/FPA_FOD_20170508.sqlite');
    }

    public function getForestCount($forestName): int
    {
        $countStmt = $this->db->prepare("SELECT COUNT(DISTINCT NWCG_REPORTING_UNIT_NAME) FROM fires WHERE NWCG_REPORTING_UNIT_NAME LIKE :query");
        $countStmt->bindParam(':query', $forestName);
        $countStmt->execute();
        return $countStmt->fetchColumn();
    }

    public function getForests($forestName, $start, $limit): array
    {
        $forests = [];

        $stmt = $this->db->prepare("SELECT DISTINCT NWCG_REPORTING_UNIT_NAME FROM fires WHERE NWCG_REPORTING_UNIT_NAME LIKE :query LIMIT :start, :limit");
        $stmt->bindParam(':query', $forestName);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $forests[] = new Forest($row['NWCG_REPORTING_UNIT_NAME']);
        }

        return $forests;
    }

    public function getFiresInForestCount($forestName): int
    {
        $totalStmt = $this->db->prepare("SELECT COUNT(*) FROM fires WHERE NWCG_REPORTING_UNIT_NAME = :query");
        $totalStmt->bindParam(':query', $forestName);
        $totalStmt->execute();
        return $totalStmt->fetchColumn();
    }

    public function getFiresInForest($forestName, $start, $limit): array
    {
        $fires = [];

        $stmt = $this->db->prepare("SELECT FPA_ID, FIRE_NAME, DISCOVERY_DATE, DISCOVERY_TIME, STAT_CAUSE_DESCR FROM fires WHERE NWCG_REPORTING_UNIT_NAME = :query ORDER BY DISCOVERY_DATE DESC LIMIT :start, :limit");
        $stmt->bindParam(':query', $forestName);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fires[] = new Fire($row['FPA_ID'], $row['FIRE_NAME'], $row['DISCOVERY_DATE'], $row['DISCOVERY_TIME'], $row['STAT_CAUSE_DESCR']);
        }

        return $fires;
    }
}