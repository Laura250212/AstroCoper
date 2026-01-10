<?php
$page_title = "Registro de Ventas - Panel Admin";
include_once __DIR__ . '/../General/header.php';
?>

<style>
    /* Efecto al pasar el ratón*/
    .tabla-astro tr:hover {
        background-color: rgba(255, 0, 255, 0.1) !important;
    }
    /* En móvil, el texto pequeño debajo del nombre */
    .info-movil {
        font-size: 0.8rem;
        color: #aaa;
        margin-top: 4px;
        font-weight: normal;
    }
    /* Estilo del botón de envío */
    .btn-enviar {
        padding: 4px 10px;
        font-size: 0.75rem;
        background: rgba(0, 255, 255, 0.1);
        color: #00ffff;
        border: 1px solid #00ffff;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 5px;
    }
    .btn-enviar:hover {
        background: rgba(0, 255, 255, 0.3);
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
    }
</style>

<div class="store-container" style="padding-top: 100px; padding-bottom: 80px;">
    
    <div class="store-header">
        <h2 class="store-title" style="color: #ff00ff; text-shadow: 0 0 20px #ff00ff;">REGISTRO DE VENTAS</h2>
        <p class="store-subtitle">Historial completo de ventas</p>
    </div>

    <div class="card" style="max-width: 1200px; text-align: left; padding: 0;">
        
        <div style="padding: 20px; display: flex; justify-content: flex-end; border-bottom: 1px solid rgba(255, 0, 255, 0.2);">
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="buttons" style="border-color: #aaa; color: #aaa; font-size: 0.9rem; padding: 5px 15px;">
                ← Volver al Panel
            </a>
        </div>

        <div class="w3-responsive">
            <table class="w3-table w3-bordered tabla-astro" style="color: #e0e0e0; min-width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid #ff00ff; text-align: left;">
                        <th class="w3-hide-small" style="padding: 15px; color: #ff00ff;">ID #</th>
                        <th class="w3-hide-small" style="padding: 15px; color: #ff00ff;">Fecha</th>
                        
                        <th style="padding: 15px; color: #ff00ff;">Comprador</th>
                        <th style="padding: 15px; color: #ff00ff;">Total</th>
                        <th style="padding: 15px; color: #ff00ff;">Estado / Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hay_ventas = false;
                    ?>

                    <?php foreach ($ventas as $venta): ?>
                        <?php $hay_ventas = true; ?>
                        <tr style="border-bottom: 1px solid rgba(255, 0, 255, 0.2); transition: background 0.3s;">
                            
                            <td class="w3-hide-small" style="padding: 15px; font-weight: bold; vertical-align: middle;">
                                #<?php echo $venta['id']; ?>
                            </td>

                            <td class="w3-hide-small" style="padding: 15px; vertical-align: middle;">
                                <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?>
                            </td>

                            <td style="padding: 15px; color: #00ffff; vertical-align: middle;">
                                👤 <?php echo htmlspecialchars($venta['comprador']); ?>
                                
                                <div class="w3-hide-medium w3-hide-large info-movil">
                                    📅 <?php echo date("d/m/y", strtotime($venta['fecha'])); ?>
                                    <br>
                                    Ref: #<?php echo $venta['id']; ?>
                                </div>
                            </td>

                            <td style="padding: 15px; color: #00ff00; font-weight: bold; vertical-align: middle;">
                                <?php echo number_format($venta['total'], 2); ?>€
                            </td>

                            <td style="padding: 15px; vertical-align: middle;">
                                <?php 
                                    $textoEstado = isset($venta['estado']) ? strtolower(trim($venta['estado'])) : 'desconocido';
                                    
                                    //Configuración por defecto en azul cian
                                    $color = '#00ffff'; 
                                    $borde = '#00ffff';

                                    // Si es 'pagado', verde
                                    if ($textoEstado == 'pagado') { 
                                        $color = '#00ff00';
                                        $borde = '#00ff00';
                                    }
                                    // Si es 'pendiente', amarillo
                                    if ($textoEstado == 'pendiente') {
                                        $color = '#ffff00';
                                        $borde = '#ffff00';
                                    }
                                ?>
                                
                                <span style="color: <?php echo $color; ?>; border: 1px solid <?php echo $borde; ?>; padding: 3px 8px; border-radius: 5px; font-size: 0.75rem; text-transform: uppercase; font-weight: bold; display: inline-block;">
                                    <?php echo htmlspecialchars($venta['estado']); ?>
                                </span>

                                <?php if ($textoEstado != 'enviado'): ?>
                                    <div style="margin-top: 5px;">
                                        <form action="<?php echo RUTA_BASE; ?>/index.php?action=cambiarEstado" method="POST" style="display:inline;">
                                            <input type="hidden" name="pedido_id" value="<?php echo $venta['id']; ?>">
                                            <input type="hidden" name="nuevo_estado" value="enviado">
                                            
                                            <button type="submit" class="btn-enviar" title="Marcar pedido como Enviado">
                                                ✈️ Enviar
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$hay_ventas): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #aaa;">
                                Aún no se han registrado movimientos comerciales.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>