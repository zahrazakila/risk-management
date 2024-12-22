<?php
require_once '../includes/db.php';

class UserManager extends Database {
    private $id;
    private $username;
    private $role;
    private $faculty_id;
    private $password;

    // Pastikan koneksi dari Database diwarisi dengan memanggil parent constructor
    public function __construct() {
        parent::__construct();  // Memanggil konstruktor Database untuk koneksi
    }

    // === Getter dan Setter ===

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getFacultyId() {
        return $this->faculty_id;
    }

    public function setFacultyId($faculty_id) {
        $this->faculty_id = $faculty_id;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    // === METHOD UNTUK PROFIL PENGGUNA ===
    public function getUserProfile() {
        $query = $this->conn->prepare("
            SELECT users.id, users.username, users.role, users.last_login, users.total_logins, 
                   faculties.nama_lengkap 
            FROM users 
            LEFT JOIN faculties ON users.faculty_id = faculties.id 
            WHERE users.id = ?
        ");
        $query->execute([$this->id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    

    // === CRUD Methods ===

    public function getAllUsers() {
        $query = $this->conn->query("
            SELECT users.*, faculties.faculty_name 
            FROM users 
            LEFT JOIN faculties ON users.faculty_id = faculties.id
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFaculties() {
        $query = $this->conn->query("SELECT id, faculty_name FROM faculties");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveUser() {
        if ($this->id) {
            if (!empty($this->password)) {
                $query = $this->conn->prepare("
                    UPDATE users 
                    SET username = ?, role = ?, faculty_id = ?, password = ? 
                    WHERE id = ?
                ");
                $query->execute([$this->username, $this->role, $this->faculty_id, $this->password, $this->id]);
            } else {
                $query = $this->conn->prepare("
                    UPDATE users 
                    SET username = ?, role = ?, faculty_id = ? 
                    WHERE id = ?
                ");
                $query->execute([$this->username, $this->role, $this->faculty_id, $this->id]);
            }
        } else {
            $finalPassword = $this->password ?: password_hash('default123', PASSWORD_BCRYPT);
            $query = $this->conn->prepare("
                INSERT INTO users (username, password, role, faculty_id) 
                VALUES (?, ?, ?, ?)
            ");
            $query->execute([$this->username, $finalPassword, $this->role, $this->faculty_id]);
        }
    }

    public function deleteUser() {
        $query = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $query->execute([$this->id]);
    }
}
?>
