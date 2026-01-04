<?php
$page_title = "Panel de Comandante - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="store-header">
        <h2 class="store-title" style="color: #ff00ff; text-shadow: 0 0 20px #ff00ff;">PANEL DE COMANDO</h2>
        <p class="store-subtitle">Gestión global del sistema</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div id="mensajeToast" style="max-width: 1200px; margin: 0 auto 30px auto; padding: 15px; border-radius: 8px; text-align: center; font-weight: bold; box-shadow: 0 0 10px rgba(0,0,0,0.5); opacity: 1; transition: opacity 0.5s ease-out;
            <?php
                $tipo = $_GET['msg'];
                //Definir colores según si es éxito (verde) o error (rojo)
                if ($tipo == 'creado' || $tipo == 'editado' || $tipo == 'borrado') {
                    echo 'background: rgba(0, 255, 0, 0.1); border: 1px solid #00ff00; color: #00ff00; text-shadow: 0 0 5px #00ff00;';
                } else {
                    echo 'background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff4444; text-shadow: 0 0 5px #ff4444;';
                }
            ?>">
            <?php
                //Mensaje específico según el caso
                switch ($tipo) {
                    case 'creado': echo "¡Elemento registrado en el sistema con éxito!"; break;
                    case 'editado': echo "¡Datos actualizados correctamente!"; break;
                    case 'borrado': echo "¡Elemento eliminado del inventario!"; break;
                    case 'error': echo "Ha ocurrido un error en la operación."; break;
                    default: echo "Operación realizada."; break;
                }
            ?>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toast = document.getElementById("mensajeToast");

                if (toast) {
                    //Esperar 3 segundos
                    setTimeout(function(){ 
                        toast.style.opacity = '0'; //Efecto desvanecer
                        setTimeout(() => toast.style.display = 'none', 500);
                    }, 3000);
                    
                    //Limpiar la URL
                    const newUrl = window.location.href.split('&msg=')[0].split('?msg=')[0];
                    window.history.pushState({path:newUrl}, '', newUrl);
                }
            });
        </script>
    <?php endif; ?>
    <div style="text-align: center; margin-bottom: 40px;">
        <a href="<?php echo RUTA_BASE; ?>/index.php?action=verVentas" class="buttons" style="background: rgba(0, 255, 255, 0.2); width: 100%; max-width: 400px; display: inline-block;">
            📊 Ver Registro de Ventas Globales
        </a>
    </div>

    <div class="card" style="text-align: left; max-width: 1200px; margin-bottom: 50px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #00ffff; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #00ffff; margin: 0;">📦 Inventario de Suministros</h3>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=crearProducto" class="buttons" style="padding: 5px 15px; font-size: 0.9rem; border-color: #00ff00; color: #00ff00;">
                + Añadir Producto
            </a>
        </div>

        <table style="width: 100%; border-collapse: collapse; color: #e0e0e0;">
            <thead>
                <tr style="border-bottom: 2px solid #ff00ff; text-align: left;">
                    <th style="padding: 10px;">IMG</th>
                    <th style="padding: 10px;">Nombre</th>
                    <th style="padding: 10px;">Precio</th>
                    <th style="padding: 10px;">Stock</th>
                    <th style="padding: 10px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $prod): ?>
                    <tr style="border-bottom: 1px solid rgba(255, 0, 255, 0.2);">
                        <td style="padding: 10px;">
                            <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagen&id=<?php echo $prod['id']; ?>" 
                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; border: 1px solid #ff00ff;"
                                 onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/defecto.png'">
                        </td>
                        <td style="padding: 10px; font-weight: bold; color: #ff00ff;">
                            <?php echo htmlspecialchars($prod['nombre']); ?>
                        </td>
                        <td style="padding: 10px;"><?php echo number_format($prod['precio'], 2); ?>€</td>
                        <td style="padding: 10px;">
                            <?php if ($prod['stock'] < 5): ?>
                                <span style="color: #ff4444; font-weight: bold;"><?php echo $prod['stock']; ?></span>
                            <?php else: ?>
                                <span style="color: #00ff00;"><?php echo $prod['stock']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px;">
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=editarProducto&id=<?php echo $prod['id']; ?>" title="Editar" style="text-decoration: none; margin-right: 5px; font-size: 1.2rem;">✏️</a>
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=borrarProducto&id=<?php echo $prod['id']; ?>" 
                               onclick="return confirm('¿Borrar producto?');" title="Borrar" style="text-decoration: none; font-size: 1.2rem;">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="padding: 20px; text-align: center;">Sin productos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card" style="text-align: left; max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ffd700; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #ffd700; margin: 0;">✨ Guía de Constelaciones</h3>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=crearConstelacion" class="buttons" style="padding: 5px 15px; font-size: 0.9rem; border-color: #ffd700; color: #ffd700;">
                + Añadir Constelación
            </a>
        </div>

        <table style="width: 100%; border-collapse: collapse; color: #e0e0e0;">
            <thead>
                <tr style="border-bottom: 2px solid #ffd700; text-align: left;">
                    <th style="padding: 10px;">Mapa</th>
                    <th style="padding: 10px;">Nombre</th>
                    <th style="padding: 10px;">Descripción Breve</th>
                    <th style="padding: 10px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($constelaciones)): ?>
                    <?php foreach ($constelaciones as $const): ?>
                    <tr style="border-bottom: 1px solid rgba(255, 215, 0, 0.2);">
                        <td style="padding: 10px;">
                            <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagenConstelacion&id=<?php echo $const['id']; ?>" 
                                 style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px; border: 1px solid #ffd700;"
                                 onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/warning.png'">
                        </td>
                        <td style="padding: 10px; font-weight: bold; color: #ffd700;">
                            <?php echo htmlspecialchars($const['nombre']); ?>
                        </td>
                        <td style="padding: 10px; font-size: 0.85rem; color: #ccc;">
                            <?php 
                                //mb_substr para tildes
                                echo mb_substr(htmlspecialchars($const['descripcion']), 0, 60) . '...'; 
                            ?>
                        </td>
                        <td style="padding: 10px;">
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=editarConstelacion&id=<?php echo $const['id']; ?>" 
                               title="Editar" style="text-decoration: none; margin-right: 5px; font-size: 1.2rem;">✏️</a>
                            
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=borrarConstelacion&id=<?php echo $const['id']; ?>" 
                               onclick="return confirm('¿Eliminar esta constelación?');" 
                               style="text-decoration: none; font-size: 1.2rem;" title="Borrar">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="padding: 20px; text-align: center;">No hay constelaciones registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>