<?php
$token = Security::generarTokenCSRF();

$page_title = "Editar Constelación - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    <div class="card" style="max-width: 600px;">
        <h2 style="color: #ffd700; margin-bottom: 20px;">EDITAR CONSTELACIÓN</h2>

        <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST" enctype="multipart/form-data" class="auth-form">
            
            <input type="hidden" name="action" value="actualizarConstelacion">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            <input type="hidden" name="id" value="<?php echo $constelacion['id']; ?>">

            <div class="input-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($constelacion['nombre']); ?>" required>
            </div>

            <div class="input-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="5" required style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #ffd700; color: white; border-radius: 8px;"><?php echo htmlspecialchars($constelacion['descripcion']); ?></textarea>
            </div>

            <div class="input-group" style="margin-top: 20px;">
                <label>Imagen Actual:</label><br>
                <img src="<?php echo RUTA_BASE; ?>/index.php?action=verImagenConstelacion&id=<?php echo $constelacion['id']; ?>" 
                     alt="Constelación actual"
                     style="width: 100px; margin: 10px 0; border: 1px solid #ffd700;"
                     onerror="this.style.display='none'">
                <br>
                <label style="color: #ffd700; font-size: 0.9rem;">Subir nueva (Dejar vacío para mantener):</label>
                <input type="file" name="imagen" accept="image/*" style="border: none; margin-top: 5px;">
            </div>

            <button type="submit" class="buttons" style="width: 100%; margin-top: 20px; border-color: #ffd700; color: #ffd700;">
                ACTUALIZAR
            </button>
        </form>
        
        <br>
        <a href="<?php echo RUTA_BASE; ?>/index.php?action=panelAdmin" class="buttons" style="display:block; text-align: center; border-color: #ff4444; color: #ff4444;">
                Cancelar
        </a>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>