<?php
$page_title = "Guía de Constelaciones - AstroCoper";
include_once __DIR__ . '/General/header.php'; 
?>

<div class="store-container">
    <div class="store-header">
        <h1 class="store-title" style="text-shadow: 0 0 20px cyan; color: #00ffff;">GUÍA ESTELAR</h1>
        <p class="store-subtitle" style="color: #b0d4ff;">Explora los mapas del firmamento</p>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; padding-bottom: 50px;">
        
        <?php if (!empty($listaConstelaciones)): ?>
            <?php foreach ($listaConstelaciones as $const): ?>
            <div class="card" style="width: 300px; padding: 0; overflow: hidden; transition: transform 0.3s; border: 1px solid #00ffff;">
                
                <div style="height: 200px; overflow: hidden; border-bottom: 2px solid #00ffff; background: #000;">
                    <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagenConstelacion&id=<?php echo $const['id']; ?>" 
                         alt="<?php echo htmlspecialchars($const['nombre']); ?>"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/warning.png'">
                </div>

                <div style="padding: 20px; text-align: left;">
                    <h3 style="color: #ffd700; margin-top: 0; text-transform: uppercase; border-bottom: 1px solid #ffd700; padding-bottom: 10px;">
                        <?php echo htmlspecialchars($const['nombre']); ?>
                    </h3>
                    
                    <p style="color: #e0e0e0; font-size: 0.95rem; line-height: 1.6; margin-top: 15px;">
                        <?php 
                            $texto = htmlspecialchars($const['descripcion']);
                            //Se usan mb_strlen y mb_substr para respetar tildes y ñ
                            if (mb_strlen($texto) > 150) {
                                echo mb_substr($texto, 0, 150) . '...';
                            } else {
                                echo $texto;
                            }
                        ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card" style="max-width: 600px;">
                <h3 style="color: #ff4444;">No hay datos estelares</h3>
                <p>Por ahora no hay ninguna constelación. Vuelve pronto.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php 
include_once __DIR__ . '/General/footer.php'; 
?>