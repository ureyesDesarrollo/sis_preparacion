<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../conexion/conexion.php";
include "../../assets/barcode/barcode.php";
$generator = new barcode_generator();
$tar_id = $_GET['tar_id'];

$cnx =  Conectarse();
try {
    $listado_tarimas = mysqli_query(
        $cnx,
        "SELECT tar_folio, pro_id, tar_fecha FROM rev_tarimas WHERE tar_id = '$tar_id'"
    );

    $datos_tarimas = mysqli_fetch_assoc($listado_tarimas);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    mysqli_close($cnx);
}

$currentDir = dirname($_SERVER['REQUEST_URI']);

$url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/tarimas_detalle.php?tar_id=' . $tar_id;

//$url = 'http://10.16.68.63:8080' . $currentDir . '/tarimas_detalle.php?tar_id=' . $tar_id;

$svg = $generator->render_svg('qr', $url, '');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Tarima</title>
    <style>
        @media print {
            @page {
                margin: 0;
                /* Márgenes mínimos */
                size: auto;
                /* Ajustar automáticamente al tamaño del contenido */
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: Arial, sans-serif;
            }
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            /* Asegura que el contenedor tenga el mismo ancho que el QR */
        }

        h1 {
            margin: 0 0 20px 0;
            text-align: center;
            font-size: 18px;
            /* Reduce el tamaño del texto si es necesario */
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
            text-align: center;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container">
        <h1>Proceso: <?= $datos_tarimas['pro_id']; ?> Tarima: <?= $datos_tarimas['tar_folio']; ?></h1>
        <h4>Fecha creación: <?= $datos_tarimas['tar_fecha']; ?></h4>
        <div class="barcode-svg">
            <?php echo $svg; ?>
        </div>
        <div class="label">QR Tarima</div>
    </div>
</body>

</html>