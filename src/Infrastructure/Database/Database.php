<?php
namespace App\Infrastructure\Database;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $db_name = 'inventario_compras';
    private $username = 'root';  // Cambia según tu configuración
    private $password = '';      // Cambia según tu configuración
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn;
    }
}
