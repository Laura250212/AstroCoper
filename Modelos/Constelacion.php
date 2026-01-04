<?php
class Constelacion {
    private $conn;
    private $table_name = "constelaciones";

    //Propiedades
    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $tipo_imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    //Leer todas las constelaciones
    public function leerTodas() {
        try {
            $query = "SELECT id, nombre, descripcion, tipo_imagen FROM " . $this->table_name . " ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error leerTodas Constelaciones: " . $e->getMessage());
            return null;
        }
    }

    //Leer una (Para editar)
    public function leerUna($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    //Obtener imagen (Para pintarla en el navegador)
    public function obtenerImagen($id) {
        try {
            $query = "SELECT imagen, tipo_imagen FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    //Crear (Admin)
    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, imagen=:imagen, tipo_imagen=:tipo_imagen";
            $stmt = $this->conn->prepare($query);

            //Limpieza
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);

            if (!empty($this->imagen)) {
                $stmt->bindParam(":imagen", $this->imagen, PDO::PARAM_LOB);
                $stmt->bindParam(":tipo_imagen", $this->tipo_imagen);
            } else {
                $null = null;
                $stmt->bindParam(":imagen", $null, PDO::PARAM_NULL);
                $stmt->bindParam(":tipo_imagen", $null, PDO::PARAM_NULL);
            }

            if ($stmt->execute()) return true;
            return false;
        } catch (PDOException $e) {
            error_log("Error crear Constelacion: " . $e->getMessage());
            return false;
        }
    }

    //Actualizar (Admin)
    public function actualizar() {
        try {
            $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion";
            
            if (!empty($this->imagen)) {
                $query .= ", imagen = :imagen, tipo_imagen = :tipo_imagen";
            }
            $query .= " WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':id', $this->id);

            if (!empty($this->imagen)) {
                $stmt->bindParam(':imagen', $this->imagen, PDO::PARAM_LOB);
                $stmt->bindParam(':tipo_imagen', $this->tipo_imagen);
            }

            if ($stmt->execute()) return true;
            return false;
        } catch (PDOException $e) {
            error_log("Error actualizar Constelacion: " . $e->getMessage());
            return false;
        }
    }

    //Borrar (Admin)
    public function borrar($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            if ($stmt->execute()) return true;
            return false;
        } catch (PDOException $e) {
            error_log("Error borrar Constelacion: " . $e->getMessage());
            return false;
        }
    }
}
?>