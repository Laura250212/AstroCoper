<?php
$page_title = "Mapa Estelar - AstroCoper";
include_once __DIR__ . '/../Vistas/General/header.php';
?>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://aladin.u-strasbg.fr/AladinLite/api/v2/latest/aladin.min.css" />
<script type="text/javascript" src="https://aladin.u-strasbg.fr/AladinLite/api/v2/latest/aladin.min.js"></script>

<div class="store-container" style="text-align: center;">
    <h2 class="store-title">EXPLORADOR DEL COSMOS</h2>
    <p style="color: #e0e0e0;">Explora el universo real. Usa el ratón para moverte y la rueda para hacer zoom.</p>
    
    <div id="aladin-lite-div" style="
        width: 100%; 
        height: 600px; 
        margin: 0 auto; 
        border: 2px solid #00ffff; 
        box-shadow: 0 0 20px rgba(0,255,255,0.3);
        background-color: #000;
        background-image: url('<?= RUTA_BASE ?>/IMGS/eso1205a.jpg'); 
        background-size: cover;
        background-position: center;">
    </div>
    
    <div style="margin-top: 15px;">
        <a href="https://aladin.u-strasbg.fr/AladinLite/" target="_blank" class="buttons" style="font-size: 0.8rem; padding: 5px 10px; opacity: 0.7;">
            Abrir en servidor externo
        </a>
    </div>

    <script type="text/javascript">
        //Intenta cargar el mapa
        try {
            var aladin = A.aladin('#aladin-lite-div', {
                survey: "P/DSS2/color", 
                fov: 60,                
                target: "M42"           
            });
        } catch (error) {
            console.log("El mapa no pudo cargar, mostrando imagen de respaldo.");
        }
    </script>
</div>

<?php include_once __DIR__ . '/../Vistas/General/footer.php'; ?>