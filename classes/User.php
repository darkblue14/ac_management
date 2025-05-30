<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/BaseModel.php';

class User extends BaseModel {
    private $id;
    private $fullname;
    private $email;
    private $password;

    public function __construct(Database $db) {
        $this->conn = $db->connect(); // Ambil koneksi dari objek Database
    }

    // Setters
    public function setFullname($fullname) {
        $this->fullname = htmlspecialchars(strip_tags($fullname));
    }

    public function setEmail($email) {
        $this->email = htmlspecialchars(strip_tags($email));
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT); // Enkripsi password
    }

    // Getters (jika dibutuhkan)
    public function getFullname() {
        return $this->fullname;
    }

    public function getEmail() {
        return $this->email;
    }


    public function getPassword() {
        return $this->password;
    }

    // Mengecek apakah email sudah terdaftar
    public function isEmailRegistered($email) {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // login
    public function login($email, $password) {
    $query = "SELECT id, fullname, email, password FROM users WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Simpan informasi pengguna ke sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_fullname'] = $user['fullname'];
                return true;
            }
        }
        return false;
    }

    // Menyimpan user baru
    public function save() {
        $query = "INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        return $stmt->execute();
    }

    // Hapus AKun
    public function deleteById($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Akun technician Kecuali admin
    public function getAllTechniciansExcludeAdmins(array $adminEmails) {
        // Buat placeholder sebanyak jumlah email admin
        $placeholders = implode(',', array_fill(0, count($adminEmails), '?'));

        $query = "SELECT fullname FROM users WHERE email NOT IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($adminEmails);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
