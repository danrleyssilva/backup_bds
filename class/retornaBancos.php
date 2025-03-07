<?php
date_default_timezone_set('America/Manaus');

class retornaBancos {
    private $host = "localhost";
    private $user = "root"; 
    private $pass = ""; 
    private $conn; 

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass);

        if ($this->conn->connect_error) {
            die("Erro na conexão: " . $this->conn->connect_error);
        }
    }

    public function getBancos() {
        $sql = "
            SELECT 
                table_schema AS 'Database', 
                SUM(data_length + index_length) / 1024 / 1024 AS 'size'
            FROM 
                information_schema.tables
            GROUP BY 
                table_schema";
        $result = $this->conn->query($sql);
        $bancos = [];
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bancos[] = [
                    'Database' => $row['Database'],
                    'size' => round($row['size'], 2)
                ];
            }
        }
        return $bancos;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

// Exemplo de uso da classe

