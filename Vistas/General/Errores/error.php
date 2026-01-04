<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error del Sistema - AstroCoper</title>
    
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>/Vistas/General/css/estilos.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            /*Si todo falla, se ve este degradado*/
            background: radial-gradient(ellipse at bottom, #0b132b 0%, #000000 100%);
            font-family: 'Orbitron', sans-serif;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        @keyframes animStar {
            from { transform: translateY(0px); }
            to { transform: translateY(-2000px); }
        }

        #stars {
            width: 1px; height: 1px;
            background: transparent;
            box-shadow: 10vw 10vh #FFF, 20vw 80vh #FFF, 30vw 30vh #FFF, 80vw 10vh #FFF, 40vw 50vh #FFF, 60vw 90vh #FFF, 70vw 20vh #FFF, 15vw 60vh #FFF;
            animation: animStar 50s linear infinite;
            position: fixed; top: 0; left: 0; z-index: -3;
        }
        
        #stars2 {
            width: 2px; height: 2px;
            background: transparent;
            box-shadow: 15vw 20vh #FFF, 35vw 40vh #FFF, 55vw 60vh #FFF, 75vw 80vh #FFF, 25vw 90vh #FFF, 45vw 30vh #FFF;
            animation: animStar 100s linear infinite;
            position: fixed; top: 0; left: 0; z-index: -2; opacity: 0.8;
        }

        /*Repetir la animación*/
        #stars::after, #stars2::after {
            content: " "; position: absolute; top: 2000px;
            width: inherit; height: inherit; background: inherit; box-shadow: inherit;
        }

        /*Error*/
        .errorProducido {
            text-align: center;
            padding: 3rem 2rem;
            max-width: 600px;
            background: rgba(10, 15, 30, 0.85);
            border: 2px solid #00ffff;
            border-radius: 20px;
            box-shadow: 0 0 50px rgba(0, 255, 255, 0.4),
                        inset 0 0 30px rgba(0, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            margin: 2rem;
            position: relative;
            z-index: 10;
        }

        .errorProducido img {
            max-width: 180px;
            height: auto;
            margin-bottom: 1.5rem;
            border: 2px solid #00ffff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
            transition: all 0.4s ease;
        }

        .errorProducido img:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 0 40px rgba(0, 255, 255, 0.8);
        }

        .errorProducido h2 {
            color: #ff3333;
            font-size: 2rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 0 15px rgba(255, 51, 51, 0.8);
        }

        .errorProducido p {
            font-size: 1.2rem;
            color: #e0e0e0;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .buttons {
            display: inline-block;
            padding: 12px 30px;
            background: transparent;
            border: 2px solid #00ffff;
            border-radius: 10px;
            color: #00ffff;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
        }

        .buttons:hover {
            background: #00ffff;
            color: #000;
            box-shadow: 0 0 25px #00ffff;
            transform: translateY(-3px);
        }

        /*Particulas*/
        .particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: #00ffff;
            border-radius: 50%;
            animation: float 6s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body>

    <div id="stars"></div>
    <div id="stars2"></div>
    
    <div class="errorProducido">
        <img src="<?php echo RUTA_BASE; ?>/IMGS/error.jpg" alt="Error del sistema">
        
        <h2>Error detectado</h2>
        
        <p><?php echo htmlspecialchars($mensaje); ?></p>
        
        <a href='<?php echo RUTA_BASE; ?>/index.php?action=mostrarPrincipal' class="buttons">
            Volver al inicio
        </a>
    </div>

    <script>
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'particles';
        document.body.appendChild(particlesContainer);
        
        for (let i = 0; i < 60; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.animationDelay = Math.random() * 5 + 's';
            particle.style.animationDuration = (Math.random() * 4 + 3) + 's';
            
            const size = Math.random() * 3 + 1;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            
            particle.style.opacity = Math.random() * 0.7 + 0.3;
            
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>