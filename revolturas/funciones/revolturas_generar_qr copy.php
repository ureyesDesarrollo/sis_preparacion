<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

include "../../conexion/conexion.php";
include "../../assets/barcode/barcode.php";
$generator = new barcode_generator();
$rev_id = $_GET['rev_id'];

$cnx =  Conectarse();
try {
    $listado_revolturas = mysqli_query(
        $cnx,
        "SELECT rev_folio FROM rev_revolturas WHERE rev_id = '$rev_id'"
    );

    $datos_revolturas = mysqli_fetch_assoc($listado_revolturas);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    mysqli_close($cnx);
}

$currentDir = dirname($_SERVER['REQUEST_URI']);

$url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;

//$url = 'http://192.168.100.8:80' . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;

$svg = $generator->render_svg('qr', $url, '');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Tarima</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            /* Asegura que el contenedor tenga el mismo ancho que el QR */
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
        }

        .barcode-svg {
            width: 300px;
            /* Asegura que el QR tenga el mismo ancho que el contenedor */
            height: 300px;
            /* Cambia este valor para ajustar el tamaño */
        }

        .barcode-svg svg {
            width: 100%;
            height: 100%;
        }

        .label {
            margin-top: 10px;
            color: #888888;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Revoltura: <?= $datos_revolturas['rev_folio']; ?></h1>
        <div class="barcode-svg">
            <?php echo $svg; ?>
        </div>
        <div class="label">QR Revoltura</div>
    </div>
</body>

</html>