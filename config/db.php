<?php
class Database {
    private $host = "localhost";
    private $db_name = "garage_group5";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Kết nối PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );

            // Thiết lập chế độ lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {
            echo "Lỗi kết nối: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
