<?php
class Security {
    
    //Genera el token y lo guarda en la sesión
    public static function generarTokenCSRF() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        if (empty($_SESSION['csrf_token'])) {
            //Genera una cadena aleatoria criptográfica
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    //Verifica si el token que llega del formulario es el correcto
    public static function verificarTokenCSRF($token_formulario) {
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }
        
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token_formulario)) {
            return true;
        }
        return false;
    }
}
?>