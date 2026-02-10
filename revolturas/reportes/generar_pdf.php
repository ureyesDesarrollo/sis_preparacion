<?php
function generarPDF($url, $outputPath)
{
    $chromePath = '"C:\Program Files\Google\Chrome\Application\chrome.exe"';
    
    $comando = $chromePath . ' --headless --disable-gpu --no-pdf-header-footer --virtual-time-budget=5000 --print-to-pdf="' . $outputPath . '" "' . $url . '"';

    exec($comando, $output, $returnVar);

    echo "Comando ejecutado: $comando <br>";
    echo "Salida del comando: <pre>" . implode("\n", $output) . "</pre><br>";
    echo "Código de retorno: $returnVar <br>";

    return file_exists($outputPath);
}

function realizarSolicitudPost($action) {
    $url = "http://localhost/sis_preparacion/revolturas/reportes/reporte_inventario.controller.php";
    // Inicializa cURL
    $ch = curl_init();

    // Configura cURL para la solicitud POST
    curl_setopt($ch, CURLOPT_URL, $url);  // La URL del archivo PHP
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Para recibir la respuesta
    curl_setopt($ch, CURLOPT_POST, true);  // Indica que es una solicitud POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($action));  // Los datos a enviar

    // Configura para que la respuesta sea interpretada como JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    // Ejecuta la solicitud cURL y almacena la respuesta
    $response = curl_exec($ch);

    // Verifica si hay algún error con la solicitud cURL
    if (curl_errno($ch)) {
        // Devuelve el error si ocurre
        curl_close($ch);
        return ['error' => curl_error($ch)];
    }

    // Cierra la conexión cURL
    curl_close($ch);

    // Devuelve la respuesta decodificada en formato JSON
    return json_decode($response, true);
}

?>
