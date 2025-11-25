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
        "SELECT tar_folio, pro_id, tar_fecha,tar_fino FROM rev_tarimas WHERE tar_id = '$tar_id'"
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
            padding: 0rem;
            /* width: 100%; */
            display: flex;
            flex-direction: row;
            /* Cambia a fila */
            justify-content: space-between;
            text-align: center;
            box-sizing: border-box;
            border: 2px dashed red;
        }


        .label-group {
            width: 48%;
            height: 100%;
            border: 1px solid #ccc;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }


        .qr-container {
            width: 100%;
            height: 50%;
        }

        @media print {
            body {
                margin: 0rem;
                padding: 0;
            }

            .label-container {
                width: 100%;
                height: auto;
                /* Permite que se ajuste a la altura del contenido */
                transform: rotate(0deg);
                /* No rotamos en la impresión */
                font-size: 12pt;
                /* Tamaño de fuente ajustable */
            }


            @page {
                size: 16.5cm 10cm;
                /* Tamaño de la etiqueta */
                margin: 0;
                /* Sin márgenes */
            }
        }
    </style>
</head>

<body>
    <div class="label-container">
        <?php for ($i = 0; $i < 2; $i++) { ?>
            <div class="label-group">
                <h4>Proceso: <?= $datos_tarimas['pro_id']; ?> Tarima: <?= $datos_tarimas['tar_folio']; ?></h4>
                <h4>Fecha creación: <?= $datos_tarimas['tar_fecha']; ?></h4>
                <?php
                if ($datos_tarimas['tar_fino'] == 'F') {
                    echo '<br>FINO</br>';
                } else {
                    echo '';
                } ?>
                <div class="qr-Fontainer">
                    <?php echo $svg; ?>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>