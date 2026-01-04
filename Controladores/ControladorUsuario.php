<?php
/**
 * Controlador de Usuarios
 * Gestiona Login, Registro, Perfil y Sesiones.
 */

class ControladorUsuario {

    private $usuarioModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuarioModel = new Usuario($this->db);
    }

    //Redirecciones a las vistas
    public function VLogin() {
        require_once __DIR__ . '/../Vistas/Usuario/login.php';
    }

    public function VRegister() {
        require_once __DIR__ . '/../Vistas/Usuario/register.php';
    }

    //Inicio de sesion
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Token
            $token_recibido = $_POST['csrf_token'] ?? '';
            
            if (!Security::verificarTokenCSRF($token_recibido)) {
                die("Error de Seguridad: Token inválido o expirado. Recarga la página.");
            }

            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $usuario = $this->usuarioModel->obtenerUsuarioPorEmail($email);

            if ($usuario && password_verify($password, $usuario['password'])) {
                
                //Regenerar ID de sesión para evitar robo de sesión
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['usuario_foto'] = !empty($usuario['foto_perfil']); 

                //Recuperar el carrito persistente, si el usuario tenía cosas en la BD, las traemos
                $carritoBD = $this->usuarioModel->recuperarCarritoDeBD($usuario['id']);
                
                if (!empty($carritoBD)) {
                    if (!isset($_SESSION['carrito'])) {
                        $_SESSION['carrito'] = [];
                    }
                    //Fusiona Carrito Sesión + Carrito BD
                    //Mantiene las claves (IDs) y no duplica si ya existen en el primero
                    $_SESSION['carrito'] = $_SESSION['carrito'] + $carritoBD;
                }

                header("Location: " . RUTA_BASE . "/index.php?action=mostrarPrincipal");
                exit();
            } else {
                $mensaje_error = "Usuario o contraseña incorrectos.";
                require_once __DIR__ . '/../Vistas/Usuario/login.php';
            }
        } else {
            $this->VLogin();
        }
    }

    //Registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token_recibido = $_POST['csrf_token'] ?? '';
            if (!Security::verificarTokenCSRF($token_recibido)) {
                die("Acceso denegado: Token de seguridad inválido.");
            }

            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (!$this->usuarioModel->existeEmail($email)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                if ($this->usuarioModel->crearUsuario($nombre, $email, $hash)) {
                    header("Location: " . RUTA_BASE . "/index.php?action=verLogin&msg=registrado");
                    exit();
                }
            } else {
                $mensaje_error = "Este correo ya está registrado. Prueba con otro.";
                require_once __DIR__ . '/../Vistas/Usuario/register.php';
            }
        } else {
            $this->VRegister();
        }
    }

    //Logout guardando el carrito
    public function logout() {
        if (isset($_SESSION['usuario_id']) && isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            $this->usuarioModel->guardarCarritoEnBD($_SESSION['usuario_id'], $_SESSION['carrito']);
        }

        //Destruir sesión completamente
        session_unset();
        session_destroy();
        
        header("Location: " . RUTA_BASE . "/index.php?action=mostrarPrincipal");
        exit();
    }

    public function verPerfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . RUTA_BASE . "/index.php?action=verLogin");
            exit();
        }

        require_once __DIR__ . "/../Vistas/Usuario/perfil.php";
    }

   //Cambios en el perfil
   public function guardarPerfil() {
        //Verificar Login
        if (!isset($_SESSION['usuario_id'])) { 
            header("Location: " . RUTA_BASE . "/index.php?action=verLogin");
            exit(); 
        }

        //Verificar Método y Token
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token_recibido = $_POST['csrf_token'] ?? '';
            if (!Security::verificarTokenCSRF($token_recibido)) {
                die("Error: Token de seguridad inválido.");
            }

            $id_usuario = $_SESSION['usuario_id'];
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            //Si hay contraseña nueva, se encripta
            $pass_hash = null;
            if (!empty($password)) {
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            }
            
            //Actualizar textos
            if ($this->usuarioModel->actualizarDatos($id_usuario, $nombre, $pass_hash)) {
                $_SESSION['usuario_nombre'] = $nombre;
            }

            //Actualizar Foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $tipo = $_FILES['foto']['type'];
                $tamano = $_FILES['foto']['size'];

                //Límite de 2MB
                if ($tamano <= 2097152) { 
                    $imagenData = file_get_contents($_FILES['foto']['tmp_name']); 
                    $this->usuarioModel->actualizarFoto($id_usuario, $imagenData, $tipo);
                    $_SESSION['usuario_foto'] = true; 
                }
            }

            header("Location: " . RUTA_BASE . "/index.php?action=verPerfil&msg=guardado");
            exit();
        }
   }

    public function verAvatar() {
        if (ob_get_length()) ob_clean();

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $fila = $this->usuarioModel->obtenerFoto($_GET['id']);
            
            if ($fila && !empty($fila['foto_perfil'])) {
                header("Content-Type: " . $fila['tipo_imagen_perfil']);
                echo $fila['foto_perfil'];
                exit();
            }
        }
        
        //Imagen por defecto
        $rutaImagenDefecto = __DIR__ . '/../IMGS/perfilDefecto.png';
        
        if (file_exists($rutaImagenDefecto)) {
            header("Content-Type: image/png");
            readfile($rutaImagenDefecto);
        }
        
        exit();
    }
}
?>