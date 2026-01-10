<?php
/* Esta página gestiona la Imagen Astronómica del Día (APOD) de la NASA */

//Función para realizar peticiones cURL
function realizarPeticionCurl($url) {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 3, 
      CURLOPT_CONNECTTIMEOUT => 2, //Tiempo máximo para conectar
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_USERAGENT => 'AstroCoper/1.0',
      CURLOPT_FAILONERROR => true
  ]);

    $respuesta = curl_exec($ch);
    $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($codigoHttp === 200 && $respuesta) {
        return json_decode($respuesta, true);
    }
    return false;
}

//Intentos para prevenir fallos o situaciones inesperadas
$datos = false; 

//1.Api Key normal
$apiKey = getenv('NASA_API_KEY');
if (!$apiKey) {
    $apiKey = "xqylh1f3fKTw4G4aEarRK8uSNJpznIxFgnuexD5O";
}

$urlPrincipal = "https://api.nasa.gov/planetary/apod?api_key=" . $apiKey;
$datos = realizarPeticionCurl($urlPrincipal);

//2.DEMO KEY
if (!$datos) {
    $claveDemo = "DEMO_KEY";
    $urlDemo = "https://api.nasa.gov/planetary/apod?api_key=" . $claveDemo;
    $datos = realizarPeticionCurl($urlDemo);
}

//3.Fecha pasada
if (!$datos) {
    $fechaRespaldo = "2025-02-05"; 
    $urlRespaldo = "https://api.nasa.gov/planetary/apod?api_key=" . $apiKey . "&date=" . $fechaRespaldo;
    $datos = realizarPeticionCurl($urlRespaldo);
    
    if ($datos) {
        $datos['es_fecha_respaldo'] = true;
    }
}

//4.APOD predeterminado
if (file_exists(__DIR__ . '/../IMGS/apod_resplado.png')) {
  $datos = [
      'title' => 'Jupiter with the Great Red Spot',
      'explanation' => ' Jupiter reaches its 2026 opposition today. That puts our Solar System s most massive planet opposite the Sun and near its closest and brightest for viewing from planet Earth. In fact, captured only 3 days ago this sharp telescopic snapshot reveals excellent details of the ruling gas giant s swirling cloudtops, in light zones and dark belts girdling the rapidly rotating outer planet. Jupiter s famous, persistent anticyclonic vortex, known as the Great Red Spot, is south of the equator at the lower right. But two smaller red spots are also visible, one near the top in the northernmost zone, and one close to Jupiter s south pole. And while Jupiter s Great Red Spot is known to be shrinking, it s still about the size of the Earth itself. ',
      'url' => 'IMGS/apod_resplado.png',
      'media_type' => 'image',
      'date' => date('Y-m-d'),
      'copyright' => 'NASA / Backup System'
  ];
}

//5.Imagen de local
if (!$datos || !isset($datos['title']) || !isset($datos['url'])) {
    $datos = [
        'title' => 'Comunicación Interrumpida',
        'date' => date('Y-m-d'),
        'explanation' => 'No hemos podido establecer contacto con los satélites de la NASA. Disfruta de esta imagen de archivo mientras restablecemos la conexión con la base.',
        'url' => RUTA_BASE . '/IMGS/imgDiaError.jpg',
        'media_type' => 'image',
        'es_fallback_total' => true
    ];
}

//Traducciones
$traducciones = [
    'es' => [
        'titulo_web' => 'AstroCoper - NASA APOD',
        'titulo_apod' => 'Imagen Astronómica del Día',
        'error_titulo' => 'Problema de Conexión',
        'error_mensaje' => 'No se pudo conectar con la NASA.',
        'mensaje_tecnico' => 'Detalle técnico',
        'footer_acerca' => 'AstroCoper',
        'footer_desc' => 'Explorando el universo desde la Tierra.',
        'aviso_fallback' => 'Mostrando imagen de archivo por problemas de conexión',
        'aviso_fecha_respaldo' => 'Mostrando una imagen histórica destacada (Conexión limitada)'
    ],
    'en' => [
        'titulo_web' => 'AstroCoper - NASA APOD',
        'titulo_apod' => 'Astronomy Picture of the Day',
        'error_titulo' => 'Connection Issue',
        'error_mensaje' => 'Could not connect to NASA.',
        'mensaje_tecnico' => 'Technical detail',
        'footer_acerca' => 'AstroCoper',
        'footer_desc' => 'Exploring the universe from Earth.',
        'aviso_fallback' => 'Showing archive image due to connection issues',
        'aviso_fecha_respaldo' => 'Showing a highlighted historical image'
    ],
    'fr' => [
        'titulo_web' => 'AstroCoper - NASA APOD',
        'titulo_apod' => 'Image Astronomique du Jour',
        'error_titulo' => 'Problème de Connexion',
        'error_mensaje' => 'Impossible de se connecter à la NASA.',
        'mensaje_tecnico' => 'Détail technique',
        'footer_acerca' => 'AstroCoper',
        'footer_desc' => 'Explorer l\'univers depuis la Terre.',
        'aviso_fallback' => 'Affichage d\'une image d\'archive en raison de problèmes de connexion',
        'aviso_fecha_respaldo' => 'Affichage d\'une image historique mise en évidence'
    ]
];

