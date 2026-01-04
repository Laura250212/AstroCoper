<?php
$page_title = "Pedido Confirmado - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="card" style="border-color: #00ff00; box-shadow: 0 0 40px rgba(0, 255, 0, 0.2);">
        <h1 style="color: #00ff00; font-size: 3rem; margin-bottom: 20px;">¡MISIÓN CUMPLIDA!</h1>
        
        <p style="font-size: 1.2rem;">Tu pedido ha sido registrado correctamente en la base de datos estelar.</p>
        
        <?php if(isset($_GET['id'])): ?>
            <p style="background: rgba(0,255,0,0.1); padding: 10px; display:inline-block; border-radius: 5px;">
                Código de seguimiento: <strong>#<?php echo htmlspecialchars($_GET['id']); ?></strong>
            </p>
        <?php endif; ?>

        <div style="margin-top: 40px;">
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=verTienda" class="buttons">Seguir Explorando</a>
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=misCompras" class="buttons" style="border-color: #ff00ff; color: #ff00ff;">Ver Mis Compras</a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>