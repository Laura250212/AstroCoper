<?php
$page_title = "Gestión de Usuarios - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="store-header">
        <h2 class="store-title" style="color: #ff00ff; text-shadow: 0 0 20px #ff00ff;">Gestión de usuarios de la web</h2>
    </div>

    <?php 
    $tipo = isset($_GET['msg']) ? $_GET['msg'] : (isset($_GET['error']) ? 'error' : null);
    if ($tipo): 
        $titulo = ""; $texto = ""; $icono = "success"; $colorBoton = "#ff00ff";
        switch ($tipo) {
            case 'creado': $titulo = "¡Reclutamiento Exitoso!"; $texto = "Nuevo usuario añadido."; $colorBoton = "#00ffff"; break;
            case 'editado': $titulo = "¡Datos Actualizados!"; $texto = "Usuario modificado."; break;
            case 'borrado': $titulo = "¡Usuario Eliminado!"; $texto = "Borrado correctamente."; break;
            case 'error': $titulo = "Error"; $texto = "Falló la operación."; $icono = "error"; break;
        }
    ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: '<?php echo $titulo; ?>',
                    text: '<?php echo $texto; ?>',
                    icon: '<?php echo $icono; ?>',
                    background: '#0b132b',
                    color: '#ffffff',
                    confirmButtonColor: '<?php echo $colorBoton; ?>',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    const newUrl = window.location.href.replace(/[?&](msg|error)=[^&]+/, '');
                    window.history.pushState({path:newUrl}, '', newUrl);
                });
            });
        </script>
    <?php endif; ?>

    <div class="card" style="text-align: left; max-width: 1200px; background: rgba(11, 19, 43, 0.8); border: 1px solid #ff00ff; box-shadow: 0 0 20px rgba(255, 0, 255, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ff00ff; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #ff00ff; margin: 0; text-shadow: 0 0 10px rgba(255, 0, 255, 0.5);">Administración de usuarios</h3>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=crearUsuario" class="buttons" style="padding: 8px 20px; font-size: 0.9rem; border: 2px solid #00ffff; color: #00ffff; background: transparent;">
                + Añadir usuario
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table class="w3-table w3-hoverable" style="width: 100%; color: #e0e0e0;">
                <thead>
                    <tr style="background-color: rgba(255, 0, 255, 0.1);">
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ff00ff; color: #ff00ff;">Correo</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #ff00ff; color: #ff00ff;">Nombre</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #ff00ff; color: #ff00ff;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $usu): ?>
                        <tr style="border-bottom: 1px solid rgba(0, 255, 255, 0.2); transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='rgba(0, 255, 255, 0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px; border-bottom: 1px solid rgba(0, 255, 255, 0.1);"><?php echo htmlspecialchars($usu['email']); ?></td>
                            <td style="padding: 15px; font-weight: bold; color: #00ffff; border-bottom: 1px solid rgba(0, 255, 255, 0.1);"><?php echo htmlspecialchars($usu['nombre']); ?></td>
                            <td style="padding: 15px; text-align: center; border-bottom: 1px solid rgba(0, 255, 255, 0.1);">
                                <a href="<?php echo RUTA_BASE; ?>/index.php?action=editarUsuario&id=<?php echo $usu['id']; ?>" title="Editar" style="text-decoration: none; margin-right: 15px; font-size: 1.3rem;">✏️</a>
                                <a href="javascript:void(0);" onclick="confirmarBorradoUsuario('<?php echo RUTA_BASE; ?>/index.php?action=borrarUsuario&id=<?php echo $usu['id']; ?>')" style="text-decoration: none; font-size: 1.3rem; cursor: pointer;" title="Borrar">🗑️</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="padding: 30px; text-align: center; color: #00ffff;">No hay usuarios.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmarBorradoUsuario(urlDestino) {
    Swal.fire({
        title: '¿Eliminar Usuario?', text: "¡Esta acción no se puede deshacer!", icon: 'warning',
        showCancelButton: true, background: '#0b132b', color: '#fff',
        confirmButtonColor: '#ff3333', cancelButtonColor: '#00ffff',
        confirmButtonText: 'Sí, borrar', cancelButtonText: 'Cancelar'
    }).then((r) => { if (r.isConfirmed) window.location.href = urlDestino; })
}
</script>

<?php include_once __DIR__ . '/../General/footer.php'; ?>