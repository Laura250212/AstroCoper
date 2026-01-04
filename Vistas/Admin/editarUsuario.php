<?php
$token = Security::generarTokenCSRF();

$page_title = "Editar Usuario - Panel Admin";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container" style="padding-top: 100px;">

    <div class="card" style="max-width: 600px; margin: 0 auto; padding: 2.5rem; border: 1px solid #ff00ff; box-shadow: 0 0 15px rgba(255, 0, 255, 0.2);">
        
        <h2 style="color: #ff00ff; margin-bottom: 2rem; text-align: center;">Editar Tripulante</h2>
        
        <?php if (!empty($mensaje_error)): ?>
            <div style="background: rgba(255, 0, 0, 0.1); border: 1px solid red; color: #ff5555; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center;">
                ⚠️ <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo RUTA_BASE; ?>/index.php">
            
            <input type="hidden" name="action" value="actualizarUsuario">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            
            <div style="margin-bottom: 15px;">
                <label style="color: #00ffff; display: block; margin-bottom: 5px;">Nombre de la cuenta</label>
                <input type="text" name="nombre" required 
                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                       style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="color: #00ffff; display: block; margin-bottom: 5px;">Correo Electrónico</label>
                <input type="email" name="email" required pattern=".+@.+\..+"
                       value="<?php echo htmlspecialchars($usuario['email']); ?>"
                       style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white;">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="color: #00ffff; display: block; margin-bottom: 5px;">Contraseña Nueva</label>
                <input type="password" name="password" placeholder="Dejar en blanco para mantener la actual"
                       style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white;">
                <small style="color: #aaa; font-size: 0.8rem;">* Si escribes algo aquí, la contraseña se cambiará.</small>
            </div>

            <button type="submit" class="buttons" style="width: 100%; padding: 12px; font-size: 1.1rem; cursor: pointer; background: rgba(255, 0, 255, 0.2); border-color: #ff00ff; color: #ff00ff;">
                💾 GUARDAR CAMBIOS
            </button>
        </form>

        <div style="margin-top: 2rem; border-top: 1px solid rgba(255, 0, 255, 0.2); padding-top: 1rem; text-align: center;">
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=gestionUsuarios" style="color: #aaa; text-decoration: none;">← Cancelar y volver a la lista</a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>