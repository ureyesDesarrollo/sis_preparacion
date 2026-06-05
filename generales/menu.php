<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/




require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../conexion/conexion.php';
require_once __DIR__ . '/../seguridad/user_seguridad.php';
require_once __DIR__ . '/../funciones/funciones.php';

$directorio = base_url('');
$cnx = Conectarse();


$cadena = mysqli_query($cnx, "SELECT usu_usuario 
                FROM usuarios WHERE usu_id =" . $_SESSION['idUsu']) or die(mysqli_error($cnx) . "Error: en consultar el usuario");
$registros = mysqli_fetch_assoc($cadena);

$tot_alerta = 0;
?>

<link rel="stylesheet" href="<?php echo asset_url('bootstrap/css/bootstrap.min.css'); ?>">
<script src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/bootstrap.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo asset_url('css/estilos_menu_general.css'); ?>">
<link rel="stylesheet" href="<?php echo asset_url('assets/css/estilos_generales.css'); ?>">

<link rel="icon" type="image/png" sizes="32x32" href="<?php echo asset_url('imagenes/favicon-32x32.png'); ?>">
<script src="<?php echo asset_url('assets/fontawesome/fontawesome.js'); ?>"></script>


<!-- Toastr  -->
<link rel="stylesheet" href="<?php echo asset_url('assets/toastr/toastr.css'); ?>">
<script src="<?php echo asset_url('assets/toastr/toastr.min.js'); ?>"></script>

<!-- Sweet alert -->
<link href="<?php echo asset_url('assets/sweetalert/sweetalert.css'); ?>" rel="stylesheet" />
<script src="<?php echo asset_url('assets/sweetalert/sweetalert.js'); ?>"></script>
<script src="<?php echo asset_url('assets/sweetalert/sweetalert2.js'); ?>"></script>

<nav class="navbar navbar" style="background: #333333">
    <div class="container-fluid">
        <div class="col-sm-12 col-md-3">
            <img src="<?php echo asset_url('imagenes/logo_progel_v5.png'); ?>" alt=" Progel Mexicana">
        </div>
        <div class="col-sm-12 col-md-9">


            <ul class="navbar-nav navbar-right">
                <li class="active"><a href="<?php echo base_url('index_inicio.php'); ?>"><i class=" fa-solid fa-house"></i>Inicio</a></li>
                <?php if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 15 or $_SESSION['privilegio'] == 28) {
                ?>
                    <li class="">
                        <a href="<?php echo base_url('revolturas/index_inicio.php'); ?>" style="color: #F1F0EF"><i class="fa-solid fa-fan"></i> Revolturas</a>
                    </li><?php } ?>
                <li class=""><a href="<?php echo base_url('catalogos/submenu_catalogos.php'); ?>"><i class=" fa-solid fa-folder-tree"></i> Catálogos</a></li>
                <li class=""><a href="<?php echo base_url('modulos/submenu_funciones.php'); ?>"><i class=" fa-solid fa-gears"></i> Funciones</a></li>
                <li class=""><a href="<?php echo base_url('reportes/submenu_reportes.php'); ?>"><i class=" fa-solid fa-file-lines"></i> Reportes</a></li>
                <?php if (fnc_permiso($_SESSION['privilegio'], 2, 'upe_listar') == 1) { ?>
                    <li class=""><a href="<?php echo base_url('indicadores/submenu_indicadores.php'); ?>"><i class=" fa-solid fa-gears"></i> Indicadores</a></li>
                <?php } ?>
                <li class="">
                    <?php if ($tot_alerta > 0) { ?>
                        <a href="#" style="color: #F1F0EF">
                            <i class="fa-regular fa-bell"></i>
                            ¡ Alerta !
                        </a>
                    <?php } ?>
                </li>
                <?php
                if ($_SESSION['privilegio'] == 7) {
                    $str_manual = "manual_almacen.pdf";
                }
                if ($_SESSION['privilegio'] == 10) {
                    $str_manual = "manual_aseguramiento.pdf";
                }
                if ($_SESSION['privilegio'] == 9) {
                    $str_manual = "manual_estadistica.pdf";
                }
                if ($_SESSION['privilegio'] == 6) {
                    $str_manual = "manual_laboratorio.pdf";
                }
                if ($_SESSION['privilegio'] == 3) {
                    $str_manual = "manual_operador.pdf";
                }
                if ($_SESSION['privilegio'] == 4) {
                    $str_manual = "manual_supervisor.pdf";
                }

                if ($_SESSION['privilegio'] == 7 or $_SESSION['privilegio'] == 10 or $_SESSION['privilegio'] == 9 or $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4) {
                ?>
                    <li class="">
                        <a href="<?php echo base_url('manuales_pdf/' . $str_manual); ?>" style="color: #F1F0EF"><i class="fa-regular fa-circle-question"></i> Manual</a>
                    </li><?php } ?>
                <li class="">
                    <a href="<?php echo base_url('ayuda/index.php'); ?>" style="color: #F1F0EF"><i class="fa-regular fa-circle-question"></i> Ayuda</a>
                </li>
                <li class="">
                    <a href="<?php echo base_url('seguridad/salir.php'); ?>" style="color: #F1F0EF">
                        <i class="fa-solid fa-user"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    var logoutUrl = <?php echo json_encode(base_url('seguridad/salir.php?session_closed=true'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
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
                window.location.href = logoutUrl;
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
