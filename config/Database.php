<?php
class Database {
    // private $host = "localhost";
    // private $dbname = "ac_management";
    // private $username = "root";
    // private $password = "";
    // private $conn;
    private $host = "sql311.infinityfree.com";
    private $dbname = "if0_39116059_ac_management";
    private $username = "if0_39116059";
    private $password = "TugasUASKBP";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>
