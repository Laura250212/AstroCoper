<?php
$token = Security::generarTokenCSRF();

$page_title = "Nueva Constelación - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="card" style="max-width: 600px;">
        <h2 style="color: #ffd700; margin-bottom: 20px; text-shadow: 0 0 10px #ffd700;">
            REGISTRAR NUEVA CONSTELACIÓN
        </h2>

        <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST" enctype="multipart/form-data" class="auth-form">
            
            <input type="hidden" name="action" value="guardarConstelacion">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            
            <div class="input-group">
                <label>Nombre de la Constelación:</label>
                <input type="text" name="nombre" required placeholder="Ej: Orión, Osa Mayor..." style="border-color: #ffd700;">
            </div>

            <div class="input-group">
                <label>Descripción / Épocas:</label>
                <textarea name="descripcion" rows="5" required style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #ffd700; color: white; border-radius: 8px;" placeholder="Escribir aquí la información"></textarea>
            </div>

            <div class="input-group" style="margin-top: 20px; border: 1px dashed #ffd700; padding: 15px; border-radius: 10px;">
                <label style="color: #ffd700;">Mapa o Imagen:</label>
                <input type="file" name="imagen" accept="image/*" required style="border: none; margin-top: 10px;">
            </div>

            <button type="submit" class="buttons" style="width: 100%; margin-top: 20px; border-color: #ffd700; color: #ffd700;">
                GUARDAR EN LA GUÍA
            </button>
            
        </form>

        <br>
        <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="buttons" style="display:block; text-align: center; border-color: #ff4444; color: #ff4444;">
            Cancelar
        </a>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>