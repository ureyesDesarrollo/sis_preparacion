<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

require_once('../conexion/conexion.php');
include('../seguridad/user_seguridad.php');
include('../funciones/funciones.php');
require '../funciones/funciones_procesos.php';
$cnx =  Conectarse();



$id_e = $_GET['id_e'];

//Estatus anteriores
/*1;"Ocupado";"P";
2;"Libre";"P";
3;"Descompuesto";"P";
4;"En reparacion";"P";
5;"Ocupado";"L";
6;"Libre";"L";
7;"Descompuesto";"L";
8;"En reparacion";"L";
9;"Libre";"#E4E4E5";"E"*/

//Estatus nuevos
/*10;"Orden trabajo";"#43E011";"P"
11;"Ocupado";"#FDEB31";"P"
12;"Descompuesto";"#FB6D5C";"E"
13;"Reparacion";"#31C2FD";"E" */

/*echo "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio   
FROM procesos as p
inner join procesos_equipos as e ON(p.pro_id = e.pro_id)
LEFT JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
WHERE e.ep_id = '$id_e' AND p.pro_estatus = 1 ";*/



$cad_tit = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio   
							   FROM procesos as p
                               inner join procesos_equipos as e ON(p.pro_id = e.pro_id)
							   LEFT JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   WHERE e.ep_id = '$id_e' AND p.pro_estatus = 1 and e.pe_ban_activo = 1 ");
$reg_tit = mysqli_fetch_array($cad_tit);

