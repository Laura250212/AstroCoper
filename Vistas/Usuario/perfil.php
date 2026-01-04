<?php
$token = Security::generarTokenCSRF();

$page_title = "Mi Perfil - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<div class="store-container">
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'guardado'): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Identificación Actualizada!',
                    text: 'Los datos de tu perfil han sido guardados correctamente.',
                    background: '#0b132b',
                    color: '#e0e0e0',
                    confirmButtonColor: '#00ffff',
                    confirmButtonText: '<span style="color: #000; font-weight: bold;">ENTENDIDO</span>',
                    iconColor: '#00ffff',
                   backdrop: `rgba(0,0,0,0.8)`
                });
            });
        </script>
    <?php endif; ?>

    <div class="card" style="max-width: 900px; text-align: left;">
        <h2 style="color: #00ffff; border-bottom: 2px solid #00ffff; padding-bottom: 10px; margin-bottom: 30px;">
            IDENTIFICACIÓN DEL PILOTO
        </h2>

        <form action="<?php echo RUTA_BASE; ?>/index.php" method="POST" enctype="multipart/form-data" class="auth-form" style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 40px;">
            
            <input type="hidden" name="action" value="guardarPerfil">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            
            <div style="flex: 1; min-width: 250px; text-align: center;">
                
                <div style="width: 200px; height: 200px; background-color: #000; border-radius: 50%; overflow: hidden; margin: 0 auto 20px; border: 4px solid #00ffff; box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);">
                    
                    <img src="<?php echo RUTA_BASE; ?>/index.php?action=verAvatar&id=<?php echo isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0; ?>" 
                         alt="Avatar" 
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/perfilDefecto.png'">
                </div>
                
                <label for="file-upload" class="buttons" style="font-size: 0.9rem; cursor: pointer;">
                    📷 Cambiar Foto
                </label>
                <input id="file-upload" type="file" name="foto" accept="image/*" style="display: none;" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                <p id="file-name" style="margin-top: 10px; color: #b0d4ff; font-size: 0.8rem;"></p>
            </div>

            <div style="flex: 2; min-width: 300px;">
                <div class="input-group">
                    <label>Nombre de Usuario:</label>
                    <input type="text" name="nombre" value="<?php echo isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : ''; ?>" required>
                </div>

                <div class="input-group">
                    <label>Correo Electrónico (No modificable):</label>
                    <input type="email" value="<?php echo isset($_SESSION['usuario_email']) ? htmlspecialchars($_SESSION['usuario_email']) : ''; ?>" disabled style="opacity: 0.7; cursor: not-allowed;">
                </div>

                <div class="input-group">
                    <label>Rol Asignado:</label>
                    <input type="text" value="<?php echo isset($_SESSION['usuario_rol']) ? strtoupper($_SESSION['usuario_rol']) : 'USUARIO'; ?>" disabled style="color: #00ff00; font-weight: bold; border-color: #00ff00;">
                </div>

                <div class="input-group">
                    <label>Nueva Contraseña (Dejar en blanco para no cambiar):</label>
                    <input type="password" name="password" placeholder="......">
                </div>

                <button type="submit" class="buttons" style="width: 100%; margin-top: 20px;">
                    GUARDAR CAMBIOS
                </button>
            </div>

        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>