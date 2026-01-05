<?php
/**
 * Archivo principal de la aplicación
 */

 //Configuración de errores

//Detectamos si la web se encuentra en local, es decir, no está desplegada
$entornosLocales = ['localhost', '127.0.0.1', '::1'];
//servidor local | IP estándar local | IP moderna local 

//Aqui recogera true si la variable anteriormente creada existe en el array, es decir, si está en local, de lo contrario devolvera false 
$enModoPruebas = in_array($_SERVER['SERVER_NAME'], $entornosLocales);

if ($enModoPruebas) {
    //Local
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    define('RUTA_BASE', '/AstroCoper');
} else {
    //Desplegado
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    define('RUTA_BASE', '');
}

session_start();

//Autoload - metodo para cargar las clases y scripts 
spl_autoload_register(function ($nombreClase) {
//Se convierten las barras invertidas de namespace a barras de directorio por si se diera el caso de que hubiera namespace 
    $nombreArchivo = str_replace('\\', '/', $nombreClase) . '.php';
    
    //Donde buscar
    $carpetas = [
        'Modelos/',
        'Controladores/',
        'APIs/',
        'Config/',
        'Helpers/'
    ];

    //Se busca el archivo necesario en cada carpeta
    foreach ($carpetas as $carpeta) {
        $rutaCompleta = __DIR__ . '/' . $carpeta . $nombreArchivo;
        if (file_exists($rutaCompleta)) {
            require_once $rutaCompleta;
            return;
        }
    }
});

try {
    //Instancias
    $usuario = new ControladorUsuario();
    $principal = new ControladorPrincipal();
    $error = new ControladorError();
    $tienda = new ControladorTienda();
    $foro = new ControladorForo();
    $API = new ControladorAPIs();

    //Recoge la acción de la URL, se usa $_REQUEST para que acepte tanto GET como POST 
    $action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "mostrarPrincipal";
    
    switch($action) {
        //Partes comunnes
        case "mostrarPrincipal":
            $principal->mostrarPrincipal();
            break;
        case "constelaciones":
            $principal->constelaciones();
            break;
        case "verImagenConstelacion":
            $principal->verImagenConstelacion();
            break;
        case "error":
            $error->mostrarError();
            break;

        //Usuarios
        case "login":
            $usuario->login(); 
            break;
        case "register":
            $usuario->register(); 
            break;
        case "logout":
            $usuario->logout();
            break;
        case "verLogin":
            $usuario->VLogin(); 
            break;
        case "verRegister":
            $usuario->VRegister();
            break;
        case "verAvatar":
            $usuario->verAvatar();
            break;
        case "verPerfil":
            $usuario->verPerfil();
            break;
        case "guardarPerfil":
            $usuario->guardarPerfil();
            break;

        //Tienda
        case "verTienda":
            $tienda->mostrarTienda();
            break;   
        case "verImagen":
            $tienda->verImagen();
            break;
        case "agregarCarrito":
            $tienda->agregarAlCarrito();
            break;
        case "verCarrito":
            $tienda->verCarrito();
            break;
        case "borrarItem":
            $tienda->eliminarDelCarrito();
            break;
        case "vaciarCarrito":
            $tienda->vaciarCarrito();
            break;
        case "procesarPedido":
            $tienda->procesarPedido();
            break;
        case "pedidoConfirmado":
            $tienda->pedidoConfirmado();
            break;
        case "misCompras":
            $tienda->misCompras();
            break;

        //Admin
        case "panelAdmin":
            $admin = new ControladorAdmin(); 
            $admin->panelAdmin();
            break;
        case "verVentas":
            $admin = new ControladorAdmin(); 
            $admin->verVentas();
            break;
        
        //Productos
        case "crearProducto":
            $admin = new ControladorAdmin();
            $admin->vistaCrear();
            break;
        case "guardarProducto":
            $admin = new ControladorAdmin();
            $admin->crearProducto(); 
            break;      
        case "borrarProducto":
            $admin = new ControladorAdmin();
            $admin->eliminarProducto();
            break;
        case "editarProducto":
            $admin = new ControladorAdmin();
            $admin->vistaEditar();
            break;          
        case "actualizarProducto":
            $admin = new ControladorAdmin();
            $admin->actualizarProducto();
            break;

        //Constelaciones
        case "crearConstelacion":
            $admin = new ControladorAdmin();
            $admin->crearConstelacion();
            break;
        case "guardarConstelacion":
            $admin = new ControladorAdmin();
            $admin->guardarConstelacion();
            break;
        case "borrarConstelacion":
            $admin = new ControladorAdmin();
            $admin->borrarConstelacion();
            break; 
        case "editarConstelacion":
            $admin = new ControladorAdmin();
            $admin->vistaEditarConstelacion();
            break;    
        case "actualizarConstelacion":
            $admin = new ControladorAdmin();
            $admin->actualizarConstelacion();
            break;

        //Gestion de usuarios
        case "gestionUsuarios":
            $admin = new ControladorAdmin(); 
            $admin->gestionUsuarios();
            break;
        case "crearUsuario":
            $admin = new ControladorAdmin();
            $admin->crearUsuario();
            break;
        case "borrarUsuario":
            $admin = new ControladorAdmin();
            $admin->borrarUsuario();
            break;
        case "editarUsuario":
            $admin = new ControladorAdmin();
            $admin->vistaEditarUsuario(); 
            break;
        case "actualizarUsuario":
            $admin = new ControladorAdmin();
            $admin->actualizarUsuario();
            break;

        //Foro
        case "foro":
            $foro->verForo();
            break;
        case "guardarComentario":
            $foro->guardarComentario();
            break;
        case "borrarComentario":
            $foro->borrarComentario();
            break;

        //APIS
        case "imgDia":
            $API->imagenDia();
            break;
        case "verMapa":
            $API->VerMapa();
            break;

        default:
            $error->error404();
            break;
    }

} catch (Throwable $e) {
    if (isset($enModoPruebas) && $enModoPruebas === true) {
        echo "<div style='background:red; color:white; padding:20px; font-family:monospace;'>";
        echo "<h3>☠️ Error (Modo Debug):</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<p>En archivo: " . $e->getFile() . " línea: " . $e->getLine() . "</p>";
        echo "</div>";
        exit();
    } 

    else {
        error_log("Error Fatal App: " . $e->getMessage());
        $_GET['msg'] = "Ha ocurrido un error interno en el servidor.";

        //Si no existe la instacia de error previamente, se crea
        if (!isset($error)) {
            require_once 'Controladores/ControladorError.php';
            $error = new ControladorError();
        }
        
        $error->mostrarError();
        exit();
    }
}
?>