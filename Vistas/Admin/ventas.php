<?php
$page_title = "Registro de Ventas - Panel Admin";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container" style="padding-top: 100px;">
    
    <div class="store-header">
        <h2 class="store-title" style="color: #ff00ff; text-shadow: 0 0 20px #ff00ff;">REGISTRO DE VENTAS</h2>
        <p class="store-subtitle">Historial completo de transacciones en la base</p>
    </div>

    <div class="card" style="max-width: 1200px; text-align: left; padding: 20px;">
        
        <div style="margin-bottom: 20px; text-align: right;">
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="buttons" style="border-color: #aaa; color: #aaa; font-size: 0.9rem;">
                ← Volver al Panel
            </a>
        </div>

        <table style="width: 100%; border-collapse: collapse; color: #e0e0e0;">
            <thead>
                <tr style="border-bottom: 2px solid #ff00ff; text-align: left;">
                    <th style="padding: 15px; color: #ff00ff;">ID #</th>
                    <th style="padding: 15px; color: #ff00ff;">Fecha</th>
                    <th style="padding: 15px; color: #ff00ff;">Comprador</th>
                    <th style="padding: 15px; color: #ff00ff;">Total</th>
                    <th style="padding: 15px; color: #ff00ff;">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $hay_ventas = false;
                ?>

                <?php foreach ($ventas as $venta): ?>
                    <?php $hay_ventas = true; ?>
                    <tr style="border-bottom: 1px solid rgba(255, 0, 255, 0.2); transition: background 0.3s;">
                        
                        <td style="padding: 15px; font-weight: bold;">
                            #<?php echo $venta['id']; ?>
                        </td>

                        <td style="padding: 15px;">
                            <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?>
                        </td>

                        <td style="padding: 15px; color: #00ffff;">
                            👤 <?php echo htmlspecialchars($venta['comprador']); ?>
                        </td>

                        <td style="padding: 15px; color: #00ff00; font-weight: bold;">
                            <?php echo number_format($venta['total'], 2); ?>€
                        </td>

                        <td style="padding: 15px;">
                            <?php 
                                $color = '#aaa';
                                if($venta['estado'] == 'pagado') $color = '#00ff00';
                                if($venta['estado'] == 'pendiente') $color = '#ffff00';
                                if($venta['estado'] == 'enviado') $color = '#00ffff';
                            ?>
                            <span style="color: <?php echo $color; ?>; border: 1px solid <?php echo $color; ?>; padding: 3px 8px; border-radius: 5px; font-size: 0.8rem; text-transform: uppercase;">
                                <?php echo htmlspecialchars($venta['estado']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$hay_ventas): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #aaa;">
                            Aún no se han registrado movimientos comerciales en el sistema.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>