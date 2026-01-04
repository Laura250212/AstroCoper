<?php
/**
 * SCRIPT DE INSTALACIÓN - ASTROCOPER
 * Crea todas las tablas necesarias
 * La mayor parte de las ejecuciones sql que se encuantran aqui han sido ejecutadas cuando ha sido necesario directamente en phpmyadmin, se almacenan aqui en caso de que sea necesario ejecutarlo en otra parte o que se pierda cualquier sentencia
 */

//Configurar los datos
$config = [
    'servername' => "localhost",
    'username'   => "User", 
    'password'   => "Password",
    'dbname'     => "AstroCoper",
    'port'       => 3306
];

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Función visual para mensajes
function mostrarMensaje($tipo, $mensaje) {
    $estilos = [
        'exito' => 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;',
        'error' => 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;',
        'info'  => 'background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;',
        'sql'   => 'background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;'
    ];
    $iconos = [
        'exito' => '✅', 'error' => '❌', 'info' => 'ℹ️', 'sql' => '🛠️'
    ];
    
    echo "<div style='{$estilos[$tipo]} padding: 10px; border-radius: 5px; margin-bottom: 5px; font-family: sans-serif;'>
            <strong>{$iconos[$tipo]}</strong> $mensaje
          </div>";
}

echo "<h1>Instalador de Base de Datos - AstroCoper</h1>";

try {
    //Conexión
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], '', $config['port']);
    if ($conn->connect_error) throw new Exception("Error de conexión: " . $conn->connect_error);
    mostrarMensaje('exito', "Conectado al servidor MySQL");

    ///Crear base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conn->query($sql)) throw new Exception("Error creando BD: " . $conn->error);
    mostrarMensaje('exito', "Base de datos '{$config['dbname']}' verificada");
    
    $conn->select_db($config['dbname']);

    //Tabla usuarios (Con soporte para fotos LONGBLOB)
    $sql = "CREATE TABLE IF NOT EXISTS `usuarios` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(100) NOT NULL,
        `email` VARCHAR(150) UNIQUE NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `rol` ENUM('admin', 'usuario') DEFAULT 'usuario' NOT NULL,
        `foto_perfil` LONGBLOB NULL,
        `tipo_imagen_perfil` VARCHAR(50) NULL,
        `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `activo` TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    if ($conn->query($sql)) mostrarMensaje('sql', "Tabla 'usuarios' revisada.");
    else throw new Exception("Error en tabla usuarios: " . $conn->error);

    //Tabla productos (Tienda)
    $sql = "CREATE TABLE IF NOT EXISTS `productos` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(150) NOT NULL,
        `descripcion` TEXT,
        `precio` DECIMAL(10,2) NOT NULL,
        `categoria` VARCHAR(50),
        `stock` INT DEFAULT 0,
        `imagen` LONGBLOB NULL,
        `tipo_imagen` VARCHAR(50) NULL,
        `activo` TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql)) mostrarMensaje('sql', "Tabla 'productos' revisada.");
    else throw new Exception("Error en tabla productos: " . $conn->error);

    //Tabla constelaciones
    $sql = "CREATE TABLE IF NOT EXISTS `constelaciones` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(100) NOT NULL,
        `descripcion` TEXT,
        `imagen` LONGBLOB NULL,
        `tipo_imagen` VARCHAR(50) NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql)) mostrarMensaje('sql', "Tabla 'constelaciones' revisada.");
    else throw new Exception("Error en tabla constelaciones: " . $conn->error);

    //Tabla foro (Relacionada con usuarios)
    $sql = "CREATE TABLE IF NOT EXISTS `foro` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `usuario_id` INT NOT NULL,
        `mensaje` TEXT NOT NULL,
        `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql)) mostrarMensaje('sql', "Tabla 'foro' revisada.");
    else throw new Exception("Error en tabla foro: " . $conn->error);

    //Tabla Pedidos
    $sql = "CREATE TABLE IF NOT EXISTS `pedidos` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `usuario_id` INT NOT NULL,
        `total` DECIMAL(10,2) NOT NULL,
        `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `estado` VARCHAR(20) DEFAULT 'pagado',
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->query($sql);

    //Tabla Detalles del Pedido
    $sql = "CREATE TABLE IF NOT EXISTS `detalles_pedido` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `pedido_id` INT NOT NULL,
        `producto_id` INT NOT NULL,
        `cantidad` INT NOT NULL,
        `precio_unitario` DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (`pedido_id`) REFERENCES `pedidos`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    if ($conn->query($sql)) mostrarMensaje('sql', "Tablas de 'pedidos' y detalles revisadas.");
    else throw new Exception("Error en tablas de pedidos: " . $conn->error);

    //Tabla carrito persistente
    $sql = "CREATE TABLE IF NOT EXISTS `carritos_guardados` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `usuario_id` INT NOT NULL,
        `producto_id` INT NOT NULL,
        `cantidad` INT NOT NULL,
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`producto_id`) REFERENCES `productos`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    if ($conn->query($sql)) mostrarMensaje('sql', "Tabla 'carritos_guardados' revisada.");
    else throw new Exception("Error en tabla carritos: " . $conn->error);

    //Usuario administrador creado con sentencias sql
    $adminEmail = 'admin@gmail.com';
    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$adminEmail'");
    
    if ($check && $check->num_rows == 0) {
        $passHash = password_hash("admin", PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('Administrador Principal', '$adminEmail', '$passHash', 'admin')";
        if ($conn->query($sql)) {
            mostrarMensaje('exito', "Usuario Admin creado: <b>admin@gmail.com</b> / <b>admin</b>");
        }
    } else {
        mostrarMensaje('info', "El usuario Admin ya existe, no se ha modificado.");
    }

    echo "<hr><h3>INSTALACIÓN COMPLETADA CON ÉXITO</h3>";
    echo "<div style='background: #e2f0ff; padding: 15px; border-radius: 5px; border: 1px solid #b8daff;'>
            <strong>Acceso Administrador:</strong><br>
             Email: <strong>admin@gmail.com</strong><br>
            Contraseña: <strong>admin</strong>
          </div><br>";
    echo "<a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir a la Web</a>";

} catch (Exception $e) {
    mostrarMensaje('error', "<b>ERROR FATAL:</b> " . $e->getMessage());
} finally {
    if (isset($conn)) $conn->close();
}
?>