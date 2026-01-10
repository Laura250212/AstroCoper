<?php
/**
 * Controlador de la Tienda
 */

class ControladorTienda {
    private $productoModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->productoModel = new Articulos($this->db);
        
        //Inicia el array del carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    //Muestra la tienda
    public function mostrarTienda() {
        $stmt = $this->productoModel->leerTodos();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . "/../Vistas/Articulos/tienda.php";
    }

    public function verImagen() {
        //Limpiar buffer
        if (ob_get_length()) ob_clean();

        if (isset($_GET['id'])) {
            $row = $this->productoModel->obtenerImagen($_GET['id']);
            
            if ($row && !empty($row['imagen'])) {
                header("Content-Type: " . $row['tipo_imagen']);
                echo $row['imagen'];
                exit(); 
            }
        }
        
        $rutaImagenDefecto = __DIR__ . '/../IMGS/productoDefecto.png';
        
        if (file_exists($rutaImagenDefecto)) {
            header("Content-Type: image/png");
            readfile($rutaImagenDefecto);
        }
        
        exit();
    }

    //Añade un producto al carrito
    public function agregarAlCarrito() {
        // 1. Verificar Login
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . RUTA_BASE . "/index.php?action=verLogin");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
            $token_recibido = $_POST['csrf_token'] ?? '';

            if (!Security::verificarTokenCSRF($token_recibido)) {
                die("Error de seguridad: Token inválido o expirado. Por favor, recarga la página.");
            }

            $id = $_POST['producto_id'];
            
            //Lógica de adición
            if (isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id]++;
            } else {
                $_SESSION['carrito'][$id] = 1;
            }
            
            header("Location: " . RUTA_BASE . "/index.php?action=verTienda&msg=added"); 
            exit();
        }
    }

    //Muestra el carrito
    public function verCarrito() {
        $productos_carrito = [];
        $total = 0;

        if (!empty($_SESSION['carrito'])) {
            $ids = array_keys($_SESSION['carrito']);
            $stmt = $this->productoModel->leerPorIds($ids);
            $datos_bd = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($datos_bd as $prod) {
                $id = $prod['id'];
                $cantidad = $_SESSION['carrito'][$id];
                $subtotal = $prod['precio'] * $cantidad;
                
                $productos_carrito[] = [
                    'info' => $prod,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }
        require_once __DIR__ . "/../Vistas/Articulos/carrito.php";
    }

    //Eliminar item del carrito
    public function eliminarDelCarrito() {
        if (isset($_GET['id'])) {
            unset($_SESSION['carrito'][$_GET['id']]);
        }
        header("Location: " . RUTA_BASE . "/index.php?action=verCarrito");
        exit();
    }
    
    //Vaciar carrito
    public function vaciarCarrito() {
        unset($_SESSION['carrito']);
        header("Location: " . RUTA_BASE . "/index.php?action=verCarrito");
        exit();
    }

    //Procesar pedido
    public function procesarPedido() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . RUTA_BASE . "/index.php?action=verLogin");
            exit();
        }

        if (empty($_SESSION['carrito'])) {
            header("Location: " . RUTA_BASE . "/index.php?action=verTienda");
            exit();
        }

        $ids = array_keys($_SESSION['carrito']);
        $stmt = $this->productoModel->leerPorIds($ids);
        $productos_bd = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_pedido = 0;
        foreach ($productos_bd as $prod) {
            $cantidad = $_SESSION['carrito'][$prod['id']];
            $total_pedido += $prod['precio'] * $cantidad;
        }

        try {
            //Transacción
            $this->db->beginTransaction();

            //Crear Pedido
            $pedido_id = $this->productoModel->crearPedido($_SESSION['usuario_id'], $total_pedido, 'pagado');

            if (!$pedido_id) {
                throw new Exception("Error al inicializar el pedido.");
            }

            //Procesar productos y resetear stock
            foreach ($productos_bd as $prod) {
                $cantidad = $_SESSION['carrito'][$prod['id']];
                $restaExitosa = $this->productoModel->restarStock($prod['id'], $cantidad);

                if (!$restaExitosa) {
                    //En caso de stock insuficiente
                    throw new Exception("Stock insuficiente para: " . $prod['nombre']);
                }

                $this->productoModel->agregarDetallePedido($pedido_id, $prod['id'], $cantidad, $prod['precio']);
            }

            //Guardar los cambios en la BD
            $this->db->commit();

            unset($_SESSION['carrito']);
            header("Location: " . RUTA_BASE . "/index.php?action=pedidoConfirmado&id=" . $pedido_id);
            exit();

        } catch (Exception $e) {
            //En caso de error se deshace todo lo hecho en este intento (Rollback)
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            //El usuario vuelve al carrito
            echo "<script>
                alert('Error al procesar el pedido: " . addslashes($e->getMessage()) . "'); 
                window.location.href='" . RUTA_BASE . "/index.php?action=verCarrito';
            </script>";
            exit();
        }
    }

    //Vista de confirmación
    public function pedidoConfirmado() {
        if (isset($_GET['id'])) {
            $id_pedido = $_GET['id'];
            require_once __DIR__ . "/../Vistas/Articulos/confirmacion.php";
        }
    }

    //Historial
    public function misCompras() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . RUTA_BASE . "/index.php?action=verLogin");
            exit();
        }

        $pedidos = $this->productoModel->obtenerHistorialPedidos($_SESSION['usuario_id']);
        $detalles = [];
        foreach ($pedidos as $p) {
            $detalles[$p['id']] = $this->productoModel->obtenerDetallesDePedido($p['id']);
        }
        require_once __DIR__ . "/../Vistas/Articulos/misCompras.php";
    }
}
?>