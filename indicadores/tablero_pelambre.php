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
    <script src="../js/alerta.js"></script>

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
                    $clase = 'class="col-xs-12 col-sm-12 col-md-3 ps-1 pe-1"';
                    $clase_estatus = 'class="col-4 ps-1 pe-1"';
                } else {
                    $tipo = "Sin identificar";
                }

                $cad_estatus = mysqli_query($cnx, "SELECT le_estatus,le_color,le_id FROM listado_estatus WHERE le_color <> '' and le_id <> 15 and le_tipo = '" . $reg_tipo_estatus['le_tipo'] . "'
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



            <div class="col-xs-12 col-sm-12 col-md-6" style="color: #000;text-align:right;margin-top:1.5rem">
                <span style="font-weight: bold;margin-right:1rem">TABLERO DE PELAMBRE</span>
                <?php if (
                    $_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 3 || $_SESSION['privilegio'] == 4
                    || $_SESSION['privilegio'] == 4 || $_SESSION['privilegio'] == 15 || $_SESSION['privilegio'] == 5
                ) { ?>
                    <a href="index.php">
                        <i class="fa fa-dashcube" aria-hidden="true"></i>
                        Tablero indicadores
                    </a>
                <?php } ?>
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
            //equipos receptores/paletos/ preparadores
            $cad_equipos = mysqli_query($cnx, "SELECT ep.*, le.le_color, le.le_id FROM equipos_preparacion AS ep
            LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id) 
            WHERE ep_tipo = '{$reg_tipo_eq['et_tipo']}' AND ep_tipo = 'X' AND estatus = 'A' ORDER BY ep_tipo") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
            $reg_equipos = mysqli_fetch_assoc($cad_equipos);

            $total_registros = mysqli_num_rows($cad_equipos);
            if ($total_registros > 0) {

                echo "<h3>" . $reg_tipo_eq['et_descripcion'] . "</h3>";
        ?>
                <div class="row">
                    <?php do {
                        $inv_pel = mysqli_query($cnx, "SELECT * FROM inventario_pelambre as ip
                        inner join inventario as i on(ip.inv_id = i.inv_id) 
                        WHERE ip.ep_id = '" . $reg_equipos['ep_id'] . "' and ip_ban = 1") or die(mysqli_error($cnx) . "Error: en consultar inventario pelambre");
                        $reg_pel = mysqli_fetch_assoc($inv_pel);
                        $tot_pel = mysqli_num_rows($inv_pel);
                        $fecha_param = "'" . $reg_pel['inv_fe_enviado'] . "'";

                        $cad_material = mysqli_query($cnx, "SELECT * FROM  materiales 
                            where mat_id = '" . $reg_pel['mat_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos materiales");
                        $reg_material = mysqli_fetch_assoc($cad_material);
                        $tot_mat = mysqli_num_rows($cad_material);

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
                                    <!-- <img class="img-fluid" src="<?php echo $reg_tipo_eq['et_imagen'] ?>" alt="<?= $reg_tipo_eq['et_descripcion'] ?>"> -->
                                    <img style="width: 70%;" class="img-fluid" src="../iconos/lavador_3d.png" alt="<?= $reg_tipo_eq['et_descripcion'] ?>">
                                </div>
                            </div>
                            <!-- color de estatus del equipo -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body p-2 border fw-bold" style="background: <?php echo $reg_equipos['le_color'] ?>">
                                        <?php if ($reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11) { ?>
                                            <a href="pelambre/pelambrado_bitacoras.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion']  ?></a>
                                        <?php } else {
                                            echo $reg_equipos['ep_descripcion'];
                                        } ?>
                                    </div>

                                    <div class="card-body p-1 border" style="font-size: .7rem; text-align: center;height:auto; height: 130px ">
                                        <?php do { ?>
                                            <!-- <span style="font-weight: bold;">Material: </span> -->
                                            <!--  <?php echo "<span style='font-weight: bold;text-align:center'>Folio: </span>" .  $reg_pel['inv_folio_interno']; ?><br> -->
                                            <?php echo "<span style='font-weight: bold;text-align:center'>Ticket: </span>" . $reg_pel['inv_no_ticket']; ?><br>
                                            <?php echo "<span style='font-weight: bold;text-align:center'>Fecha envío: </span>" . $reg_pel['ip_fecha_envio']; ?><br>
                                            <?php echo "<span style='font-weight: bold;text-align:center'>Material: </span>" . $reg_material['mat_nombre']; ?><br>
                                            <?php echo "<span style='font-weight: bold;text-align:center'>Kg a pelambre: </span>" . $reg_pel['inv_kilos']; ?><br>
                                            <?php echo "<span style='font-weight: bold;text-align:center'>Proveedor: </span>" . $reg_pel['prv_id']; ?><br>
                                            <div class="p-3">

                                                <?php
                                                if ($_SESSION['privilegio'] == 3 &&  $reg_equipos['le_id'] == 14) { ?>
                                                    <a href="#" onclick="drenar(<?php echo $reg_equipos['ep_id'] ?>) ">Drenar equipo <i class="fa-brands fa-digital-ocean"></i></a>
                                                <?php }
                                                if ($reg_pel['ip_fe_descarga'] != '') { ?>
                                                    <!-- <a href="#" class="d-inline me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Enviar a patio" onclick="manda_patio(<?php echo $reg_pel['inv_id'] ?>,<?php echo $reg_pel['inv_folio_interno'] ?>,<?php echo $fecha_param ?>,<?php echo $reg_material['mat_id'] ?>,<?php echo $reg_pel['inv_kilos'] ?>,<?php echo $reg_pel['prv_id'] ?>) "> <i class="fa-solid fa-arrows-rotate fa-2xl" style="color: #bfc1c3;"></i></a> -->
                                                <?php } ?>
                                                <?php if ($reg_equipos['le_id'] == 9) {
                                                    #supervisor /operador
                                                    if ($_SESSION['privilegio'] == 4 || $_SESSION['privilegio'] == 3) { ?>
                                                        <a href="#" class="d-inline" data-bs-toggle="tooltip" data-bs-placement="top" title="Carga de Equipo" onclick="formulario_etapa1('<?php echo $reg_equipos['ep_id']; ?>','<?php echo $reg_equipos['ep_descripcion']; ?>')">
                                                            <i class="fa-solid fa-receipt fa-2xl" style="color: #bfc1c3;"></i>
                                                        </a>
                                                <?php  }
                                                } else {
                                                    echo "<br>";
                                                } ?>
                                            </div>
                                        <?php } while ($reg_pel = mysqli_fetch_assoc($inv_pel)); ?>
                                    </div>


                                </div>
                            </div>
                        </div>
                <?php } while ($reg_equipos = mysqli_fetch_assoc($cad_equipos));
                } ?>
                </div>
            <?php } while ($reg_tipo_eq = mysqli_fetch_assoc($cad_tipo_eq)); ?>
    </div>
    <!-- Modal movimiento inventario -->
    <div class="modal fade" id="modal_patio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    </div>

    <!-- Modal etapa1 -->
    <div class="modal fade" id="modal_etapa1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    </div>
</body>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    function manda_patio(inv, folio, fecha, mat, kg, prov) {
        $.ajax({
            type: 'post',
            url: 'modal_patio.php',
            data: {
                "hdd_inv": inv,
                "hdd_prov": prov,
                "hdd_mat": mat,
                "hdd_folio": folio,
                "hdd_fecha": fecha,
                "hdd_kg": kg,
            }, //Pass $id
            success: function(result) {
                $("#modal_patio").html(result);
                $('#modal_patio').modal('show')
            }
        });
        return false;
    };

    function formulario_etapa1(id_lavador, nombre_lavador) {
        const dataForm = {
            id_lavador,
            nombre_lavador
        }

        console.log(dataForm);
        $.ajax({
            type: 'POST',
            url: 'pelambre/pelambre_etapa1.php',
            data: dataForm,
            success: function(result) {
                $('#modal_etapa1').html(result);
                $('#modal_etapa1').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

</html>