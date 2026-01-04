<?php
$token = Security::generarTokenCSRF();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AstroCoper</title>
    
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>/Vistas/General/css/estilos.css"> 
</head>
<body>
    
    <div id="stars"></div>
    <div id="stars2"></div>
    <div id="stars3"></div>

    <div class="auth-container">
        <div class="card" style="max-width: 450px; padding: 2.5rem;">
            <h2 style="color: #00ffff; margin-bottom: 2rem; text-shadow: 0 0 10px #00ffff;">ACCESO TRIPULACIÓN</h2>
            
            <?php if (!empty($mensaje_error)): ?>
                <div class="error-message" style="background: rgba(255, 0, 0, 0.2); border: 1px solid red; color: #ffaaaa; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;">
                    ⚠️ <?php echo htmlspecialchars($mensaje_error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo RUTA_BASE; ?>/index.php" class="auth-form">
                
                <input type="hidden" name="action" value="login">
                
                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                
                <div class="input-group">
                    <input type="email" name="email" placeholder="Identificador (Email)" required pattern=".+@.+\..+"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="input-group">
                    <input type="password" name="password" placeholder="Código de acceso" required>
                </div>

                <button type="submit" class="login-btn" style="width: 100%; margin-top: 10px; font-size: 1.1rem; cursor: pointer;">
                    INICIAR SESIÓN
                </button>
            </form>

            <div style="margin-top: 2rem; border-top: 1px solid rgba(0,255,255,0.2); padding-top: 1rem;">
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=verRegister" class="auth-link">¿Sin credenciales? Solicitar Registro</a>
                <br><br>
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=mostrarPrincipal" class="auth-link">⬅ Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>