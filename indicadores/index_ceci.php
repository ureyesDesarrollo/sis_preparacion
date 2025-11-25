<?php
/*Desarrollado por: CCA Technologies */
/*Contacto: ccastillo@ccaconsultoresti.com */
/*12 - Septiembre - 2023*/
include "../conexion/conexion.php";
/* include "../funciones/funciones.php";*/
include "../seguridad/user_seguridad.php";
$cnx =  Conectarse();


//lavadores 
$cad_lav = mysqli_query($cnx, "SELECT *	FROM preparacion_lavadores WHERE pl_id >= 1 and pl_id <= 12 ") or die(mysqli_error($cnx) . "Error: en consultar los lavadores");
$reg_lav = mysqli_fetch_assoc($cad_lav);
$tot_lav = mysqli_num_rows($cad_lav);


$cad_tipo_eq = mysqli_query($cnx, "SELECT * FROM equipos_tipos") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_eq = mysqli_fetch_assoc($cad_tipo_eq);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indicadores</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="../assets/fontawesome/fontawesome.js"></script>
    <link rel="stylesheet" href="../assets/css/indicadores.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7"></div>
            <div class="col-md-3">
                <h4>
                    <div class="col titulo_indicadores">TABLERO DE INDICADORES</div>
                </h4>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2">
                <img src="../imagenes/logo_progel_v5.png" alt="">
            </div>

        </div>

        <!-- Lavadores -->
        <div class="row">
            <?php
            do {
                $strEstilo_img = fnc_imagen_lavador($reg_lav['le_id']); //imagen
                $strEstilo = fnc_estilo_lavador($reg_lav['le_id']); //color
            ?>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-1 indicador">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php echo $strEstilo_img; ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="<?php echo $strEstilo; ?>">
                        <?php echo $reg_lav['pl_descripcion'] ?>(etapa)
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 detalle_indicadores">
                        Detalle
                    </div>
                </div>
            <?php } while ($reg_lav = mysqli_fetch_assoc($cad_lav)); ?>
        </div>

        <!-- receptores/paletos/ preparadores -->
        <?php
        do {
            //equipos receptores/paletos/ preparadores
            $cad_equipos = mysqli_query($cnx, "SELECT * FROM equipos_preparacion 
            WHERE et_id = '" . $reg_tipo_eq['et_id'] . "' AND estatus = 'A'") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
            $reg_equipos = mysqli_fetch_assoc($cad_equipos);
            $total_registros = mysqli_num_rows($cad_equipos);

            if ($total_registros > 0) {
                echo "<h3>" . $reg_tipo_eq['et_descripcion'] . "</h3>";
        ?>
                <div class="row">
                    <?php do { ?>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-1 indicador">
                            <!-- imagen de equipo -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?php echo $strEstilo_img; ?>
                            </div>

                            <!-- color de estatus del equipo -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="<?php echo $strEstilo; ?>">
                                <?php echo $reg_tipo_eq['et_descripcion'] . ' ' . $reg_equipos['ep_descripcion'] ?>(etapa)
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 detalle_indicadores">
                                Detalle
                            </div>
                        </div>
                <?php } while ($reg_equipos = mysqli_fetch_assoc($cad_equipos));
                } ?>
                </div>
            <?php } while ($reg_tipo_eq = mysqli_fetch_assoc($cad_tipo_eq)); ?>

    </div>

</body>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</html>

<?php

#Función para obtener el estilo del lavador
function fnc_estilo_lavador($estatus)
{
    //5 ocupado
    //6 libre
    //7 descompuesto
    //8 reparación
    switch ($estatus) {
        case 5:
            $strCol = "background:#F9F606";
            break;
        case 6:
            $strCol = "background:#F0EDED";
            break;
        case 7:
            $strCol = "background:#FF5B54";
            break;
        case 8:
            $strCol = "background:#342E72";
            break;
    }


    return $strCol;
}

#Función para obtener el estilo del lavador
function fnc_imagen_lavador($no)
{
    switch ($no) {
        case 5:
            $strCol = "<img src='../iconos/lavador_ocupado.png')>";
            break;
        case 6:
            $strCol = "<img src='../iconos/lavador_libre.png')>";
            break;
        case 7:
            $strCol = "<img src='../iconos/lavador_descompuesto.png')>";
            break;
        case 8:
            $strCol = "<img src='../iconos/lavador_reparacion.png')>";
            break;
    }

    return $strCol;
}

#Función para obtener el estilo del lavador
function fnc_estilo_equipos($estatus)
{
    //5 ocupado
    //6 libre
    //7 descompuesto
    //8 reparación
    switch ($estatus) {
        case 5:
            $strCol = "background:#F9F606";
            break;
        case 6:
            $strCol = "background:#F0EDED";
            break;
        case 7:
            $strCol = "background-image: url('iconos/lavador_descompuesto.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
            break;
        case 8:
            $strCol = "background-image: url('iconos/lavador_reparacion.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
            break;
    }

    return $strCol;
}

#Función para obtener el estilo del lavador
function fnc_imagen_equipos($estatus)
{
    switch ($estatus) {
        case 5:
            $strCol = "<img src='../iconos/paletoocupado.png')>";
            break;
        case 6:
            $strCol = "<img src='../iconos/paletolibre.png')>";
            break;
        case 7:
            $strCol = "<img src='../iconos/paletodescompuesto.png')>";
            break;
        case 8:
            $strCol = "<img src='../iconos/paletoreparacion.png')>";
            break;
    }

    return $strCol;
}
?>