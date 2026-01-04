<?php
/**
 * Controlador Principal
 * Gestiona la página de inicio y la galería de constelaciones.
 */

class ControladorPrincipal {

    private $db;
    private $constelacionModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->constelacionModel = new Constelacion($this->db);
    }

    public function mostrarPrincipal(){
        //Datos estáticos para los eventos
        $eventos = [
            [
                'titulo' => 'Eclipse Lunar Total',
                'fecha' => '27 de Agosto, 2026',
                'descripcion' => 'La Luna pasará por la sombra de la Tierra, volviéndose roja.',
                'icono' => '🌑'
            ],
            [
                'titulo' => 'Lluvia de Líridas',
                'fecha' => '22 de Abril, 2026',
                'descripcion' => 'Pico de actividad de meteoros visibles en el hemisferio norte.',
                'icono' => '☄️'
            ],
            [
                'titulo' => 'Oposición de Saturno',
                'fecha' => '4 de Octubre, 2026',
                'descripcion' => 'El mejor momento del año para fotografiar los anillos.',
                'icono' => '🪐'
            ]
        ];

        require_once __DIR__ . "/../Vistas/principal.php";
    }

    public function constelaciones() {
        $stmt = $this->constelacionModel->leerTodas();
        $listaConstelaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . "/../Vistas/constelaciones.php";
    }

    public function verImagenConstelacion() {
        //Limpia cualquier espacio en blanco previo
        if (ob_get_length()) ob_clean();

        if (isset($_GET['id'])) {
            $fila = $this->constelacionModel->obtenerImagen($_GET['id']);
            
            if ($fila && !empty($fila['imagen'])) {
                header("Content-Type: " . $fila['tipo_imagen']);
                echo $fila['imagen'];
                exit();
            }
        } 

        //Si no hay foto en BD, carga la imagen de aviso local
        $rutaImagenDefecto = __DIR__ . '/../IMGS/warning.png';
        
        if (file_exists($rutaImagenDefecto)) {
            header("Content-Type: image/png");
            readfile($rutaImagenDefecto);
        }
        
        exit();
    }
}
?>