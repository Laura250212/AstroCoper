<?php
$page_title = "Mis Compras - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="store-header">
        <h2 class="store-title">HISTORIAL DE MISIÓN</h2>
        <p class="store-subtitle">Registro de equipamiento adquirido</p>
    </div>

    <?php if (empty($pedidos)): ?>
        <div class="card">
            <p>Aún no has realizado ninguna adquisición de suministros.</p>
            <br>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=verTienda" class="buttons">Ir a la Tienda</a>
        </div>
    <?php else: ?>

        <div class="card" style="text-align: left; max-width: 1000px;">
            <table style="width: 100%; border-collapse: collapse; color: #e0e0e0;">
                <thead>
                    <tr style="border-bottom: 2px solid #00ffff; text-align: left;">
                        <th style="padding: 15px;">ID Pedido</th>
                        <th style="padding: 15px;">Fecha</th>
                        <th style="padding: 15px;">Contenido</th>
                        <th style="padding: 15px;">Total</th>
                        <th style="padding: 15px;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr style="border-bottom: 1px solid rgba(0,255,255,0.2);">
                        
                        <td style="padding: 15px; color: #00ffff;">
                            #<?php echo $pedido['id']; ?>
                        </td>
                        
                        <td style="padding: 15px;">
                            <?php echo date("d/m/Y H:i", strtotime($pedido['fecha'])); ?>
                        </td>
                        
                        <td style="padding: 15px;">
                            <ul style="font-size: 0.9rem; color: #b0d4ff; list-style: circle; margin-left: 15px;">
                                <?php 
                                if (isset($detalles[$pedido['id']])) {
                                    foreach ($detalles[$pedido['id']] as $item) {
                                        echo "<li>" . htmlspecialchars($item['nombre']) . " (x" . $item['cantidad'] . ")</li>";
                                    }
                                }
                                ?>
                            </ul>
                        </td>
                        
                        <td style="padding: 15px; font-weight: bold; color: #00ff00;">
                            <?php echo number_format($pedido['total'], 2); ?>€
                        </td>
                        
                        <td style="padding: 15px;">
                            <span style="background: rgba(0, 255, 0, 0.2); color: #00ff00; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem;">
                                <?php echo strtoupper($pedido['estado']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>