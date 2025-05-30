<?php
class MaintenanceLog {
    private $unitId;
    private $maintenanceDate;
    private $maintenanceType;
    private $technicianName;
    private $workPerformed;
    private $photoPath;

    public function __construct($unitId, $maintenanceDate, $maintenanceType, $technicianName, $workPerformed, $photoPath = null) {
        $this->unitId = $unitId;
        $this->maintenanceDate = $maintenanceDate;
        $this->maintenanceType = $maintenanceType;
        $this->technicianName = $technicianName;
        $this->workPerformed = $workPerformed;
        $this->photoPath = $photoPath;
    }

    // Getter and Setter

    // UNIT
    public function getUnitId() { 
        return $this->unitId; 
    }
    public function setUnitId($unitId) { 
        $this->unitId = $unitId; 
    }

    // Date
    public function getMaintenanceDate() { 
        return $this->maintenanceDate; 
    }
    public function setMaintenanceDate($date) { 
        $this->maintenanceDate = $date; 
    }

    // Type
    public function getMaintenanceType() { 
        return $this->maintenanceType; 
    }
    public function setMaintenanceType($type) { 
        $this->maintenanceType = $type; 
    }

    // Technician
    public function getTechnicianName() { 
        return $this->technicianName; 
    }
    public function setTechnicianName($name) { 
        $this->technicianName = $name; 
    }

    // Detail teks
    public function getWorkPerformed() { return $this->workPerformed; 
    }
    public function setWorkPerformed($work) { $this->workPerformed = $work; 
    }

    // Photo
    public function getPhotoPath() { 
        return $this->photoPath; 
    }
    public function setPhotoPath($path) { 
        $this->photoPath = $path; 
    }

    public function saveToDatabase($conn) {
    try {
        $stmt = $conn->prepare("INSERT INTO maintenance_logs (unit_id, maintenance_date, maintenance_type, technician_name, work_performed, photo_path) VALUES (:unit_id, :maintenance_date, :maintenance_type, :technician_name, :work_performed, :photo_path)");

        return $stmt->execute([
            ':unit_id' => $this->unitId,
            ':maintenance_date' => $this->maintenanceDate,
            ':maintenance_type' => $this->maintenanceType,
            ':technician_name' => $this->technicianName,
            ':work_performed' => $this->workPerformed,
            ':photo_path' => $this->photoPath,
        ]);
        } catch (PDOException $e) {
            error_log("Database Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public static function getMaintenanceLogsWithUnitCode(PDO $conn) {
        $sql = "SELECT ml.*, au.unit_code
                FROM maintenance_logs ml
                JOIN ac_units au ON ml.unit_id = au.id
                ORDER BY ml.maintenance_date DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLogsByTechnician($pdo, $technicianName) {
    $stmt = $pdo->prepare("
        SELECT ml.*, au.unit_code
        FROM maintenance_logs ml
        JOIN ac_units au ON ml.unit_id = au.id
        WHERE ml.technician_name = ?
        ORDER BY ml.maintenance_date DESC
    ");
        $stmt->execute([$technicianName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
