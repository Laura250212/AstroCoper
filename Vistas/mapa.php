<?php
$page_title = "Mapa Estelar - AstroCoper";
include_once __DIR__ . '/../General/header.php';
?>

<link rel="stylesheet" href="https://aladin.u-strasbg.fr/AladinLite/api/v3/latest/aladin.min.css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://aladin.u-strasbg.fr/AladinLite/api/v3/latest/aladin.min.js"></script>

<div class="store-container" style="text-align: center;">
    <h2 class="store-title">EXPLORADOR DEL COSMOS</h2>
    <p style="color: #e0e0e0;">Explora el universo real. Usa el ratón para moverte y la rueda para hacer zoom.</p>
    
    <div id="aladin-lite-div" style="width: 100%; height: 600px; margin: 0 auto; border: 2px solid #00ffff; box-shadow: 0 0 20px rgba(0,255,255,0.3);"></div>
    
    <script type="text/javascript">
        let aladin;
        A.init.then(() => {
            aladin = A.aladin('#aladin-lite-div', {
                survey: "P/DSS2/color", //Encuesta visual a color
                fov: 60, //Campo de visión inicial
                target: "M42" //Empieza mirando a la Nebulosa de Orión
            });
        });
    </script>
</div>

<?php include_once __DIR__ . '/../General/footer.php'; ?>