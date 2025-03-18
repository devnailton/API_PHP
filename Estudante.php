<?php
require_once "Estudante.php";

class EstudanteGraduacao extends Estudante {
    private $conn;
    private $table_name = "estudantes_graduacao";
    
    public $curso;

    public function __construct($db) {
        parent::__construct($db);
        $this->conn = $db;
    }

    // Método para criar um novo estudante de graduação
    public function create() {
        // Primeiro, cria o registro na tabela pai (estudantes)
        if (parent::create()) {
            // Obtém o ID gerado
            $this->id = $this->conn->lastInsertId();
            
            // Insere na tabela estudantes_graduacao
            $query = "INSERT INTO " . $this->table_name . " SET id = :id, curso = :curso";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":curso", $this->curso);

            if($stmt->execute()) {
                return true;
            }
            return false;
        }
        return false;
    }

    // Método para ler todos os estudantes de graduação
    public function readAll() {
        $query = "SELECT e.id, e.nome, e.idade, g.curso 
                 FROM estudantes e 
                 INNER JOIN " . $this->table_name . " g ON e.id = g.id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para ler um único estudante de graduação
    public function readOne() {
        if (parent::readOne()) {
            $query = "SELECT curso FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row) {
                $this->curso = $row['curso'];
                return true;
            }
        }
        return false;
    }

    // Método para atualizar os dados de um estudante de graduação
    public function update() {
        // Primeiro atualiza a tabela pai
        if (parent::update()) {
            // Depois atualiza a tabela de graduação
            $query = "UPDATE " . $this->table_name . " SET curso = :curso WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":curso", $this->curso);
            $stmt->bindParam(":id", $this->id);

            if($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    // Método para deletar um estudante de graduação
    public function delete() {
        // A exclusão da tabela estudantes_graduacao acontecerá automaticamente
        // devido à restrição ON DELETE CASCADE
        return parent::delete();
    }
}
?>