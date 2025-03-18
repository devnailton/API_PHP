<?php
class Database {
    private $host = "localhost";
    private $db_name = "escola";      // Nome do banco de dados criado
    private $username = "root"; // Substitua pelo seu usuário do MySQL
    private $password = "Nlt@@123";   // Substitua pela sua senha do MySQL
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>