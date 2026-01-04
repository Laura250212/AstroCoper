<?php
/**
 * Controlador de APIs Externas
 */

class ControladorAPIs {

    //Muestra la Imagen del Día
    public function imagenDia() {
        require_once __DIR__ . "/../APIs/imgDia.php";
    }
}
?>