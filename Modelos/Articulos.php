<?php
class Articulos {
    private $conn;
    private $table_name = "productos";

    //Propiedades
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $categoria;
    public $stock;
    public $activo;
    public $imagen;
    public $tipo_imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function leerTodos() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE activo = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error leerTodos: " . $e->getMessage());
            return null;
        }
    }

    public function leerUno($id) {
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

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      SET nombre=:nombre, descripcion=:descripcion, precio=:precio, 
                          categoria=:categoria, stock=:stock, activo=1,
                          imagen=:imagen, tipo_imagen=:tipo_imagen";
            $stmt = $this->conn->prepare($query);
            
            //Limpieza
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->precio = htmlspecialchars(strip_tags($this->precio));
            $this->categoria = htmlspecialchars(strip_tags($this->categoria));
            $this->stock = htmlspecialchars(strip_tags($this->stock));
            
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":precio", $this->precio);
            $stmt->bindParam(":categoria", $this->categoria);
            $stmt->bindParam(":stock", $this->stock);
            
            if (!empty($this->imagen)) {
                $stmt->bindParam(":imagen", $this->imagen, PDO::PARAM_LOB);
                $stmt->bindParam(":tipo_imagen", $this->tipo_imagen);
            } else {
                $null = null;
                $stmt->bindParam(":imagen", $null, PDO::PARAM_NULL);
                $stmt->bindParam(":tipo_imagen", $null, PDO::PARAM_NULL);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crear producto: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar() {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                          categoria = :categoria, stock = :stock";
            
            if (!empty($this->imagen)) {
                $query .= ", imagen = :imagen, tipo_imagen = :tipo_imagen";
            }
            $query .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            //Limpieza
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->precio = htmlspecialchars(strip_tags($this->precio));
            $this->categoria = htmlspecialchars(strip_tags($this->categoria));
            $this->stock = htmlspecialchars(strip_tags($this->stock));
            //id no necesita strip_tags, pero mejor prevenir cualquier cosa
            $this->id = htmlspecialchars(strip_tags($this->id)); 
            
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':categoria', $this->categoria);
            $stmt->bindParam(':stock', $this->stock);
            $stmt->bindParam(':id', $this->id);
            
            if (!empty($this->imagen)) {
                $stmt->bindParam(':imagen', $this->imagen, PDO::PARAM_LOB);
                $stmt->bindParam(':tipo_imagen', $this->tipo_imagen);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizar producto: " . $e->getMessage());
            return false;
        }
    }

    public function borrar($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    //Tienda y carrito
    public function leerPorIds($ids) {
        try {
            if (empty($ids)) return null;

            //Generación dinámica de placeholders (?,?,?)
            $in  = str_repeat('?,', count($ids) - 1) . '?';
            
            $query = "SELECT * FROM " . $this->table_name . " WHERE id IN ($in)";
            $stmt = $this->conn->prepare($query);
            
            $i = 1;
            foreach ($ids as $id) {
                $stmt->bindValue($i++, $id);
            }
            
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error leerPorIds: " . $e->getMessage());
            return null;
        }
    }

    public function crearPedido($usuario_id, $total) {
        try {
            $query = "INSERT INTO pedidos (usuario_id, total, fecha, estado) VALUES (:uid, :total, NOW(), 'pagado')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":uid", $usuario_id);
            $stmt->bindParam(":total", $total);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error crearPedido: " . $e->getMessage());
            return false;
        }
    }

    public function agregarDetallePedido($pedido_id, $producto_id, $cantidad, $precio_unitario) {
        try {
            $query = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) 
                      VALUES (:pid, :prodid, :cant, :precio)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":pid", $pedido_id);
            $stmt->bindParam(":prodid", $producto_id);
            $stmt->bindParam(":cant", $cantidad);
            $stmt->bindParam(":precio", $precio_unitario);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error agregarDetalle: " . $e->getMessage());
            return false;
        }
    }

    //Restar stock
    public function restarStock($producto_id, $cantidad) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET stock = stock - :cant 
                      WHERE id = :id AND stock >= :cant";
                      
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cant", $cantidad);
            $stmt->bindParam(":id", $producto_id);
            
            if ($stmt->execute()) {
                //Si rowCount > 0 significa que había stock y se restó
                //Si es 0, es que la condición (stock >= cant) falló
                return $stmt->rowCount() > 0;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error restarStock: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerHistorialPedidos($usuario_id) {
        try {
            $query = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $usuario_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerDetallesDePedido($pedido_id) {
        try {
            $query = "SELECT d.*, p.nombre 
                      FROM detalles_pedido d
                      JOIN productos p ON d.producto_id = p.id
                      WHERE d.pedido_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $pedido_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerTodasLasVentas() {
        try {
            $query = "SELECT p.id, p.fecha, p.total, p.estado, u.nombre as comprador 
                      FROM pedidos p
                      JOIN usuarios u ON p.usuario_id = u.id
                      ORDER BY p.fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>