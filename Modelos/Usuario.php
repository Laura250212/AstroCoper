<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    //Constructor que recibe la conexión
    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerUsuarioPorEmail($email) {
        try {
            $query = "SELECT id, nombre, email, password, rol, tipo_imagen_perfil, activo 
                      FROM " . $this->table_name . " 
                      WHERE email = :email LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerUsuarioPorEmail: " . $e->getMessage());
            return false;
        }
    }

    public function crearUsuario($nombre, $email, $passwordHash) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre, email, password, rol, fecha_registro) VALUES (:nombre, :email, :pass, 'usuario', NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":pass", $passwordHash);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crearUsuario: " . $e->getMessage());
            return false;
        }
    }
    
    public function existeEmail($email) {
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

    //Obtener SOLO la foto
    public function obtenerFoto($id) {
        try {
            $query = "SELECT foto_perfil, tipo_imagen_perfil FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

   //Actualizar datos de texto (nombre y/o contraseña)
   public function actualizarDatos($id, $nombre, $password_hash = null) {
        try {
            if ($password_hash) {
                //Si hay contraseña nueva, actualiza nombre y pass
                $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, password = :pass WHERE id = :id";
            } else {
                //Si no, solo actualiza el nombre
                $query = "UPDATE " . $this->table_name . " SET nombre = :nombre WHERE id = :id";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":id", $id);
            
            if ($password_hash) { 
                $stmt->bindParam(":pass", $password_hash); 
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarDatos: " . $e->getMessage());
            return false;
        }
    }

    //Actualizar SOLO la foto
    public function actualizarFoto($id, $imagenBinaria, $tipoImagen) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET foto_perfil = :foto, tipo_imagen_perfil = :tipo 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":foto", $imagenBinaria, PDO::PARAM_LOB);
            $stmt->bindParam(":tipo", $tipoImagen);
            $stmt->bindParam(":id", $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarFoto: " . $e->getMessage());
            return false;
        }
    }

    //Gestión del carrito
    public function guardarCarritoEnBD($usuario_id, $carritoSession) {
        try {
            $this->conn->beginTransaction();

            //Borra lo viejo
            $sqlDelete = "DELETE FROM carritos_guardados WHERE usuario_id = :uid";
            $stmtDel = $this->conn->prepare($sqlDelete);
            $stmtDel->bindParam(":uid", $usuario_id);
            $stmtDel->execute();

            //Insertar lo nuevo
            if (!empty($carritoSession)) {
                $sqlInsert = "INSERT INTO carritos_guardados (usuario_id, producto_id, cantidad) VALUES (:uid, :pid, :cant)";
                $stmtInst = $this->conn->prepare($sqlInsert);

                foreach ($carritoSession as $id_producto => $cantidad) {
                    $stmtInst->bindParam(":uid", $usuario_id);
                    $stmtInst->bindParam(":pid", $id_producto);
                    $stmtInst->bindParam(":cant", $cantidad);
                    $stmtInst->execute();
                }
            }

            $this->conn->commit();

        } catch (Exception $e) {
            //Si algo falla, deshacer los cambios (Rollback) para no perder datos
            $this->conn->rollBack();
            error_log("Error guardando carrito: " . $e->getMessage());
        }
    }

    //Recuperar el carrito al entrar
    public function recuperarCarritoDeBD($usuario_id) {
        try {
            $sql = "SELECT producto_id, cantidad FROM carritos_guardados WHERE usuario_id = :uid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":uid", $usuario_id);
            $stmt->execute();
            
            $carritoRecuperado = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //Reconstruir el array
                $carritoRecuperado[$row['producto_id']] = $row['cantidad'];
            }
            return $carritoRecuperado;
        } catch (PDOException $e) {
            error_log("Error recuperando carrito: " . $e->getMessage());
            return [];
        }
    }
}
?>