if (isset($reg_tit['pro_id'])) {
    $id_oper = $reg_tit['pro_operador'];
    $id_super = $reg_tit['pro_supervisor'];
    $id_tipo = $reg_tit['pt_id'];
    $strFech = $reg_tit['pro_fe_carga'];
    $strHr = $reg_tit['pro_hr_inicio'];
    $idx_pro = $reg_tit['pro_id'];
} else {
    $id_oper = '';
    $id_super = '';
    $id_tipo = '';
    $strFech = '';
    $strHr = '';
    $idx_pro = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bitacora</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/estilos_proceso.css">
    <script src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/alerta.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <!-- Sweet alert -->
    <link href="../assets/sweetalert/sweetalert.css" rel="stylesheet" />
    <script src="../assets/sweetalert/sweetalert.js"></script>
    <script src="../assets/sweetalert/sweetalert2.js"></script>
    <!-- Toastr  -->
    <link rel="stylesheet" href="../assets/toastr/toastr.css">
    <script src="../assets/toastr/toastr.min.js"></script>

    <script type="text/javascript">
        /* function x() {
            // Código de la función aquí
            var autentificadoValor = <?php echo json_encode($_SESSION["autentificado"]); ?>;
            alert(autentificadoValor);
        }

        // Agregar un listener para el evento click en la ventana
        window.addEventListener('click', x); */

        window.addEventListener("keypress", function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        }, false);

        //Bloquear boton al agregar material
        function confirmEnviar() {

            formModalM.btn.disabled = true;
            formModalM.btn.value = "Enviando...";

            setTimeout(function() {
                formModalM.btn.disabled = true;
                formModalM.btn.value = "Guardar";
            }, 2000);

            var statSend = false;
            return false;
        }

        //Bloquear boton al dividir material
        function confirmEnviar2() {

            formModal.btn.disabled = true;
            formModal.btn.value = "Enviando...";

            setTimeout(function() {
                formModal.btn.disabled = true;
                formModal.btn.value = "Guardar";
            }, 2000);

            var statSend = false;
            return false;
        }

        //Bloquear boton al dividir material
        function confirmEnviar4() {

            formModalR.btn.disabled = true;
            formModalR.btn.value = "Enviando...";

            setTimeout(function() {
                formModalR.btn.disabled = true;
                formModalR.btn.value = "Guardar";
            }, 2000);

            var statSend = false;
            return false;
        }

        function abre_modal_equipos(proceso, equipo) {
            var datos = {
                "pro_id": proceso,
                "equipo": equipo
            }
            $.ajax({
                type: 'post',
                url: 'modal_equipo.php',
                data: datos,
                //data: {nombre:n},
                success: function(result) {
                    $("#modal_equipos").html(result);
                    $('#modal_equipos').modal('show')
                }
            });
            return false;
        }

        function abre_modal_equipo_receptor(proceso, equipo) {
            var datos = {
                "pro_id": proceso,
                "equipo": equipo
            }
            $.ajax({
                type: 'post',
                url: 'modal_equipo_receptor.php',
                data: datos,
                //data: {nombre:n},
                success: function(result) {
                    $("#modal_equipo_receptor").html(result);
                    $('#modal_equipo_receptor').modal('show')
                }
            });
            return false;
        }

        //modal materiales
        function abre_modal_material(proceso) {
            var datos = {
                "pro_id_m": proceso,
            }
            $.ajax({
                type: 'post',
                //url: 'formatos/modal_procesos_materiales.php',
                url: "formatos/modal_procesos_materiales.php",
                data: datos,
                //data: {nombre:n},
                success: function(result) {
                    $("#modal_procesos_materiales").html(result);
                    $('#modal_procesos_materiales').modal('show')
                }
            });
            return false;
        }

        //modal equipos
        function abre_modal_equipos_bit(proceso) {
            var datos = {
                "pro_id_m": proceso,
            }
            $.ajax({
                type: 'post',
                url: 'formatos/modal_procesos_equipos.php',
                data: datos,
                //data: {nombre:n},
                success: function(result) {
                    $("#modal_procesos_equipos").html(result);
                    $('#modal_procesos_equipos').modal('show')
                }
            });
            return false;
        }

        //modal quimicos 
        function abre_modal_quimicos(proceso) {
            var datos = {
                "pro_id_m": proceso,
            }
            $.ajax({
                type: 'post',
                url: 'formatos/modal_procesos_quimicos.php',
                data: datos,
                //data: {nombre:n},
                success: function(result) {
                    $("#modal_procesos_quimicos").html(result);
                    $('#modal_procesos_quimicos').modal('show')
                }
            });
            return false;
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
                    window.location.href = 'index.php';
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

    <div class="container encabezado">
        <?php
        include "header_procesos.php";
        ?>
    </div>

    <div class="container">
        <?php
        /*  $cad_tipo_equipo = mysqli_query($cnx, "SELECT t.ban_almacena FROM equipos_preparacion as e
              inner join equipos_tipos as t on(e.ep_tipo =t.et_tipo) 
              WHERE ep_id = '$id_e'");
        $reg_tipo_equipo = mysqli_fetch_array($cad_tipo_equipo); */

        if ($_SESSION['privilegio'] == 4 and $id_super == '') {
            include "encabezado_sup.php";
        } else if ($_SESSION['privilegio'] == 4) {
            include "formatos/encabezado.php";
        } //Supervisor

        if ($_SESSION['privilegio'] == 3 and ($id_oper == 0 or $id_oper == '')) {
            include "encabezado_ope.php";
        } else if ($_SESSION['privilegio'] == 3) {
            include "formatos/encabezado.php";
        } //Operador

        if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28 or $_SESSION['privilegio'] == 28 ) {
            include "formatos/encabezado.php";
        } //Laboratorio

        //SI EL EQUIPO NO ES DE TIPO ALMACEN
        /* if ($reg_tipo_equipo['ban_almacena'] != 'S') { */

        //Dibuja las preparaciones segun el tipo de proceso que seleccione.
        $cad_et = mysqli_query($cnx, "SELECT e.pe_archivo, e.pe_id  
							FROM preparacion_tipo_etapas as t
							INNER JOIN preparacion_etapas As e on (t.pe_id = e.pe_id)
							WHERE pt_id = '$id_tipo'
							ORDER BY pte_orden ASC ");
        $reg_et = mysqli_fetch_array($cad_et);
        $tot_et = mysqli_num_rows($cad_et);

        //Inicia variables vacias
        $strProp1 = $strProp2 = $strProp3 = $strProp4 = $strProp5 = $strProp6 = '';

        //Muestra las etapas si el operador ya completo el registro
        if ($idx_pro > 0) {
            if ($strFech != '') {
                if ($tot_et != 0) {
                    $str_continua = 'SI';
                    do {

                        if ($reg_et['pe_id'] != 17 and $reg_et['pe_id'] != 20 and $reg_et['pe_id'] != 21 and $reg_et['pe_id'] != 26 and $reg_et['pe_id'] != 18) {
                            $val = fnc_valida_etapa($idx_pro, $reg_et['pe_id']);
                            #echo "***AQUI".$reg_et['pe_id'];
                        } else {
                            $val = fnc_valida_etapa_b($idx_pro, $reg_et['pe_id']);
                            #echo "***Final".$reg_et['pe_id'];
                        }
                        
                        #Como estaba antes de MC
                        /*
                        if ($val == 'Si') {
                            include("fases/" . $reg_et['pe_archivo']);
                        } else {
                            include "fases/formatos/" . $reg_et['pe_archivo'];
                        }*/

                        #Despues de la validación MC
                        if($str_continua == 'SI')
                        {
                            if ($val == 'Si') {
                                include("fases/" . $reg_et['pe_archivo']);
                                $str_continua = 'NO';
                            } else {
                                include "fases/formatos/" . $reg_et['pe_archivo'];
                                $str_continua = 'SI';
                            }
                        }

                    } while ($reg_et = mysqli_fetch_array($cad_et));
                }
            } else { ?>
                <div style="height: 40px;width: 490px;text-align: left;z-index: 10;margin-top:15px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
                    Debe completar la captura el operador antes de desplegar las etapas <?php echo $strFech; ?>
                </div>
        <?php }
        }
        /* } */
        ?>

    </div>
    <div class="modal" id="modal_equipo_receptor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
    <div class="modal" id="modal_equipos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>


</body>

</html>