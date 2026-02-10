<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/




// Ruta completa de la página actual
$url_actual = $_SERVER['REQUEST_URI'];

// Ruta desde /sis_preparacion/ hasta la página actual
$ruta_base = str_replace('/sis_preparacion', '', dirname($url_actual));

// Contar la cantidad de directorios en la ruta relativa
$levelCount = substr_count($ruta_base, '/');

if ($levelCount > 0) {
    //$directorio = substr_count($ruta_base, '/');
    $directorio = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
    $ruta_base_conexion = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
    $ruta_base_seguridad = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
    $ruta_base_funciones = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
} else {
    $directorio = '';
    $ruta_base_conexion = '';
    $ruta_base_seguridad = '';
    $ruta_base_funciones = '';
}


/* require_once('../conexion/conexion.php');
include "../seguridad/user_seguridad.php";
include "../funciones/funciones.php"; */

// Incluir el archivo de conexión usando la ruta relativa
require_once $ruta_base_conexion . 'conexion/conexion.php';
require_once $ruta_base_seguridad . 'seguridad/user_seguridad.php';
require_once $ruta_base_funciones . 'funciones/funciones.php';
$cnx = Conectarse();


$cadena = mysqli_query($cnx, "SELECT usu_usuario 
                FROM usuarios WHERE usu_id =" . $_SESSION['idUsu']) or die(mysqli_error($cnx) . "Error: en consultar el usuario");
$registros = mysqli_fetch_assoc($cadena);

$tot_alerta = 0;
?>

<link rel="stylesheet" href=<?php echo $directorio . "bootstrap/css/bootstrap.min.css" ?>>
<script src=<?php echo $directorio . "js/jquery.min.js" ?>></script>
<script src=<?php echo $directorio . "js/bootstrap.min.js" ?>></script>
<link rel="stylesheet" href=<?php echo $directorio . "css/estilos_menu_general.css" ?>>
<link rel="stylesheet" href=<?php echo $directorio . "assets/css/estilos_generales.css" ?>>

<link rel="icon" type="image/png" sizes="32x32" href=<?php echo $directorio . "imagenes/favicon-32x32.png" ?>>
<script src=<?php echo $directorio . "assets/fontawesome/fontawesome.js" ?>></script>


<!-- Toastr  -->
<link rel="stylesheet" href=<?php echo $directorio . "assets/toastr/toastr.css" ?>>
<script src=<?php echo $directorio . "assets/toastr/toastr.min.js" ?>></script>

<!-- Sweet alert -->
<link href=<?php echo $directorio . "assets/sweetalert/sweetalert.css" ?> rel="stylesheet" />
<script src=<?php echo $directorio . "assets/sweetalert/sweetalert.js" ?>></script>
<script src=<?php echo $directorio . "assets/sweetalert/sweetalert2.js" ?>></script>

<nav class="navbar navbar" style="background: #333333">
    <div class="container-fluid">
        <div class="col-sm-12 col-md-3">
            <img src="<?php echo $directorio . "imagenes/logo_progel_v5.png" ?>" alt=" Progel Mexicana">
        </div>
        <div class="col-sm-12 col-md-9">


            <ul class="navbar-nav navbar-right">
                <li class="active"><a href="<?php echo $directorio . 'index_inicio.php' ?>" onclick="obtenerURL(<?php echo $directorio . 'index_inicio.php' ?>)"><i class=" fa-solid fa-house"></i>Inicio</a></li>
                <?php if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 15 or $_SESSION['privilegio'] == 28) {
                ?>
                    <li class="">
                        <a href="<?php echo $directorio . 'revolturas/index_inicio.php' ?>" style="color: #F1F0EF"><i class="fa-solid fa-fan"></i> Revolturas</a>
                    </li><?php } ?>
                <li class=""><a href="<?php echo $directorio . 'catalogos\submenu_catalogos.php' ?>" onclick="obtenerURL(<?php echo $directorio . 'catalogos\submenu_catalogos.php.php' ?>)"><i class=" fa-solid fa-folder-tree"></i> Catálogos</a></li>
                <li class=""><a href="<?php echo $directorio . 'modulos\submenu_funciones.php' ?>" onclick="obtenerURL(<?php echo $directorio . 'modulos\submenu_funciones.php' ?>)"><i class=" fa-solid fa-gears"></i> Funciones</a></li>
                <li class=""><a href="<?php echo $directorio . 'reportes\submenu_reportes.php' ?>" onclick="obtenerURL(<?php echo $directorio . 'reportes\submenu_reportes.php' ?>)"><i class=" fa-solid fa-file-lines"></i> Reportes</a></li>
                <?php if (fnc_permiso($_SESSION['privilegio'], 2, 'upe_listar') == 1) { ?>
                    <li class=""><a href="<?php echo $directorio . 'indicadores\submenu_indicadores.php' ?>" onclick="obtenerURL(<?php echo $directorio . 'indicadores\submenu_indicadores.php' ?>)"><i class=" fa-solid fa-gears"></i> Indicadores</a></li>
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
                        <a href="../manuales_pdf/<?php echo $str_manual; ?>" style="color: #F1F0EF"><i class="fa-regular fa-circle-question"></i> Manual</a>
                    </li><?php } ?>
                <li class="">
                    <a href="ayuda/index.php" style="color: #F1F0EF"><i class="fa-regular fa-circle-question"></i> Ayuda</a>
                </li>
                <li class="">
                    <a href="<?php echo $directorio . 'seguridad\salir.php' ?>" style="color: #F1F0EF">
                        <i class="fa-solid fa-user"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function obtenerURL(event) {
        // Evitar la navegación predeterminada
        event.preventDefault();

        // Obtener la URL del enlace
        var urlDelEnlace = event.target.href;

        // Obtener la parte de la URL después de "http://localhost/sis_preparacion/"
        var parteDeseada = urlDelEnlace.split("http://localhost/sis_preparacion/")[1];
        window.location.href = parteDeseada;
    }

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