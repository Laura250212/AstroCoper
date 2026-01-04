<?php
/**
 * Controlador de Errores
 * Gestiona las pantallas de fallo de forma segura
 */

class ControladorError {

    //Pasar el mensaje por parámetro o por URL
    public function mostrarError($mensaje = null) {
        
        //Si no le pasamos mensaje directo, mira si viene por la URL
        if ($mensaje === null && isset($_GET['mensaje'])) {
            //Mensaje limpio
            $mensaje = htmlspecialchars(urldecode($_GET['mensaje']));
        }
        
        //Si sigue vacío, usa el mensaje por defecto
        if ($mensaje === null) {
            $mensaje = "Se ha producido un error desconocido";
        }

        require_once __DIR__ . "/../Vistas/General/Errores/error.php"; 
        exit();
    }
    
    public function error404() {
        http_response_code(404);
        $this->mostrarError("Página no encontrada (Error 404)");
    }

    public function error500() {
        http_response_code(500);
        $this->mostrarError("Error interno del servidor (500).");
    }
    
    public function errorDatabase() {
        http_response_code(500);
        $this->mostrarError("Error de conexión con la base de datos.");
    }
}
?>