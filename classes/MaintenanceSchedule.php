<?php
class MaintenanceSchedule {
    private $unitId;
    private $technicianName;
    private $maintenanceDate;
    private $maintenanceType;
    private $description;

    public function __construct($unitId, $technicianName, $maintenanceDate, $maintenanceType, $description) {
        $this->unitId = $unitId;
        $this->technicianName = $technicianName;
        $this->maintenanceDate = $maintenanceDate;
        $this->maintenanceType = $maintenanceType;
        $this->description = $description;
    }

    // Getter dan Setter
    public function getUnitId() {
        return $this->unitId;
    }

    public function setUnitId($unitId) {
        $this->unitId = $unitId;
    }

    public function getTechnicianName() {
        return $this->technicianName;
    }

    public function setTechnicianName($technicianName) {
        $this->technicianName = $technicianName;
    }

    public function getMaintenanceDate() {
        return $this->maintenanceDate;
    }

    public function setMaintenanceDate($maintenanceDate) {
        $this->maintenanceDate = $maintenanceDate;
    }

    public function getMaintenanceType() {
        return $this->maintenanceType;
    }

    public function setMaintenanceType($maintenanceType) {
        $this->maintenanceType = $maintenanceType;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    // Simpan ke database
    public function saveToDatabase($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO maintenance_schedules (unit_id, technician_name, maintenance_date, maintenance_type, description) VALUES (:unit_id, :technician_name, :maintenance_date, :maintenance_type, :description)");
            return $stmt->execute([
                ':unit_id' => $this->unitId,
                ':technician_name' => $this->technicianName,
                ':maintenance_date' => $this->maintenanceDate,
                ':maintenance_type' => $this->maintenanceType,
                ':description' => $this->description
            ]);
        } catch (PDOException $e) {
            error_log("Schedule Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public static function markAsComplete($pdo, $id) {
        try {
            $stmt = $pdo->prepare("UPDATE maintenance_schedules SET status = 'complete' WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Mark Complete Error: " . $e->getMessage());
            return false;
        }
    }

    public static function deleteSchedule($pdo, $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM maintenance_schedules WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Delete Schedule Error: " . $e->getMessage());
            return false;
        }
    }

    public static function getUpcomingSchedules(PDO $pdo, $limit = null) {

        $today = date('Y-m-d');

        $sql = "
            SELECT ms.*, au.unit_code
            FROM maintenance_schedules ms
            JOIN ac_units au ON ms.unit_id = au.id
            WHERE ms.maintenance_date >= :today AND (ms.status IS NULL OR ms.status != 'complete')
            ORDER BY ms.maintenance_date ASC
        ";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':today', $today);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCompletedLogs(PDO $pdo): array {

        $stmt = $pdo->prepare("
            SELECT ms.*, au.unit_code 
            FROM maintenance_schedules ms
            JOIN ac_units au ON ms.unit_id = au.id
            WHERE ms.status = 'complete'
            ORDER BY ms.maintenance_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>
