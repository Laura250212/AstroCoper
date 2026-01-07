<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Se pone un titulo a la pagina en caso de que no haya
$page_title = $page_title ?? 'AstroCoper'; 
$lang_code = isset($_GET['lang']) ? htmlspecialchars($_GET['lang']) : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <style>
        html, body { background-color: #000000; margin: 0; padding: 0; }
    </style>

    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>/Vistas/General/css/estilos.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    
    <link rel="preload" as="image" href="<?php echo RUTA_BASE; ?>/IMGS/logo.png">
    
</head>
<body>

    <div id="stars"></div>
    <div id="stars2"></div>
    <div id="stars3"></div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<header>
    <div class="header-container">
        <h1 class="logo">
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=mostrarPrincipal" style="text-decoration:none;">
            <img src="<?php echo RUTA_BASE; ?>/IMGS/logo.png" 
     alt="Logo AstroCoper" 
     width="518" 
     height="82"
     style="height: 40px; width: auto; vertical-align: middle;">
            </a>
        </h1>

        <nav class="nav-links">
            <ul>
                <li><a href="<?php echo RUTA_BASE; ?>/index.php?action=mostrarPrincipal">Inicio</a></li>
                <li><a href="<?php echo RUTA_BASE; ?>/index.php?action=foro">Foro</a></li>
                <li><a href="<?php echo RUTA_BASE; ?>/index.php?action=constelaciones">Constelaciones</a></li>
                <li><a href="<?php echo RUTA_BASE; ?>/index.php?action=verMapa">Mapa</a></li>
                <li><a href="<?php echo RUTA_BASE; ?>/index.php?action=imgDia">Imagen del día</a></li>
                <li><a href="<?php echo RUTA_BASE; ?>/index.php?action=verTienda">Tienda</a></li>
            </ul>
        </nav>

        <div class="login-register-buttons">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                
                <div class="user-profile" onclick="toggleProfileSidebar()">
                    <img src="<?php echo RUTA_BASE; ?>/index.php?action=verAvatar&id=<?php echo $_SESSION['usuario_id']; ?>" 
                        alt="Foto de perfil" 
                        class="user-avatar" 
                        onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/perfilDefecto.png'">
                    
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                    
                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                        <span class="admin-badge">ADMIN</span>
                    <?php endif; ?>
                </div>

                <div class="overlay" id="profileOverlay" onclick="toggleProfileSidebar()"></div>
                
                <div class="profile-sidebar" id="profileSidebar">
                    
                    <div class="sidebar-header">
                        <img src="<?php echo RUTA_BASE; ?>/index.php?action=verAvatar&id=<?php echo $_SESSION['usuario_id']; ?>" 
                             alt="Foto de perfil" 
                             class="sidebar-avatar"
                             onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/perfilDefecto.png'">
                        
                        <h2 class="sidebar-name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h2>
                        
                        <p class="sidebar-email"><?php echo htmlspecialchars($_SESSION['usuario_email'] ?? 'Email no disponible'); ?></p>
                    </div>

                    <div class="sidebar-menu">
                        <div class="menu-section">
                            <h3 class="menu-title">Mi Cuenta</h3>
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=verPerfil" class="menu-item">
                                <i>👤</i> Mi Perfil
                            </a>
                        </div>

                        <div class="menu-section">
                            <h3 class="menu-title">Actividad</h3>
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=verCarrito" class="menu-item">
                                <i>🛒</i> Ver Carrito
                            </a>
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=misCompras" class="menu-item">
                                <i>📦</i> Historial Compras
                            </a>
                        </div>

                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                        <div class="menu-section">
                            <h3 class="menu-title">Administración</h3>
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="menu-item">
                                <i>🛠️</i> Panel Admin
                            </a>
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=gestionUsuarios" class="menu-item">
                                <i>👥</i> Gestión Usuarios
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="sidebar-footer">
                        <a href="<?php echo RUTA_BASE; ?>/index.php?action=logout" class="logout-btn">
                            <i>🚪</i> Cerrar Sesión
                        </a>
                    </div>
                </div>

                <script>
                    function toggleProfileSidebar() {
                        const sidebar = document.getElementById('profileSidebar');
                        const overlay = document.getElementById('profileOverlay');
                        sidebar.classList.toggle('active');
                        overlay.classList.toggle('active');
                    }

                    //Cierra el menú al pulsar la tecla ESC
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            const sidebar = document.getElementById('profileSidebar');
                            const overlay = document.getElementById('profileOverlay');
                            if (sidebar.classList.contains('active')) {
                                sidebar.classList.remove('active');
                                overlay.classList.remove('active');
                            }
                        }
                    });
                </script>

            <?php else: ?>
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=verLogin" class="login-btn">Iniciar sesión</a>
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=verRegister" class="register-btn">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="main-content">