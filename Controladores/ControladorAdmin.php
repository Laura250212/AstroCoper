<?php
/**
 * Controlador exclusivo de funciones de administrador
 */

class ControladorAdmin {

    private $productoModel;
    private $constelacionModel;
    private $gestionModel;
    private $db;

    public function __construct() {
        //Validación de Rol de Administrador
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            header("Location: " . RUTA_BASE . "/index.php?action=error&mensaje=Acceso Restringido.");
            exit();
        }

        //Conexiones
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->productoModel = new Articulos($this->db);
        $this->constelacionModel = new Constelacion($this->db); 
        $this->gestionModel = new Gestion($this->db); 
    }


    private function verificarToken() {
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verificarTokenCSRF($token)) {
            die("Error de seguridad: Token inválido o expirado. Recarga la página.");
        }
    }

    //Panel de administrador
    public function panelAdmin() {
        $stmtProd = $this->productoModel->leerTodos();
        $productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

        $stmtConst = $this->constelacionModel->leerTodas();
        $constelaciones = $stmtConst->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . "/../Vistas/Admin/panelAdmin.php";
    }

    //Productos
    public function vistaCrear() { 
        require_once __DIR__ . "/../Vistas/Admin/crearProducto.php"; 
    }

    public function crearProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verificarToken();

            $this->productoModel->nombre = $_POST['nombre'];
            $this->productoModel->descripcion = $_POST['descripcion'];
            $this->productoModel->precio = $_POST['precio'];
            $this->productoModel->categoria = $_POST['categoria'];
            $this->productoModel->stock = $_POST['stock'];
            $this->productoModel->activo = 1; 

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $this->productoModel->tipo_imagen = $_FILES['imagen']['type'];
                $this->productoModel->imagen = file_get_contents($_FILES['imagen']['tmp_name']);
            } else {
                $this->productoModel->imagen = null;
                $this->productoModel->tipo_imagen = null;
            }

            if ($this->productoModel->crear()) { 
                header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin&msg=creado");
                exit();
            }
        }
    }

    public function vistaEditar() {
        if (isset($_GET['id'])) {
            $producto = $this->productoModel->leerUno($_GET['id']);
            if ($producto) { 
                require_once __DIR__ . "/../Vistas/Admin/editarProducto.php"; 
            }
        }
    }

    public function actualizarProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verificarToken();

            $this->productoModel->id = $_POST['id'];
            $this->productoModel->nombre = $_POST['nombre'];
            $this->productoModel->descripcion = $_POST['descripcion'];
            $this->productoModel->precio = $_POST['precio'];
            $this->productoModel->categoria = $_POST['categoria'];
            $this->productoModel->stock = $_POST['stock'];

            //Solo se actualiza la imagen si el usuario ha subido una nueva
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $this->productoModel->tipo_imagen = $_FILES['imagen']['type'];
                $this->productoModel->imagen = file_get_contents($_FILES['imagen']['tmp_name']);
            }

            if ($this->productoModel->actualizar()) { 
                header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin&msg=editado");
                exit();
            }
        }
    }

    public function eliminarProducto() {
        if (isset($_GET['id'])) { 
            $this->productoModel->borrar($_GET['id']); 
        }
        header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin&msg=borrado");
        exit();
    }

    public function verVentas() {
        $ventas = $this->productoModel->obtenerTodasLasVentas();
        require_once __DIR__ . "/../Vistas/Admin/ventas.php";
    }

   //Constelaciones
    public function crearConstelacion() { 
        require_once __DIR__ . "/../Vistas/Admin/crearConstelacion.php";
    }

    public function guardarConstelacion() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verificarToken();

            $this->constelacionModel->nombre = $_POST['nombre'];
            $this->constelacionModel->descripcion = $_POST['descripcion'];

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $this->constelacionModel->tipo_imagen = $_FILES['imagen']['type'];
                $this->constelacionModel->imagen = file_get_contents($_FILES['imagen']['tmp_name']);
            } else {
                $this->constelacionModel->imagen = null;
                $this->constelacionModel->tipo_imagen = null;
            }

            if ($this->constelacionModel->crear()) {
                header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin&msg=creado");
                exit();
            }
        }
    }

    public function vistaEditarConstelacion() {
        if (isset($_GET['id'])) {
            $constelacion = $this->constelacionModel->leerUna($_GET['id']);
            if ($constelacion) {
                require_once __DIR__ . "/../Vistas/Admin/editarConstelacion.php";
            } else {
                header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin");
                exit();
            }
        }
    }

    public function actualizarConstelacion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verificarToken();

            $this->constelacionModel->id = $_POST['id'];
            $this->constelacionModel->nombre = $_POST['nombre'];
            $this->constelacionModel->descripcion = $_POST['descripcion'];

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $this->constelacionModel->tipo_imagen = $_FILES['imagen']['type'];
                $this->constelacionModel->imagen = file_get_contents($_FILES['imagen']['tmp_name']);
            } 

            if ($this->constelacionModel->actualizar()) {
                header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin&msg=editado");
                exit();
            }
        }
    }

    public function borrarConstelacion() {
        if (isset($_GET['id'])) {
            $this->constelacionModel->borrar($_GET['id']);
        }
        header("Location: " . RUTA_BASE . "/index.php?action=panelAdmin&msg=borrado");
        exit();
    }

    //Usuarios
    public function gestionUsuarios() {
        $stmtUsers = $this->gestionModel->leerUsuarios();
        $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . "/../Vistas/Admin/gestionUsuarios.php";
    }

    public function crearUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verificarToken();

            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (!$this->gestionModel->existeEmailGestion($email)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                if ($this->gestionModel->crearUsuarioGestion($nombre, $email, $hash)) {
                    header("Location: " . RUTA_BASE . "/index.php?action=gestionUsuarios&msg=creado");
                    exit();
                } else {
                    $mensaje_error = "Error en la base de datos.";
                    require_once __DIR__ . "/../Vistas/Admin/crearUsuario.php";
                }
            } else {
                $mensaje_error = "Este correo ya está registrado.";
                require_once __DIR__ . "/../Vistas/Admin/crearUsuario.php";
            }
        } else {
            require_once __DIR__ . "/../Vistas/Admin/crearUsuario.php";
        }
    }

    public function borrarUsuario(){
        if (isset($_GET['id'])) {
            $this->gestionModel->borrarUsuario($_GET['id']);
            header("Location: " . RUTA_BASE . "/index.php?action=gestionUsuarios&msg=borrado");
            exit();
        }
    }

    public function vistaEditarUsuario() {
        if (isset($_GET['id'])) {
            $usuario = $this->gestionModel->obtenerUsuario($_GET['id']);
            if ($usuario) {
                require_once __DIR__ . "/../Vistas/Admin/editarUsuario.php";
            } else {
                header("Location: " . RUTA_BASE . "/index.php?action=gestionUsuarios&error=noencontrado");
                exit();
            }
        }
    }

    public function actualizarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verificarToken();

            $id = $_POST['id'];
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            if ($this->gestionModel->actualizarUsuarioGestion($id, $nombre, $email, $password)) {
                header("Location: " . RUTA_BASE . "/index.php?action=gestionUsuarios&msg=editado");
                exit();
            } else {
                $usuario = ['id' => $id, 'nombre' => $nombre, 'email' => $email];
                $mensaje_error = "No se pudo actualizar.";
                require_once __DIR__ . "/../Vistas/Admin/editarUsuario.php";
            }
        }
    }
}
?>