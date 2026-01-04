<?php
//Clase para gestionar conexiones a base de datos
class Database {
    
    //Propiedades
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    //Configura las credenciales automáticamente
    public function __construct() {
        //Intenta leer variables de entorno del servidor
        //Si no existen, usa los valores por defecto
        $this->host     = getenv('DB_HOST') ?: 'localhost';
        $this->db_name  = getenv('DB_NAME') ?: 'AstroCoper';
        $this->username = getenv('DB_USER') ?: 'User'; 
        $this->password = getenv('DB_PASS') ?: 'Password';     
    }

    //Genera y devuelve la conexión
    public function getConnection() {
        $this->conn = null;
        
        try {
            //Se crea la conexión PDO, se usa utf8mb4 para compatibilidad total con emojis y caracteres epeciales
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            //Configuración de errores y caracteres
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->conn->exec("set names utf8mb4");
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>