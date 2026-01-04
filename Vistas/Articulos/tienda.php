<?php
$token = Security::generarTokenCSRF();

$page_title = "Tienda - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="store-header">
        <h2 class="store-title">SUMINISTROS ESTELARES</h2>
        <p class="store-subtitle">Equipamiento de alta tecnología para tu próxima misión</p>
    </div>

    <?php if (empty($productos)): ?>
        <div class="card">
            <p>No hay suministros disponibles en la base actualmente.</p>
        </div>
    <?php else: ?>

    <div class="products-grid">
        <?php foreach ($productos as $prod): ?>
            <div class="product-card">
                
                <?php if ($prod['stock'] <= 5 && $prod['stock'] > 0): ?>
                    <span class="product-badge badge-sale">¡Últimas uds!</span>
                <?php endif; ?>

                <div class="product-image-wrapper">
                    <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagen&id=<?php echo $prod['id']; ?>" 
                         alt="<?php echo htmlspecialchars($prod['nombre']); ?>"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/defecto.png'"> 
                </div>

                <div class="product-info">
                    <div>
                        <h3 class="product-title"><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                        <p class="product-description">
                            <?php 
                                //mb_substr para respetar tildes y emojis
                                echo mb_substr(htmlspecialchars($prod['descripcion']), 0, 80) . '...'; 
                            ?>
                        </p>
                    </div>
                    
                    <div>
                        <div class="product-meta">
                            <span class="product-price"><?php echo number_format($prod['precio'], 2); ?>€</span>
                        </div>
                        
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            
                            <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST">
                                
                                <input type="hidden" name="action" value="agregarCarrito">
                                <input type="hidden" name="producto_id" value="<?php echo $prod['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                
                                <?php if ($prod['stock'] > 0): ?>
                                    <button type="submit" class="add-to-cart-btn">Añadir al carrito</button>
                                <?php else: ?>
                                    <button type="button" class="add-to-cart-btn" style="background:grey; border-color:grey; cursor:not-allowed;" disabled>Agotado</button>
                                <?php endif; ?>
                            </form>

                        <?php else: ?>
                            
                            <a href="<?php echo RUTA_BASE; ?>/index.php?action=verLogin" class="add-to-cart-btn" style="text-align:center; display:block; background: rgba(255, 0, 0, 0.2); border-color: #ff4444; color: #ff4444; text-decoration: none; font-size: 13px; padding: 5px; width: fit-content; margin: 10px auto;">
                                🔒 Inicia sesión para comprar
                            </a>

                        <?php endif; ?>
                        </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<div id="cartToast" class="toast-notification">
    Producto añadido al inventario
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.get('msg') === 'added') {
            const toast = document.getElementById("cartToast");
            toast.className = "toast-notification show";
            
            setTimeout(function(){ 
                toast.className = toast.className.replace("show", ""); 
            }, 3000);
            
            //Limpiar URL manteniendo el action
            const newUrl = window.location.href.split('&msg=')[0].split('?msg=')[0];
            window.history.pushState({path:newUrl},'',newUrl);
        }
    });
</script>

<?php include_once __DIR__ . '/../General/footer.php'; ?>