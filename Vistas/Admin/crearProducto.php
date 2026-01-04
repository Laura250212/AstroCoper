<?php
$token = Security::generarTokenCSRF();

$page_title = "Nuevo Suministro - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="card" style="max-width: 600px;">
        <h2 style="color: #00ff00; margin-bottom: 30px; text-shadow: 0 0 10px #00ff00;">
            REGISTRAR NUEVO ÍTEM
        </h2>

        <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST" enctype="multipart/form-data" class="auth-form">
            
            <input type="hidden" name="action" value="guardarProducto">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            
            <div class="input-group">
                <label>Nombre del Producto:</label>
                <input type="text" name="nombre" required placeholder="Ej: Telescopio">
            </div>

            <div class="input-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="4" required style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white; border-radius: 8px;"></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="input-group" style="flex:1;">
                    <label>Precio (€):</label>
                    <input type="number" step="0.01" name="precio" required>
                </div>

                <div class="input-group" style="flex:1;">
                    <label>Stock:</label>
                    <input type="number" name="stock" required>
                </div>
            </div>

            <div class="input-group">
                <label>Categoría:</label>
                <select name="categoria" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white; border-radius: 8px;">
                    <option value="Telescopios">Telescopios</option>
                    <option value="Trajes">Trajes</option>
                    <option value="Accesorios">Accesorios</option>
                    <option value="Coleccionismo">Coleccionismo</option>
                </select>
            </div>

            <div class="input-group" style="margin-top: 20px; border: 1px dashed #00ffff; padding: 15px; border-radius: 10px;">
                <label style="color: #00ffff;">Imagen del Producto:</label>
                <input type="file" name="imagen" accept="image/*" required style="border: none; margin-top: 10px;">
            </div>

            <div style="display: flex; gap: 20px; margin-top: 30px;">
                <button type="submit" class="buttons" style="flex: 2; border-color: #00ff00; color: #00ff00;">
                    GUARDAR EN ALMACÉN
                </button>
                
                <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="buttons" style="flex: 1; border-color: #ff4444; color: #ff4444; text-align: center;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>