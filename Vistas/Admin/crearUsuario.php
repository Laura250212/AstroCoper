<?php
$token = Security::generarTokenCSRF();

$page_title = "Añadir Usuario - Panel Admin";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container" style="padding-top: 100px;"> 
    <div class="card" style="max-width: 500px; margin: 0 auto; padding: 2.5rem; border: 1px solid #ff00ff; box-shadow: 0 0 15px rgba(255, 0, 255, 0.2);">
        
        <h2 style="color: #ff00ff; margin-bottom: 2rem; text-align: center;">Reclutar Nuevo Tripulante</h2>
        
        <?php if (!empty($mensaje_error)): ?>
            <div style="background: rgba(255, 0, 0, 0.1); border: 1px solid red; color: #ff5555; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center;">
                ⚠️ <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensaje_exito)): ?>
            <div style="background: rgba(0, 255, 0, 0.1); border: 1px solid #00ff00; color: #00ff00; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center;">
                ✅ <?php echo htmlspecialchars($mensaje_exito); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo RUTA_BASE; ?>/index.php">
            
            <input type="hidden" name="action" value="crearUsuario">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            
            <div style="margin-bottom: 15px;">
                <label style="color: #00ffff; display: block; margin-bottom: 5px;">Nombre de la cuenta</label>
                <input type="text" name="nombre" placeholder="Ej: Pepito" required 
                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>"
                       style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="color: #00ffff; display: block; margin-bottom: 5px;">Correo Electrónico</label>
                <input type="email" name="email" placeholder="email@astrocoper.com" required pattern=".+@.+\..+"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white;">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="color: #00ffff; display: block; margin-bottom: 5px;">Contraseña Provisional</label>
                <input type="password" name="password" placeholder="••••••••" required
                       style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid #00ffff; color: white;">
            </div>

            <button type="submit" class="buttons" style="width: 100%; padding: 12px; font-size: 1.1rem; cursor: pointer; background: rgba(255, 0, 255, 0.2); border-color: #ff00ff; color: #ff00ff;">
             CREAR USUARIO
            </button>
        </form>

        <div style="margin-top: 2rem; border-top: 1px solid rgba(255, 0, 255, 0.2); padding-top: 1rem; text-align: center;">
            <a href="<?php echo RUTA_BASE; ?>/index.php?action=gestionUsuarios" style="color: #aaa; text-decoration: none;">← Cancelar y volver a la lista</a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>