//Obtener idioma
$idioma = isset($_GET['lang']) && array_key_exists($_GET['lang'], $traducciones) ? $_GET['lang'] : 'es';
$t = $traducciones[$idioma];

//Traduccion automatica
if (isset($datos['title']) && !isset($datos['es_fallback_total']) && $idioma !== 'en') {
    
    function traducirTexto($texto, $idiomaDestino) {
        if ($idiomaDestino === 'en') return $texto;
        
        if (strlen($texto) > 450) {
            $texto = substr($texto, 0, 450);
            $ultimoPunto = strrpos($texto, '.');
            if ($ultimoPunto !== false) {
                $texto = substr($texto, 0, $ultimoPunto + 1);
            }
        }
        
        //API de traduccion
        $apiUrl = "https://api.mymemory.translated.net/get?q=" . urlencode($texto) . "&langpair=en|$idiomaDestino";
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'AstroCoper/1.0'
        ]);
        $respuesta = curl_exec($ch);
        $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($codigoHttp === 200) {
            $datosTrans = json_decode($respuesta, true);
            if (isset($datosTrans['responseData']['translatedText'])) {
                return $datosTrans['responseData']['translatedText'];
            }
        }
        return $texto . " [Traducción no disponible]";
    }

    $datos['title'] = traducirTexto($datos['title'], $idioma);
    $datos['explanation'] = traducirTexto($datos['explanation'], $idioma);
    $datos['es_traducido'] = true;
}


//Configurar el título para el Header
$page_title = $t['titulo_web'];
include __DIR__ . '/../Vistas/General/header.php';
?>

  <div class="language-selector" style="text-align: right; padding: 15px; max-width: 1200px; margin: 0 auto;">
    <button class="lang-btn <?php echo $idioma === 'es' ? 'active' : ''; ?>" onclick="cambiarIdioma('es')">ES</button>
    <button class="lang-btn <?php echo $idioma === 'en' ? 'active' : ''; ?>" onclick="cambiarIdioma('en')">EN</button>
    <button class="lang-btn <?php echo $idioma === 'fr' ? 'active' : ''; ?>" onclick="cambiarIdioma('fr')">FR</button>
  </div>

  <div class="main-content">
    <div class="nasa-apod-container">
        
      <?php if (isset($datos['es_fallback_total'])): ?>
        <div class="fallback-notice" style="background: rgba(255, 0, 0, 0.2); padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid red; color: #ffaaaa;">
          <p><strong><?php echo $t['aviso_fallback']; ?></strong></p>
        </div>

      <?php elseif (isset($datos['es_fecha_respaldo'])): ?>
        <div class="fallback-notice" style="background: rgba(255, 165, 0, 0.2); padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid orange; color: #ffd700;">
          <p><strong><?php echo $t['aviso_fecha_respaldo']; ?></strong></p>
        </div>
      <?php endif; ?>

      <?php if (isset($datos['es_traducido'])): ?>
          <div class="translation-notice">
            <p><strong>Contenido traducido automáticamente</strong></p>
            <p><small>La traducción puede contener imprecisiones. <a href="?lang=en&action=imgDia" style="color: #00ffff;">Ver original (Inglés)</a></small></p>
          </div>
      <?php endif; ?>
        
      <h1 class='nasa-apod-title'><?php echo htmlspecialchars($datos['title']); ?></h1>
      <p class='nasa-apod-date'><?php echo htmlspecialchars($datos['date']); ?></p>
      <p class='nasa-apod-explanation'><?php echo nl2br(htmlspecialchars($datos['explanation'])); ?></p>
        
      <div class='nasa-apod-media'>
          <?php if ($datos['media_type'] === 'image'): ?>
            <img src='<?php echo htmlspecialchars($datos['url']); ?>' alt='NASA Astronomy Picture' class='nasa-apod-image'
            onerror="this.src='<?php echo RUTA_BASE; ?>/IMGS/imgDiaError.jpg'">
          <?php else: ?>
            <iframe src='<?php echo htmlspecialchars($datos['url']); ?>' class='nasa-apod-video' frameborder='0' allowfullscreen></iframe>
          <?php endif; ?>
      </div>

    </div>
  </div>

  <script>
    function cambiarIdioma(lang) {
      const url = new URL(window.location.href);
      url.searchParams.set('lang', lang);
      //Asegurar que no se pierda la acción 'imgDia' al cambiar idioma
      if (!url.searchParams.has('action')) {
          url.searchParams.set('action', 'imgDia');
      }
      window.location.href = url.toString();
    }
  </script>

<?php 
include __DIR__ . '/../Vistas/General/footer.php'; 
?>