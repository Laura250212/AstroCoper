<?php
$page_title = "Mapa Estelar - AstroCoper";
include_once __DIR__ . '/../Vistas/General/header.php';
?>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://aladin.u-strasbg.fr/AladinLite/api/v2/latest/aladin.min.css" />
<script type="text/javascript" src="https://aladin.u-strasbg.fr/AladinLite/api/v2/latest/aladin.min.js"></script>

<div class="store-container" style="text-align: center;">
    <h2 class="store-title">EXPLORADOR DEL COSMOS</h2>
    <p style="color: #e0e0e0;">Explora el universo real. Usa el ratón para moverte y la rueda para hacer zoom. Por favor, haz las búsquedas en <b>Inglés</b> o <b>Latín</b></p>
    
    <div id="mission-panel" style="background: rgba(0, 0, 0, 0.8); color: white; padding: 15px; border-radius: 10px; border: 1px solid #00d2ff; margin-bottom: 10px; font-family: sans-serif;">
    <h3 style="margin-top: 0; color: #00d2ff;">Misión de Exploración</h3>
    <p>Objetivo: <strong id="mission-target" style="font-size: 1.2em; color: #ffeb3b;">Cargando misión...</strong></p>
    <p id="mission-hint" style="font-style: italic; font-size: 0.9em; color: #ccc;">...</p>
    <div style="margin-top: 10px; font-size: 0.8em;">
        Estado: <span id="mission-status">Escaneando coordenadas...</span>
    </div>
</div>

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



//Minijuego

//Lista de Misiones
const misiones = [
    { 
        nombre: "Galaxia de Andrómeda (M31)", 
        ra: 10.6847, 
        dec: 41.2687, 
        pista: "Es nuestra vecina galáctica más famosa. Busca 'M31' en el buscador.",
        info: "¡Genial! Andrómeda está a 2.5 millones de años luz y viene hacia nosotros."
    },
    { 
        nombre: "Nebulosa de Orión (M42)", 
        ra: 83.82, 
        dec: -5.39, 
        pista: "Una fábrica de estrellas en el cinturón del cazador. Busca 'M42'.",
        info: "¡Espectacular! Ahí nacen estrellas nuevas ahora mismo."
    },
    { 
        nombre: "Pléyades (M45)", 
        ra: 56.75, 
        dec: 24.11, 
        pista: "Las siete hermanas azules. Busca 'M45'.",
        info: "¡Lo encontraste! Es un cúmulo abierto muy joven y caliente."
    }
];

let misionActual = 0; //Ir a por la primera

//Función para actualizar el panel
function actualizarPanel() {
    if (misionActual < misiones.length) {
        document.getElementById('mission-target').innerText = misiones[misionActual].nombre;
        document.getElementById('mission-hint').innerText = "Pista: " + misiones[misionActual].pista;
        document.getElementById('mission-status').innerText = "Escaneando...";
        document.getElementById('mission-status').style.color = "white";
    } else {
        //Juego terminado
        document.getElementById('mission-panel').innerHTML = 
            "<h3 style='color:#4CAF50'>¡JUEGO COMPLETADO!</h3><p>Has encontrado todos los objetivos. Eres un explorador experto.</p>";
    }
}

   //El Radar, comprueba cada segundo dónde está mirando el usuario
    setInterval(function() {
     if (misionActual >= misiones.length) return; //Si ya acabó, no hace nada

    //Dónde mira el usuario (centro de la pantalla)
        let centro = aladin.getRaDec(); //Devuelve [ra, dec]
        let objetivo = misiones[misionActual];

    //Cálculo de la distancia
        let distRA = Math.abs(centro[0] - objetivo.ra);
        let distDEC = Math.abs(centro[1] - objetivo.dec);
    
    //Si la distancia es menor a 1 grado (está muy cerca)
        if (distRA < 1 && distDEC < 1) {
            completarMision();
        }

    }, 2000); //Comprueba cada 2 segundos para no saturar

    //Ganar
        function completarMision() {
        let mision = misiones[misionActual];
    
    //Lanza alerta
        Swal.fire({
            title: '¡OBJETIVO LOCALIZADO!',
            text: mision.info,
            icon: 'success',
            confirmButtonText: 'Siguiente Misión'
        }).then((result) => {
            misionActual++; //Siguiente
            actualizarPanel(); //Actualizar texto
        });
    }

//Inicializar el panel al cargar
    actualizarPanel();

    </script>
</div>

<?php include_once __DIR__ . '/../Vistas/General/footer.php'; ?>