<?php
$page_title = "Comunidad Estelar - AstroCoper";
include_once __DIR__ . '/../General/header.php';

$token = Security::generarTokenCSRF();
?>

<div class="store-container">
    <div class="store-header">
        <h2 class="store-title" style="color: #00ffff;">TRANSMISIONES DE LA COMUNIDAD</h2>
        <p class="store-subtitle">Comparte tus hallazgos con otros exploradores</p>
    </div>

    <?php 
    $tipo = isset($_GET['msg']) ? $_GET['msg'] : (isset($_GET['error']) ? $_GET['error'] : null);
    
    if ($tipo): ?>
        <div id="alerta-foro" style="max-width: 800px; margin: 0 auto 30px auto; padding: 15px; border-radius: 8px; text-align: center; font-weight: bold; box-shadow: 0 0 10px rgba(0,0,0,0.5); opacity: 1; transition: opacity 0.5s ease-out;
            <?php
                if ($tipo == 'publicado' || $tipo == 'borrado') {
                    echo 'background: rgba(0, 255, 0, 0.2); border: 1px solid #00ff00; color: #00ff00; text-shadow: 0 0 5px #00ff00;';
                } else {
                    echo 'background: rgba(255, 0, 0, 0.2); border: 1px solid #ff4444; color: #ff4444; text-shadow: 0 0 5px #ff4444;';
                }
            ?>">
            <?php
                switch ($tipo) {
                    case 'publicado': echo "¡Transmisión enviada con éxito!"; break;
                    case 'borrado': echo "🗑️ Mensaje eliminado del registro"; break;
                    case 'vacio': echo "No puedes enviar una transmisión vacía"; break;
                    case 'noautorizado': echo "Acceso denegado. No tienes permisos"; break;
                    default: echo "Operación realizada"; break;
                }
            ?>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const alerta = document.getElementById('alerta-foro');
                if (alerta) {
                    setTimeout(function() {
                        alerta.style.opacity = '0';
                        setTimeout(() => alerta.style.display = 'none', 500);
                        
                        //Limpiar URL
                        const newUrl = window.location.href.replace(/[?&](msg|error)=[^&]+/, '');
                        window.history.pushState({path:newUrl}, '', newUrl);
                    }, 4000); //4 segundos
                }
            });
        </script>
    <?php endif; ?>
    <div class="card" style="max-width: 800px; margin-bottom: 40px;">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            
            <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST">
                
                <input type="hidden" name="action" value="guardarComentario">
                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                
                <div style="display: flex; gap: 15px; align-items: flex-start;">
                    
                    <div style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #00ffff; background-color: #000; overflow: hidden; flex-shrink: 0;">
                        <img src="<?php echo RUTA_BASE; ?>/index.php?action=verAvatar&id=<?php echo $_SESSION['usuario_id']; ?>" 
                             alt="Tu Avatar"
                             style="width: 100%; height: 100%; object-fit: cover;"
                             onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/perfilDefecto.png'">
                    </div>
                    
                    <div style="flex-grow: 1;">
                        <textarea name="mensaje" rows="3" required placeholder="Escribe tu mensaje a la galaxia..." 
                                  style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid #00ffff; color: white; padding: 10px; border-radius: 10px; font-family: inherit; resize: none;"></textarea>
                        
                        <div style="text-align: right; margin-top: 10px;">
                            <button type="submit" class="buttons" style="padding: 8px 20px; font-size: 0.9rem;">ENVIAR TRANSMISIÓN</button>
                        </div>
                    </div>
                </div>
            </form>
        
        <?php else: ?>
            <p>Debes <a href="<?php echo RUTA_BASE; ?>/index.php?action=verLogin" style="color: #00ffff;">iniciar sesión</a> para enviar mensajes.</p>
        <?php endif; ?>
    </div>

    <div style="max-width: 800px; margin: 0 auto;">
        <?php if (!empty($mensajes)): ?>
            <?php foreach ($mensajes as $msg): ?>
                
                <div style="background: rgba(10, 20, 40, 0.8); border: 1px solid rgba(0,255,255,0.2); border-radius: 15px; padding: 20px; margin-bottom: 20px; display: flex; gap: 20px; transition: transform 0.2s;">
                    
                    <div style="text-align: center; min-width: 60px;">
                        
                        <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin: 0 auto; background-color: #000; border: 2px solid <?php echo ($msg['rol'] === 'admin') ? '#ff00ff' : '#00ffff'; ?>;">
                            <img src="<?php echo RUTA_BASE; ?>/index.php?action=verAvatar&id=<?php echo $msg['usuario_id']; ?>" 
                                 alt="Avatar"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/perfilDefecto.png'">
                        </div>
                        
                        <?php if($msg['rol'] === 'admin'): ?>
                            <span style="display:block; font-size: 0.7rem; color: #ff00ff; margin-top: 5px; font-weight: bold;">ADMIN</span>
                        <?php endif; ?>
                    </div>

                    <div style="flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <strong style="color: <?php echo ($msg['rol'] === 'admin') ? '#ff00ff' : '#00ffff'; ?>; font-size: 1.1rem;">
                                <?php echo htmlspecialchars($msg['nombre']); ?>
                            </strong>
                            <small style="color: #aaa;">
                                <?php echo date("d/m/Y H:i", strtotime($msg['fecha'])); ?>
                            </small>
                        </div>

                        <p style="color: #e0e0e0; line-height: 1.5; margin: 0;">
                            <?php echo nl2br(htmlspecialchars($msg['mensaje'])); ?>
                        </p>

                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                            <div style="text-align: right; margin-top: 10px;">
                                <a href="<?php echo RUTA_BASE; ?>/index.php?action=borrarComentario&id=<?php echo $msg['id']; ?>" 
                                   onclick="return confirm('¿Borrar mensaje?')"
                                   style="color: #ff4444; text-decoration: none; font-size: 0.9rem;">🗑️ Eliminar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; color: #aaa; padding: 20px;">
                <p>No hay transmisiones recibidas. ¡Sé el primero en hablar!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>