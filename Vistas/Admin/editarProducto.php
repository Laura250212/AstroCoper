<?php
$token = Security::generarTokenCSRF();

$page_title = "Editar Suministro - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="card" style="max-width: 600px;">
        <h2 style="color: #ff00ff; margin-bottom: 30px;">MODIFICAR ÍTEM #<?php echo $producto['id']; ?></h2>

        <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST" enctype="multipart/form-data" class="auth-form">
            
            <input type="hidden" name="action" value="actualizarProducto">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

            <div class="input-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>

            <div class="input-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="4" required style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white; border-radius: 8px;"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>

            <div class="input-group">
                <label>Precio (€):</label>
                <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required>
            </div>

            <div class="input-group">
                <label>Stock:</label>
                <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required>
            </div>

            <div class="input-group">
                <label>Categoría:</label>
                <select name="categoria" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white; border-radius: 8px;">
                    <option value="Telescopios" <?php echo ($producto['categoria'] == 'Telescopios') ? 'selected' : ''; ?>>Telescopios</option>
                    <option value="Trajes" <?php echo ($producto['categoria'] == 'Trajes') ? 'selected' : ''; ?>>Trajes</option>
                    <option value="Accesorios" <?php echo ($producto['categoria'] == 'Accesorios') ? 'selected' : ''; ?>>Accesorios</option>
                    <option value="Coleccionismo" <?php echo ($producto['categoria'] == 'Coleccionismo') ? 'selected' : ''; ?>>Coleccionismo</option>
                </select>
            </div>

            <div class="input-group" style="margin-top: 20px;">
                <label>Imagen Actual:</label>
                <br>
                <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagen&id=<?php echo $producto['id']; ?>" 
                     alt="Producto actual"
                     style="width: 100px; border: 1px solid #ff00ff; margin: 10px 0;"
                     onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/defecto.png'">
                <br>
                <label style="color: #b0d4ff; font-size: 0.9rem;">Subir nueva imagen (Dejar vacío para mantener la actual):</label>
                <input type="file" name="imagen" accept="image/*" style="border: none;">
            </div>

            <button type="submit" class="buttons" style="width: 100%; margin-top: 20px; border-color: #ff00ff; color: #ff00ff;">ACTUALIZAR DATOS</button>
        </form>
        
        <br>
        <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="buttons" style="display:block; text-align: center; border-color: #ff4444; color: #ff4444;">Cancelar</a>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>