<?php
$page_title = "Tu Carrito - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="store-header">
        <h2 class="store-title">CARGA DE SUMINISTROS</h2>
    </div>

    <?php if (empty($productos_carrito)): ?>
        <div class="card">
            <p>Tu carrito está vacío</p>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=verTienda" class="buttons">Volver a la Tienda</a>
        </div>
    <?php else: ?>

    <div class="card" style="text-align: left;"> 
        
        <div class="w3-responsive">
            
            <table class="w3-table" style="width: 100%; border-collapse: collapse; color: #e0e0e0; min-width: 600px;">
                <thead>
                    <tr style="border-bottom: 2px solid #00ffff; text-align: left;">
                        <th style="padding: 15px;">Producto</th>
                        <th style="padding: 15px;">Precio</th>
                        <th style="padding: 15px;">Cantidad</th>
                        <th style="padding: 15px;">Subtotal</th>
                        <th style="padding: 15px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos_carrito as $item): ?>
                    <tr style="border-bottom: 1px solid rgba(0,255,255,0.2);">
                        <td style="padding: 15px;">
                            <strong style="color: #00ffff;"><?php echo htmlspecialchars($item['info']['nombre']); ?></strong>
                        </td>
                        <td style="padding: 15px;"><?php echo number_format($item['info']['precio'], 2); ?>€</td>
                        <td style="padding: 15px;"><?php echo $item['cantidad']; ?></td>
                        <td style="padding: 15px; color: #00ff00;"><?php echo number_format($item['subtotal'], 2); ?>€</td>
                        <td style="padding: 15px;">
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=borrarItem&id=<?php echo $item['info']['id']; ?>" 
                               class="w3-text-red w3-hover-text-grey"
                               style="color: #ff4444; text-decoration: none; font-weight: bold;">
                               &times; Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> <div style="margin-top: 30px; text-align: right;">
            <h3 style="color: #00ff00; font-size: 2rem;">Total: <?php echo number_format($total, 2); ?>€</h3>
            
            <br>
            
            <div class="w3-bar-block w3-right-align">
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=vaciarCarrito" class="buttons" style="background: rgba(255,0,0,0.2); border-color: #ff4444; color: #ff4444; margin-right: 15px; display:inline-block; margin-bottom: 10px;">
                    Vaciar Carga
                </a>
                
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=procesarPedido" class="buttons" style="display:inline-block; margin-bottom: 10px;">
                    CONFIRMAR COMPRA
                </a>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>