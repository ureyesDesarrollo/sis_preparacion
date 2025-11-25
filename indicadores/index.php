<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../funciones/funciones_procesos.php";
include "../seguridad/user_seguridad.php";
$cnx =  Conectarse();

$cad_tipo_eq = mysqli_query($cnx, "SELECT * FROM equipos_tipos WHERE et_estatus = 'A' ORDER BY et_orden ASC") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_eq = mysqli_fetch_assoc($cad_tipo_eq);

$cad_tipo_estatus = mysqli_query($cnx, "SELECT distinct(le_tipo) as le_tipo FROM listado_estatus WHERE le_tipo != '' ") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_estatus = mysqli_fetch_assoc($cad_tipo_estatus);
$directorio = "";
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
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <!-- Sweet alert -->
    <link href=<?php echo $directorio . "../assets/sweetalert/sweetalert.css" ?> rel="stylesheet" />
    <script src=<?php echo $directorio . "../assets/sweetalert/sweetalert.js" ?>></script>
    <script src=<?php echo $directorio . "../assets/sweetalert/sweetalert2.js" ?>></script>

    <script>
        function drenar(id) {
            Swal.fire({
                title: "¿Dar drenado?",
                //text: "You won't be able to revert this!",
                //icon: "warning",
                //imageUrl: "URL_DE_TU_IMAGEN",
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "No" // Agregamos el texto para el botón de cancelar
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'cambiar_estatus.php',
                        data: 'id=' + id,
                        type: 'post',
                        success: function(result) {
                            Swal.fire({
                                title: "Realizado!",
                                text: "El equipo ahora esta libre",
                                icon: "success"
                            }).then(() => {
                                // Recargar la página después de cerrar el segundo SweetAlert
                                location.reload();
                            });
                        }
                    });
                    return false;

                }
            });
        }

        /*  function miFuncion() {
            // Código de la función aquí
            var autentificadoValor = <?php echo json_encode($_SESSION["autentificado"]); ?>;
            alert(autentificadoValor);
        }

        // Agregar un listener para el evento click en la ventana
        window.addEventListener('click', miFuncion);
 */
        /*-------------------------- CIERRE SESION -----------------------------*/
        // Inicia el temporizador inicial
        reiniciarTemporizador();
        // Inicia la verificación de inactividad
        verificarInactividad();

        var tiempoInactividad = 600; // en segundos
        var tiempoInactividadMillis = tiempoInactividad * 1000; // convierte a milisegundos
        var tiempoUltimaActividad;

        // Función para reiniciar el temporizador de inactividad
        function reiniciarTemporizador() {
            tiempoUltimaActividad = new Date().getTime();
        }

        // Función para verificar inactividad y realizar acciones
        function verificarInactividad() {
            var ahora = new Date().getTime();
            var tiempoInactivo = ahora - tiempoUltimaActividad;

            if (tiempoInactivo >= tiempoInactividadMillis) {
                // Si ha pasado el tiempo de inactividad, muestra la alerta de SweetAlert
                Swal.fire({
                    title: 'Sesión cerrada',
                    text: 'Tu sesión ha sido cerrada debido a inactividad.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(function() {
                    // Realiza acciones adicionales, como cerrar sesión o redirigir
                    window.location.href = '../index.php';
                });
            } else {
                // Si no ha pasado el tiempo de inactividad, sigue verificando
                setTimeout(verificarInactividad, 1000); // verifica cada segundo
            }
        }

        // Agrega listeners para los eventos del mouse y del teclado
        document.addEventListener("mousemove", reiniciarTemporizador);
        document.addEventListener("keypress", reiniciarTemporizador);

        // Inicia el temporizador inicial
        reiniciarTemporizador();
        // Inicia la verificación de inactividad
        verificarInactividad();
    </script>
</head>

