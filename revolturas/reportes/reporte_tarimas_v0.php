<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

$cad = mysqli_query($cnx, "SELECT * FROM rev_tarimas");
$res = mysqli_fetch_assoc($cad);
$totalTarimas = mysqli_num_rows($cad);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte diario de lotes y revoturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/jquery.min.js"></script>
    <!--DATATABLES-->
    <script src=../assets/datatable/jquery.dataTables.min.js></script>
    <script src=../assets/datatable/dataTables.bootstrap5.min.js></script>

    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <?php
    date_default_timezone_set('America/Chihuahua');
    ?>
</head>

<body>
    <div class="container-fluid">
        <table id="encabezado" class="table table-bordered">
            <tr>
                <td><img src="../../imagenes/logo_progel_v3.png" alt=""></td>
                <td style="text-align:center">REPORTE DIARIO DE LOTES Y REVOLTURAS <span style="float:right"><?php echo date('H:i:s'); ?></span></td>
                <td style="text-align:center">LAB F009 - REV 004</td>
            </tr>
        </table>

        <!-- tabla tarimas -->
        <table class="table table-bordered">
            <thead style="background: #000">
                <tr>
                    <th>FECHA</th>
                    <th></th>
                    <th>BLOOM</th>
                    <th>VISC.</th>
                    <th>PH FINAL</th>
                    <th>TRANS.</th>
                    <th>%T(620)</th>
                    <th>NTU</th>
                    <th>HUMEDAD</th>
                    <th>CENIZAS</th>
                    <th>CONDUCT.</th>
                    <th>REDOX</th>
                    <th>COLOR</th>
                    <th>FINO</th>
                    <th>OLOR</th>
                    <th>P.E EN 1 KG</th>
                    <th>PART. EXTRAÑAS</th>
                    <th>PART. IND. 6,66%</th>
                    <th>HIDRATACION</th>
                    <th>ACEPTADO</th>
                    <th>RECHAZADO</th>
                </tr>
                <tr>
                    <th></th>
                    <th>LÍMITES DE PARAMETROS</th>
                    <th>MIN 200</th>
                    <th>MIN. 25-60 MAX.</th>
                    <th>5.5-6.0</th>
                    <th>MIN 18</th>
                    <th>70% MIN.</th>
                    <th>80 MAX.</th>
                    <th>MIN. 9-12% MAX</th>
                    <th>1.6% MAX</th>
                    <th>
                        < 4mS </th>
                    <th>30 PPM MAX</th>
                    <th>3 MAX.</th>
                    <th>
                        <= 5%</th>
                    <th>SIN OLOR EXTRAÑO</th>
                    <th>MAX 10 PART-</th>
                    <th>0-25 MAX.</th>
                    <th>MAXIMO 10 GRANOS</th>
                    <th>MAL-BIEN</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php do { ?>
                    <tr>
                        <td><?php echo $res['tar_fecha']; ?></td>
                        <td><?php echo $res['pro_id'] . " - " . $res['tar_folio']; ?></td>
                        <td style="color: <?= ($res['tar_bloom'] < 200.00) ? 'red' : '' ?> "><?= $res['tar_bloom'] ?></td>
                        <td style="color: <?= ($res['tar_viscosidad'] < 25.00 || $res['tar_viscosidad'] > 60) ? 'red' : '' ?> "><?= $res['tar_viscosidad'] ?></td>
                        <td style="color: <?= ($res['tar_ph'] < 5.5 || $res['tar_ph'] > 6.0) ? 'red' : '' ?> "><?= $res['tar_ph'] ?></td>
                        <td style="color: <?= ($res['tar_transparencia'] < 18) ? 'red' : '' ?> "><?= $res['tar_transparencia'] ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: <?= ($res['tar_redox'] > 30.00) ? 'red' : '' ?> "><?= $res['tar_redox'] ?></td>
                        <td style="color: <?= ($res['tar_color'] > 3.00) ? 'red' : '' ?> "><?= $res['tar_color'] ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="color: <?= ($res['tar_rechazado'] != 'R') ? '' : 'red' ?> "><?= ($res['tar_rechazado'] != 'R') ? 'X' : '' ?></td>
                        <td style="color: <?= ($res['tar_rechazado'] == 'R') ? 'red' : '' ?> "><?= ($res['tar_rechazado'] == 'R') ? 'X' : '' ?></td>
                    </tr>
                <?php
                } while ($res = mysqli_fetch_assoc($cad));


                for ($i = 0; $i <= 10; $i++) { ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>TABLA DE COLOR/OLOR</th>
                            <th>CAL./COLOR</th>
                            <th>CAL./OLOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>EXCELENTE/SIN OLOR</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>MUY BIEN/CARÁCTERISTICO</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>BIEN/LIGERO</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>ACEPTABLE/ACENTUADO</td>
                            <td>3</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>MAL/MUY ACENTUADO</td>
                            <td>4</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>MUY MAL/INTENSO</td>
                            <td>5</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4" style="text-align:center;">
                <table class="table table-bordered" style="vertical-align:bottom">
                    <tr style="height: 50px;">
                        <th>______________________________________</th>
                        <th>______________________________________</th>
                    </tr>
                    <tr>
                        <th>REVISO</th>
                        <th>AUTORIZO</th>
                    </tr>
                    <tr>
                        <th colspan="2">OBSERVACIONES</th>
                    </tr>
                    <tr>
                        <td style="height: 110px;" colspan="2"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
<style>
    table:not(#encabezado) {
        font-size: 10px;
    }

    .table thead {
        --bs-table-bg: #F9F6F6;
    }

    img {
        width: 20%;
    }
</style>