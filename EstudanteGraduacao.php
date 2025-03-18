<?php
class EstudanteGraduacao extends Estudante {
    private $table_name = "estudantes_graduacao";

    public $curso;

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET id = :id, curso = :curso";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":curso", $this->curso);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET curso = :curso WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":curso", $this->curso);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>