<body>
    <div class="container-fluid">
        <!-- ESTATUS -->

        <div class="row" style="box-shadow: 1px 2px 3px #e6e6e6">
            <?php do {
                if ($reg_tipo_estatus['le_tipo'] == 'E') {
                    $tipo = "Estatus de equipo";
                    $clase = 'class="col-xs-12 col-sm-12 col-md-3 ps-1 pe-1"';
                    $clase_estatus = 'class="col-4 ps-1 pe-1"';
                } else if ($reg_tipo_estatus['le_tipo'] == 'P') {
                    $tipo = "Estatus de proceso";
                    $clase = 'class="col-xs-12 col-sm-12 col-md-5 ps-1 pe-1"';
                    $clase_estatus = 'class="col-3 ps-1 pe-1"';
                } else {
                    $tipo = "Sin identificar";
                }

                $cad_estatus = mysqli_query($cnx, "SELECT le_estatus,le_color,le_id FROM listado_estatus WHERE le_color <> '' and le_tipo = '" . $reg_tipo_estatus['le_tipo'] . "'
                ORDER BY 
                CASE 
                    WHEN le_id IN (10, 11) THEN 1
                    WHEN le_id = 15 THEN 2
                    WHEN le_id = 14 THEN 3
                    ELSE 4
                END,
                le_id;") or die(mysqli_error($cnx) . "Error: en consultar estatus");
                $reg_estatus = mysqli_fetch_assoc($cad_estatus);
            ?>
                <!-- <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> -->
                <div <?php echo $clase; ?> style="margin-bottom: 0.3rem;">
                    <div class="card">
                        <div class="card-header pt-0 pb-0">
                            <h6 class="text-center"><?php echo $tipo; ?></h6>
                        </div>
                        <div class="card-body pt-1 pb-1">
                            <div class="row ">
                                <?php do {
                                    if ($reg_estatus['le_id'] == 10 || $reg_estatus['le_id'] == 12 || $reg_estatus['le_id'] == 14) {
                                        $color_text_est = '#fff';
                                    } else {
                                        $color_text_est = '#000';
                                    } ?>
                                    <div <?php echo $clase_estatus; ?>>
                                        <div class="alert p-2 m-0" style="<?php echo 'background:' . $reg_estatus['le_color'] . ';color:' . $color_text_est ?>;">
                                            <h6 class="text-center" style="font-weight:bold;font-size: .7rem;margin:0px"><?php echo $reg_estatus['le_estatus']; ?></h6>
                                        </div>
                                    </div>
                                <?php } while ($reg_estatus = mysqli_fetch_assoc($cad_estatus)) ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } while ($reg_tipo_estatus = mysqli_fetch_assoc($cad_tipo_estatus)); ?>



            <div class="col-xs-12 col-sm-12 col-md-4" style="color: #000;text-align:right;margin-top:1.5rem">
                <span style="font-weight: bold;margin-right:1rem">TABLERO DE INDICADORES</span>
                <a href="../index_inicio.php">
                    <!-- <a href="tablero_pelambre.php">
                        <i class="fa fa-dashcube" aria-hidden="true"></i>
                        Tablero pelambre
                    </a> -->
                    <?php if ($_SESSION['privilegio'] != 3) { ?>
                        <a href="../index_inicio.php">
                            <i class="fa-solid fa-circle-left"></i> Regresar
                        </a>
                    <?php } ?>

                    <a href="../seguridad/salir.php">
                        <i class="fa-solid fa-user"></i> Cerrar sesión
                    </a>
            </div>
        </div>


        <!-- LAVADORES / RECEPTORES / PALETOS / PREPARADORES -->
        <?php
        do {
            // SELECT ep.*, le.le_color 
            // FROM equipos_preparacion AS ep 
            // LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id)
            // WHERE ep.ep_tipo = 'L' AND ep.estatus = 'A';

            //equipos receptores/paletos/ preparadores
            $cad_equipos = mysqli_query($cnx, "SELECT ep.*, le.le_color, le.le_id FROM equipos_preparacion AS ep
            LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id) 
            WHERE ep_tipo = '{$reg_tipo_eq['et_tipo']}' AND ep_tipo <> 'X' AND estatus = 'A' ORDER BY ep_tipo") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
            $reg_equipos = mysqli_fetch_assoc($cad_equipos);



            $total_registros = mysqli_num_rows($cad_equipos);
            if ($total_registros > 0) {

                echo "<h3>" . $reg_tipo_eq['et_descripcion'] . "</h3>";
        ?>
                <div class="row">
                    <?php do {

                        if ($reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                            $cad_proceso = mysqli_query($cnx, "SELECT MAX(p.pro_id) as pro_id FROM procesos as p 
                                                    inner join procesos_equipos as pe on (p.pro_id = pe.pro_id)
                                                    WHERE pe.ep_id = '" . $reg_equipos['ep_id'] . "' and p.pro_estatus = 1") or die(mysqli_error($cnx) . "Error: en consultar los procesos");
                            $reg_procesos = mysqli_fetch_assoc($cad_proceso);
                            $tot_procesos = mysqli_num_rows($cad_proceso);

                            $consulta_kg = mysqli_query($cnx, "SELECT pro_total_kg,pro_id FROM procesos WHERE  pro_id = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
                            $reg_kg = mysqli_fetch_assoc($consulta_kg);

                            $cad_ult_et_caputurada = mysqli_query($cnx, "SELECT MAX(proa_id) as proa_id
                                                            FROM procesos_auxiliar as a
                                        where pro_id = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos_auxiliar");
                            $reg_ult_et_caputurada = mysqli_fetch_assoc($cad_ult_et_caputurada);
                            $tot_ult_et_caputurada = mysqli_num_rows($cad_ult_et_caputurada);

                            $cad_auxiliar = mysqli_query($cnx, "SELECT MAX(proa_id) as proa_id,a.pro_id,a.pe_id,e.pe_descripcion, a.proa_fe_ini, a.proa_hr_ini, e.pe_hr_maxima
                                                            FROM procesos_auxiliar as a
                                                            INNER JOIN preparacion_etapas as e on (a.pe_id = e.pe_id)                   
                                        where proa_id = '" . $reg_ult_et_caputurada['proa_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos_auxiliar");
                            $reg_auxiliar = mysqli_fetch_assoc($cad_auxiliar);
                            $tot_auxiliar = mysqli_num_rows($cad_auxiliar);


                            $cad_material = mysqli_query($cnx, "SELECT mat_nombre 
                            FROM procesos_materiales as m
                            inner join materiales as x on (m.mat_id = x.mat_id)
                            where m.pro_id = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos materiales");
                            $reg_material = mysqli_fetch_assoc($cad_material);
                            $tot_mat = mysqli_num_rows($cad_material);
                        }
                        if ($reg_tipo_eq['ban_almacena'] == 'N') {
                            $clase = 'class="col-xs-2 col-sm-3 col-md-2 col-lg-2 mb-3 ps-1 pe-1 indicador"';
                        } else {
                            $clase = 'class="col-xs-2 col-sm-3 col-md-2 col-lg-2 mb-3 ps-1 pe-1 indicador"';
                        }
                    ?>

                        <div <?php echo $clase; ?>>
                            <!-- imagen de equipo -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="text-center">
                                    <img class="img-fluid" src="<?php echo $reg_tipo_eq['et_imagen'] ?>" alt="<?= $reg_tipo_eq['et_descripcion'] ?>">
                                </div>
                            </div>
                            <!-- color de estatus del equipo -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body p-2 border fw-bold" style="background: <?php echo $reg_equipos['le_color'] ?>">
                                        <!-- Si esta en estatus en cualquier excepto descompuesto y reparación, abre para captura -->
                                        <?php if ($reg_equipos['le_id'] == 9 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                            //No se que que hace esta validación aqui 
                                        ?>
                                            <?php
                                            if (($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6  or $_SESSION['privilegio'] == 28) and $reg_tipo_eq['ban_almacena'] == 'N') {
                                            ?>

                                                <div class="text-center">
                                                    <a href="../bitacoras/bitacora.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion'] ?></a>
                                                </div>
                                            <?php } else {

                                                if ($reg_equipos['le_id'] == 11) {
                                                    //DATO AGRUPADOR
                                                    $cad_agrupador = mysqli_query($cnx, "SELECT * FROM procesos_agrupados  
                                                    WHERE pro_id = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar agrupado");
                                                    $reg_agrupador = mysqli_fetch_assoc($cad_agrupador);

                                                    $dato_agrupador = " (" . $reg_agrupador['pa_id'] . ")";
                                                } else {
                                                    $dato_agrupador = " ";
                                                }

                                            ?>
                                                <a href="../bitacoras/formatos/bitacora_consulta.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion'] . $dato_agrupador  ?></a>
                                            <?php } ?>
                                        <?php
                                        }
                                        /* Si esta descompuesto o en reparación */ else {  ?>
                                            <a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo $reg_equipos['ep_descripcion'] ?></a>
                                        <?php } ?>
                                    </div>
                                    <?php if ($reg_tipo_eq['ban_almacena'] != 'S') {
                                        if ($_SESSION['privilegio'] == 3 &&  $reg_equipos['le_id'] == 14) { ?>
                                            <div class="card-body p-1 border" style="font-size: .7rem;height:160px">
                                                <a href="#" onclick="drenar(<?php echo $reg_equipos['ep_id'] ?>) ">Drenar equipo <i class="fa-brands fa-digital-ocean"></i></a>
                                            </div>

                                        <?php } else { ?>
                                            <div class="card-body p-1 border" style="font-size: .7rem;height:160px">

                                                <span style="font-weight: bold;"> Proceso:</span> <?php
                                                                                                    if ($reg_equipos['le_id'] == '11' or  $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                                                                                        echo $reg_kg['pro_id'];
                                                                                                    } ?><br>

                                                <span style="font-weight: bold;">Material: </span>
                                                <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                                    if ($tot_mat > 1) {
                                                        echo "Mezcla";
                                                    } else {
                                                        echo $reg_material['mat_nombre'];
                                                    }
                                                } ?><br>

                                                <span style="font-weight: bold;"> Kg:</span> <?php
                                                                                                if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                                                                                    echo number_format($reg_kg['pro_total_kg'], 2) . " Kgs";
                                                                                                } ?><br>

                                                <span style="font-weight: bold;"> Etapa:</span> <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {

                                                                                                    echo $reg_auxiliar['pe_descripcion'];
                                                                                                } ?><br>
                                                <span style="font-weight: bold;">Fe Ini:</span> <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {

                                                                                                    echo $reg_auxiliar['proa_fe_ini'];
                                                                                                } ?><br>
                                                <span style="font-weight: bold;">Hr Ini:</span> <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {

                                                                                                    echo $reg_auxiliar['proa_hr_ini'];
                                                                                                } ?><br>

                                                <!-- Tiempo transcurrido -->
                                                <span style="font-weight: bold;">Hrs etapa:</span>
                                                <?php if ($reg_equipos['le_id'] == 11) {
                                                    echo $reg_auxiliar['pe_hr_maxima'];
                                                } ?><br>
                                                <span style="font-weight: bold;">Hrs trans:</span>
                                                <?php if ($reg_equipos['le_id'] == 11) {

                                                    echo fnc_horas($reg_auxiliar['proa_fe_ini'], date("Y-m-d"), $reg_auxiliar['proa_hr_ini'], date("H:i:s"));
                                                } ?><br>
                                            </div>
                                        <?php   }  ?>

                                    <?php } ?>
                                </div>
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
/* function fnc_estilo_lavador($estatus)
{
    //5 ocupado
    //6 libre
    //7 descompuesto
    //8 reparación

    //nuevos estatus
    //9 libre
    //10 orden de trabajo
    //11 ocupado
    //12 descompuesto
    //13 reparación
    switch ($estatus) {
        case 11:
            $strCol = "background: rgb(238,202,6);
                       background: linear-gradient(0deg, rgba(238,202,6,1) 0%, rgba(245,255,0,1) 50%, rgba(255,255,119,1) 100%);";
            break;
        case 9:
            $strCol = "background: rgb(100,100,100);
                       background: linear-gradient(0deg, rgba(100,100,100,1) 0%, rgba(183,183,183,1) 50%, rgba(255,255,255,1) 100%);";
            break;
        case 12:
            $strCol = "background: rgb(190,23,4);
                       background: linear-gradient(0deg, rgba(190,23,4,1) 0%, rgba(239,54,32,1) 50%, rgba(250,99,81,1) 100%);";
            break;
        case 13:
            $strCol = "background: rgb(0,55,133);
                       background: linear-gradient(0deg, rgba(0,55,133,1) 0%, rgba(20,101,187,1) 50%, rgba(33,150,243,1) 100%);";
            break;
    }
    return $strCol;
} */

#Función para obtener el estilo del lavador
/* function fnc_imagen_lavador($no)
{
    switch ($no) {
        case 11:
            $strCol = "<img src='../iconos/lavador_ocupado.png')>";
            break;
        case 9:
            $strCol = "<img src='../iconos/lavador_libre.png')>";
            break;
        case 12:
            $strCol = "<img src='../iconos/lavador_descompuesto.png')>";
            break;
        case 13:
            $strCol = "<img src='../iconos/lavador_reparacion.png')>";
            break;
    }

    return $strCol;
} */

#Función para obtener el estilo del lavador
/* function fnc_estilo_equipos($estatus)
{
    //5 ocupado
    //6 libre
    //7 descompuesto
    //8 reparación
    switch ($estatus) {
        case 11:
            $strCol = "background:#F9F606";
            break;
        case 9:
            $strCol = "background:#F0EDED";
            break;
        case 12:
            $strCol = "background-image: url('iconos/lavador_descompuesto.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
            break;
        case 13:
            $strCol = "background-image: url('iconos/lavador_reparacion.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
            break;
    }

    return $strCol;
} */

#Función para obtener el estilo del lavador
/* function fnc_imagen_equipos($estatus)
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
} */
?>