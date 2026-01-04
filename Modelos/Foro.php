<?php
class Foro {
    private $conn;
    private $table = "foro";

    public function __construct($db) {
        $this->conn = $db;
    }

    //Obtener todos los mensajes
    public function obtenerMensajes() {
        try {
            $query = "SELECT f.*, u.nombre, u.rol 
                      FROM " . $this->table . " f
                      JOIN usuarios u ON f.usuario_id = u.id
                      ORDER BY f.fecha DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerMensajes Foro: " . $e->getMessage());
            return []; //Devuelve array vacío para que no rompa el foreach de la vista
        }
    }

    //Publicar mensaje
    public function publicar($usuario_id, $mensaje) {
        try {
            $query = "INSERT INTO " . $this->table . " (usuario_id, mensaje) VALUES (:uid, :msg)";
            $stmt = $this->conn->prepare($query);
            //Limpiar los datos introducidos
            $mensaje = htmlspecialchars(strip_tags($mensaje));
            
            $stmt->bindParam(":uid", $usuario_id);
            $stmt->bindParam(":msg", $mensaje);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error publicar mensaje: " . $e->getMessage());
            return false;
        }
    }

    //Borrar mensaje (Solo Admin)
    public function borrar($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error borrar mensaje: " . $e->getMessage());
            return false;
        }
    }
}
?>