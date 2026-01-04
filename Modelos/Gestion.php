<?php
class Gestion {
    private $conn;
    private $table_name = "usuarios";

    //Constructor que recibe la conexión
    public function __construct($db) {
        $this->conn = $db;
    }

    //Obtener todos los usuarios
    public function leerUsuarios() {
        try {
            $query = "SELECT id, nombre, email, rol, fecha_registro 
                      FROM " . $this->table_name . " 
                      WHERE rol != 'admin' 
                      ORDER BY nombre ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error leerUsuarios Gestion: " . $e->getMessage());
            return null;
        }
    }

    public function crearUsuarioGestion($nombre, $email, $passwordHash) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre, email, password, rol, fecha_registro) VALUES (:nombre, :email, :pass, 'usuario', NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":pass", $passwordHash);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crearUsuarioGestion: " . $e->getMessage());
            return false;
        }
    }
    
    public function existeEmailGestion($email) {
        try {
            $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function borrarUsuario($id){
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error borrarUsuario: " . $e->getMessage());
            return false;
        }
    }

    //Obtener datos de UN usuario por su ID
    public function obtenerUsuario($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    //Actualizar usuario
    public function actualizarUsuarioGestion($id, $nombre, $email, $password = null) {
        try {
            if ($password) {
                $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, email = :email, password = :password WHERE id = :id";
            } else {
                $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, email = :email WHERE id = :id";
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":id", $id);

            if ($password) {
                //Hashear la contraseña antes de guardarla
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bindParam(":password", $hash);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarUsuarioGestion: " . $e->getMessage());
            return false;
        }
    }
}
?>