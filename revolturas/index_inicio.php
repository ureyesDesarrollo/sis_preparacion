<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../funciones/funciones_procesos.php";
include "../seguridad/user_seguridad.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de revolturas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/jquery.min.js"></script>
    <!--DATATABLES-->
    <!-- <script src=../assets/datatable/jquery-3.5.1.js></script> -->
    <script src=../assets/datatable/jquery.dataTables.min.js></script>
    <script src=../assets/datatable/dataTables.bootstrap5.min.js></script>

    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Buttons -->
    <link rel="stylesheet" href="../assets/datatable/buttons.dataTables.min.css">
    <script src="../assets/datatable/dataTables.buttons.min.js"></script>
    <script src="../assets/datatable/jszip.min.js"></script>
    <script src="../assets/datatable/pdfmake.min.js"></script>
    <script src="../assets/datatable/vfs_fonts.js"></script>
    <script src="../assets/datatable/buttons.html5.min.js"></script>
    <script src="../assets/datatable/buttons.print.min.js"></script>
    <script src="../assets/datatable/buttons.colVis.min.js"></script>
    <script src="../assets/datatable/ellipsis.js"></script>

    <!-- <link href="../assets/sweetalert/sweetalert.css" rel="stylesheet" />
    <script src="../assets/sweetalert/sweetalert.js"></script>
    <script src="../assets/sweetalert/sweetalert2.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php require_once 'menu.php'; ?>
    <div id="contenido" class="container-fluid">
        <!-- Aquí se cargará el contenido -->
    </div>
    <script>
        $(document).ready(function() {
            function cargarContenido(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#contenido').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(`Error al cargar la página: ${textStatus}, ${errorThrown}`);
                    }
                });
            }

            function inicializarMenu() {
                $('.menu-item').click(function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href');
                    cargarContenido(url);
                });
            }

            inicializarMenu();
            cargarContenido('inicio.php');
        });
    </script>
    <script src="../assets/fontawesome/fontawesome.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        const TIEMPO_INACTIVIDAD_SEGUNDOS = 600;
        const TIEMPO_INACTIVIDAD_MILLIS = TIEMPO_INACTIVIDAD_SEGUNDOS * 1000;

        const configuracionTemporizador = {
            tiempoUltimaActividad: null,
            tiempoInactividadMillis: TIEMPO_INACTIVIDAD_MILLIS
        };

        function reiniciarTemporizadorDeInactividad() {
            configuracionTemporizador.tiempoUltimaActividad = new Date().getTime();
        }

        let alertaMostrada = false;

        function verificarEstadoDeInactividad() {
            const ahora = new Date().getTime();
            const tiempoInactivo = ahora - configuracionTemporizador.tiempoUltimaActividad;

            if (tiempoInactivo >= configuracionTemporizador.tiempoInactividadMillis && !alertaMostrada) {
                alertaMostrada = true;
                Swal.fire({
                    title: 'Sesión cerrada',
                    text: 'Tu sesión ha sido cerrada debido a inactividad.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    callbackCerrarSesion();
                });
            }
        }

        function callbackCerrarSesion() {
    const urlActual = window.location.href;

    if (urlActual.includes('pelambre')) {
        window.location.href = '../pelambre/index.php';
    } else if (urlActual.includes('revolturas')) {
        window.location.href = '../revolturas/index.php';
    } else {
        window.location.href = '../index.php'; // Login general o default
    }
}

        document.addEventListener("mousemove", reiniciarTemporizadorDeInactividad);
        document.addEventListener("keypress", reiniciarTemporizadorDeInactividad);

        reiniciarTemporizadorDeInactividad();
        setInterval(verificarEstadoDeInactividad, 1000);
    </script>
</body>

</html>