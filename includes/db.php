<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'risk_management';
    private $username = 'root';
    private $password = '';
    protected $conn;

    // Constructor untuk membuka koneksi
    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi ke database gagal: " . $e->getMessage());
        }
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql); // Delegasikan ke PDO
    }

    public function query($sql) {
        return $this->conn->query($sql); // Method query untuk SELECT langsung
    }

    // Getter untuk mendapatkan koneksi
    public function getConnection() {
        return $this->conn;
    }

    // Destructor untuk menutup koneksi
    public function __destruct() {
        $this->conn = null;
    }
}
?>
