<?php
class ACUnit {
    private $unitCode;
    private $location;
    private $status;

    public function __construct($unitCode, $location, $status = 'Active') {
        $this->unitCode = $unitCode;
        $this->location = $location;
        $this->status = $status;
    }

    public function save(PDO $conn) {
        $stmt = $conn->prepare("INSERT INTO ac_units (unit_code, location, status) VALUES (?, ?, ?)");
        return $stmt->execute([$this->unitCode, $this->location, $this->status]);
    }

    public static function delete(PDO $conn, $id) {
        $stmt = $conn->prepare("DELETE FROM ac_units WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getAll(PDO $conn) {
        $stmt = $conn->query("SELECT * FROM ac_units ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cek Unit AC
    public static function exists(PDO $conn, $unitCode) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM ac_units WHERE unit_code = ?");
        $stmt->execute([$unitCode]);
        return $stmt->fetchColumn() > 0;
    }

}
?>
