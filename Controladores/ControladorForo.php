<?php
/**
 * Controlador del Foro
 * Gestiona la visualización y publicación
 */

class ControladorForo {
    private $foroModel;
    private $db;

    public function __construct() {
        //Carga Database y Foro automáticamente
        $database = new Database();
        $this->db = $database->getConnection();
        $this->foroModel = new Foro($this->db);
    }


    public function verForo() {
        $mensajes = $this->foroModel->obtenerMensajes();
        require_once __DIR__ . "/../Vistas/Foro/VForo.php";
    }

    //Guardar comentario
    public function guardarComentario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            //Solo usuarios logueados
            if (!isset($_SESSION['usuario_id'])) {
                header("Location: " . RUTA_BASE . "/index.php?action=verLogin");
                exit();
            }

            //Verificacion del token
            $token_recibido = $_POST['csrf_token'] ?? '';
            
            if (!Security::verificarTokenCSRF($token_recibido)) {
                die("Error de seguridad: Token inválido. Recarga la página.");
            }

            $mensaje = trim($_POST['mensaje']);
            
            if (!empty($mensaje)) {
                $this->foroModel->publicar($_SESSION['usuario_id'], $mensaje);
                header("Location: " . RUTA_BASE . "/index.php?action=foro&msg=publicado");
            } else {
                header("Location: " . RUTA_BASE . "/index.php?action=foro&error=vacio");
            }
            exit();
        }
    }

    //Exclusiva del usuario administrador, permite borrar comentarios de cualquier usuario
    public function borrarComentario() {
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin' && isset($_GET['id'])) {
            $this->foroModel->borrar($_GET['id']);
            header("Location: " . RUTA_BASE . "/index.php?action=foro&msg=borrado");
        } else {
            //Si intenta borrar sin permiso da error
            header("Location: " . RUTA_BASE . "/index.php?action=foro&error=noautorizado");
        }
        exit();
    }
}
?>