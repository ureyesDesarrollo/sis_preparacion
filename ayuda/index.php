<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../seguridad/user_seguridad.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Ayuda</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <script src="../assets/fontawesome/fontawesome.js"></script>

    <style>
        .video-container {
            display: none;
        }

        .bg-secondary {
            background: #333333;
            background-color: #333333;
        }

        .bg-secondary {
            --bs-bg-opacity: 1;
            background-color: rgba(51, 51, 51) !important;
        }
    </style>
</head>

<body>
    <header class="bg-secondary text-light text-center p-3">
        <!-- <img src="../imagenes/logo_progel_v5.png"" alt=" Progel Mexicana"> -->
        <div class="row">
            <div class="col-md-10">
                <h5 style="margin-left: 11rem;">Panel de Ayuda</h5>
            </div>
            <div class="col-md-2">

                <h6> <?php if ($_SESSION['privilegio'] != 3) { ?>
                        <a href="../index_inicio.php" style="color:#fff;">
                            <i class="fa-solid fa-circle-left"></i> Regresar
                        </a>
                    <?php } ?>
                </h6>
            </div>
        </div>
    </header>

    <div class="container my-5">

        <div class="row">
            <!-- Opciones Almacen -->
            <?php if ($_SESSION['privilegio'] == 7 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 1) { ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-light">
                            Módulo de almacen
                        </div>

                        <div class="card-body">
                            <a href="#" class="toggle-video">Proveedores</a>
                            <div class="video-container">

                                <video width="640" height="360" controls>
                                    <source src="proveedores.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>

                        <div class="card-body">
                            <a href="#" class="toggle-video">Origen y Materiales</a>
                            <div class="video-container">

                                <video width="640" height="360" controls>
                                    <source src="materialyorigen.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>

                        <div class="card-body">
                            <a href="#" class="toggle-video">Ingreso materia prima</a>
                            <div class="video-container">

                                <video width="640" height="360" controls>
                                    <source src="video_almacen.webm" type="video/webm">
                                </video>
                            </div>
                        </div>

                        <div class="card-body">
                            <a href="#" class="toggle-video">Materaial, baja, devolución, envio a proceso</a>
                            <div class="video-container">

                                <video width="640" height="360" controls>
                                    <source src="material_baja_dev_envio.mp4" type="video/webm">
                                </video>
                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>
            <p></p>

            <!-- Opciones Admin -->
            <?php if ($_SESSION['privilegio'] == 13 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 1) { ?>
                <div class="col-md-12">
                    <div a class="card">
                        <div class="card-header bg-secondary text-light">
                            Módulo de jefe Area
                        </div>
                        <div class="card-body">
                            <a href="#" class="toggle-video">tipos y Equipos</a>
                            <div class="video-container">

                                <video width="640" height="360" controls>
                                    <source src="equiposytipos.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="#" class="toggle-video">Envio y recepción de maquila</a>
                            <div class="video-container">
                                <!-- Aquí puedes insertar el código para tu video -->
                                <video width="640" height="360" controls>
                                    <source src="maquila2.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <p></p>

            <!-- Opciones Admin -->
            <?php if ($_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 1) { ?>
                <div class="col-md-12">
                    <div a class="card">
                        <div class="card-header bg-secondary text-light">
                            Módulo de Administrador
                        </div>
                        <div class="card-body">
                            <a href="#" class="toggle-video">Proveedores</a>
                            <div class="video-container">

                                <video width="640" height="360" controls>
                                    <source src="usuarios.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>

        <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script>
            // Script para mostrar/ocultar videos al hacer clic en "Ver Video"
            document.querySelectorAll('.toggle-video').forEach(button => {
                button.addEventListener('click', () => {
                    const videoContainer = button.parentElement.querySelector('.video-container');
                    if (videoContainer) {
                        if (videoContainer.style.display === 'none' || videoContainer.style.display === '') {
                            videoContainer.style.display = 'block';
                        } else {
                            videoContainer.style.display = 'none';
                        }
                    }
                });
            });
        </script>



</body>

</html>