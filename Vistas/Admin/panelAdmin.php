<?php
$page_title = "Panel de Comandante - AstroCoper";
include_once __DIR__ . '/../General/header.php';

//Notificaciones SweetAlert
$alertaScript = "";
if (isset($_GET['msg'])) {
    $tipo = $_GET['msg'];
    $titulo = "Notificación";
    $texto = "Operación realizada";
    $icono = "info";

    switch ($tipo) {
        case 'creado':
            $titulo = "¡Registrado!";
            $texto = "Elemento añadido al sistema con éxito.";
            $icono = "success";
            break;
        case 'editado':
            $titulo = "¡Actualizado!";
            $texto = "Los datos se han guardado correctamente.";
            $icono = "success";
            break;
        case 'borrado':
            $titulo = "¡Eliminado!";
            $texto = "El elemento ha sido borrado del inventario.";
            $icono = "success"; 
            break;
        case 'error':
            $titulo = "Error";
            $texto = "Ha ocurrido un problema en la operación.";
            $icono = "error";
            break;
    }

    //Script de SweetAlert para que se ejecute al cargar
    $alertaScript = "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$titulo',
                text: '$texto',
                icon: '$icono',
                background: '#1a1a1a',
                color: '#ffffff',
                confirmButtonColor: '#ff00ff',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                // Limpiar la URL sin recargar la página
                const newUrl = window.location.href.split('&msg=')[0].split('?msg=')[0];
                window.history.pushState({path:newUrl}, '', newUrl);
            });
        });
    </script>";
}
?>

<style>
    /*Efecto al pasar el ratón*/
    .tabla-astro tr:hover {
        background-color: rgba(255, 0, 255, 0.1) !important;
    }
</style>

<div class="store-container" style="padding-bottom: 80px;">
    
    <?php echo $alertaScript; ?>

    <div class="store-header">
        <h2 class="store-title" style="color: #ff00ff; text-shadow: 0 0 20px #ff00ff;">PANEL DE COMANDO</h2>
        <p class="store-subtitle">Gestión global del sistema</p>
    </div>

    <div style="text-align: center; margin-bottom: 40px;">
        <a href="<?php echo RUTA_BASE; ?>/index.php?action=verVentas" class="buttons" style="background: rgba(0, 255, 255, 0.2); width: 100%; max-width: 400px; display: inline-block;">
            📊 Ver Registro de Ventas Globales
        </a>
    </div>

    <div class="card" style="text-align: left; max-width: 1200px; margin-bottom: 50px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #00ffff; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #00ffff; margin: 0; font-size: 1.2rem;">📦 Inventario</h3>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=crearProducto" class="buttons" style="padding: 5px 10px; font-size: 0.9rem; border-color: #00ff00; color: #00ff00;">
                + Añadir
            </a>
        </div>

        <div class="w3-responsive">
            <table class="w3-table w3-bordered tabla-astro" style="color: #e0e0e0; min-width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid #ff00ff; text-align: left;">
                        <th class="w3-hide-small">IMG</th>
                        <th>Nombre</th>
                        <th class="w3-hide-small">Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                        <tr style="border-bottom: 1px solid rgba(255, 0, 255, 0.2);">
                            
                            <td class="w3-hide-small" style="vertical-align: middle;">
                                <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagen&id=<?php echo $prod['id']; ?>" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; border: 1px solid #ff00ff;"
                                     onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/defecto.png'">
                            </td>
                            
                            <td style="font-weight: bold; color: #ff00ff; vertical-align: middle;">
                                <?php echo htmlspecialchars($prod['nombre']); ?>
                                <br><span class="w3-hide-medium w3-hide-large" style="font-size: 0.8rem; color: #aaa; font-weight: normal;"><?php echo number_format($prod['precio'], 2); ?>€</span>
                            </td>

                            <td class="w3-hide-small" style="vertical-align: middle;"><?php echo number_format($prod['precio'], 2); ?>€</td>
                            
                            <td style="vertical-align: middle;">
                                <?php if ($prod['stock'] < 5): ?>
                                    <span style="color: #ff4444; font-weight: bold;"><?php echo $prod['stock']; ?></span>
                                <?php else: ?>
                                    <span style="color: #00ff00;"><?php echo $prod['stock']; ?></span>
                                <?php endif; ?>
                            </td>
                            
                            <td style="vertical-align: middle;">
                                <a href="<?php echo RUTA_BASE; ?>/index.php?action=editarProducto&id=<?php echo $prod['id']; ?>" title="Editar" style="text-decoration: none; margin-right: 10px; font-size: 1.2rem;">✏️</a>
                                <a href="#" onclick="confirmarBorrado('<?php echo RUTA_BASE; ?>/index.php?action=borrarProducto&id=<?php echo $prod['id']; ?>')" title="Borrar" style="text-decoration: none; font-size: 1.2rem;">🗑️</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center; padding: 20px;">Sin productos.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="text-align: left; max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ffd700; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #ffd700; margin: 0; font-size: 1.2rem;">✨ Constelaciones</h3>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=crearConstelacion" class="buttons" style="padding: 5px 10px; font-size: 0.9rem; border-color: #ffd700; color: #ffd700;">
                + Añadir
            </a>
        </div>

        <div class="w3-responsive">
            <table class="w3-table w3-bordered tabla-astro" style="color: #e0e0e0; min-width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid #ffd700; text-align: left;">
                        <th class="w3-hide-small">Mapa</th>
                        <th>Nombre</th>
                        <th class="w3-hide-small">Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($constelaciones)): ?>
                        <?php foreach ($constelaciones as $const): ?>
                        <tr style="border-bottom: 1px solid rgba(255, 215, 0, 0.2);">
                            
                            <td class="w3-hide-small" style="vertical-align: middle;">
                                <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagenConstelacion&id=<?php echo $const['id']; ?>" 
                                     style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px; border: 1px solid #ffd700;"
                                     onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/defecto.png'">
                            </td>
                            
                            <td style="font-weight: bold; color: #ffd700; vertical-align: middle;">
                                <?php echo htmlspecialchars($const['nombre']); ?>
                            </td>
                            
                            <td class="w3-hide-small" style="font-size: 0.85rem; color: #ccc; vertical-align: middle;">
                                <?php echo mb_substr(htmlspecialchars($const['descripcion']), 0, 50) . '...'; ?>
                            </td>
                            
                            <td style="vertical-align: middle;">
                                <a href="<?php echo RUTA_BASE; ?>/index.php?action=editarConstelacion&id=<?php echo $const['id']; ?>" title="Editar" style="text-decoration: none; margin-right: 10px; font-size: 1.2rem;">✏️</a>
                                <a href="#" onclick="confirmarBorrado('<?php echo RUTA_BASE; ?>/index.php?action=borrarConstelacion&id=<?php echo $const['id']; ?>')" title="Borrar" style="text-decoration: none; font-size: 1.2rem;">🗑️</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; padding: 20px;">No hay constelaciones.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmarBorrado(urlDestino) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta eliminación!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar',
        background: '#1a1a1a',
        color: '#ffffff'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = urlDestino;
        }
    })
}
</script>

<?php include_once __DIR__ . '/../General/footer.php'; ?>