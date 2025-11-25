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
        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .label-container {
            width: 16.5cm;
            height: 10cm;
            padding: 0;
            margin: 0 auto;
            /* Centrar en la página */
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
            box-sizing: border-box;
            border: 2px dashed red;
            /* Borde visible para marcar la zona */
            background-color: #f9f9f9;
            /* Fondo suave */
        }

        .label-group {
            width: 48%;
            /* Cada grupo ocupa la mitad del espacio */
            display: flex;
            flex-direction: column;
            /* Cambiar a diseño vertical */
            justify-content: flex-start;
            align-items: center;
            /* Centrar el contenido horizontalmente */
            box-sizing: border-box;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .label {
            font-size: 14px;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 10px;
            /* Espaciado entre texto y QR */
        }

        .label-title {
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
            width: 100%;
        }

        .btn-imprimir {
            margin-top: 10px;
            padding: 10px;
            font-size: 14px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-imprimir:hover {
            background-color: #45a049;
        }

        /* Estilos para impresión */
        @media print {
            .btn-imprimir {
                display: none;
            }

            .label-container {
                border: 2px dashed black;
                /* Borde visible también en impresión */
                background-color: white;
                /* Fondo blanco para impresión */
            }
        }
    </style>

</head>

<body onload="window.print();">
    <div class="label-container">
        <!-- Primer grupo de datos y QR -->
        <div class="label-group">
            <div class="label">
                <h4>Proceso: <?= $datos_tarimas['pro_id']; ?> Tarima: <?= $datos_tarimas['tar_folio']; ?></h4>
                <h3>Fecha creación:</h3>
                <h3><?= $datos_tarimas['tar_fecha']; ?></h3>
            </div>
            <div class="qr-container">
                <?php echo $svg; ?>
            </div>
        </div>
        <!-- Segundo grupo de datos y QR -->
        <div class="label-group">
            <div class="label">
                <h4>Proceso: <?= $datos_tarimas['pro_id']; ?> Tarima: <?= $datos_tarimas['tar_folio']; ?></h4>
                <h3>Fecha creación:</h3>
                <h3><?= $datos_tarimas['tar_fecha']; ?></h3>
            </div>
            <div class="qr-container">
                <?php echo $svg; ?>
            </div>
        </div>
    </div>
</body>

</html>