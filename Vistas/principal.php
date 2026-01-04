<?php
include 'Vistas/General/header.php'; 
?>

<div class="card">
      <p>Bienvenido al universo de la astronomía. Descubre los misterios del cosmos, 
         explora planetas distantes y aprende sobre las maravillas del espacio.</p>
      
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="welcome-message">
          <h3>¡Bienvenido!</h3>
          <p>Estás conectado como: <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? $_SESSION['usuario_email']); ?></strong></p>
        </div>
      <?php else: ?>
        <div class="registration-prompt">
          <h3>¡Únete a nuestra comunidad!</h3>
          <p>Personaliza tu experiencia astronómica</p>
        </div>
      <?php endif; ?>

      <section style="padding: 50px 20px; max-width: 1200px; margin: 0 auto;">
        
        <h2 style="text-align: center; margin-bottom: 40px; font-family: sans-serif; color: #a9d6e5;">
            Próximos Eventos Astronómicos
        </h2>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 25px;">
            
            <?php if(isset($eventos)): ?>
                <?php foreach($eventos as $evento): ?>
                    
                    <div style="
                        background-color: rgba(255, 255, 255, 0.05);
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        border-radius: 15px;
                        padding: 25px;
                        width: 300px;
                        text-align: center;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
                        transition: transform 0.3s ease;
                    ">
                        <div style="font-size: 3rem; margin-bottom: 15px;">
                            <?php echo $evento['icono']; ?>
                        </div>
                        
                        <h3 style="color: #61a5c2; margin-bottom: 10px; font-size: 1.2rem;">
                            <?php echo $evento['titulo']; ?>
                        </h3>
                        
                        <div style="
                            display: inline-block;
                            background-color: #2a6f97;
                            color: white;
                            padding: 5px 15px;
                            border-radius: 20px;
                            font-size: 0.9rem;
                            margin-bottom: 15px;
                            font-weight: bold;
                        ">
                            <?php echo $evento['fecha']; ?>
                        </div>
                        
                        <p style="color: #ccc; font-size: 0.95rem; line-height: 1.5;">
                            <?php echo $evento['descripcion']; ?>
                        </p>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>
      </section>
    </div>

    <div class="video-container">
      <div class="video-overlay">
        <div class="youtube-embed">
          <iframe 
            class="presentation-video"
            src="https://www.youtube.com/embed/x-AjWa27zr8?autoplay=1&mute=1&loop=1&playlist=x-AjWa27zr8&controls=1&modestbranding=1&rel=0" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe>
        </div>
      </div>
      <div class="video-caption">
        Explora el universo con AstroCoper - Un viaje por las estrellas y galaxias
      </div>
    </div>

</main>

<?php 
include 'Vistas/General/footer.php'; 
?>