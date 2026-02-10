<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: info@ccaconsultoresti.com */
/* Actualizado: Junio-2024 */
/* Realizado: 21 - Agosto - 2018 */
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login Sistema Revolturas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <link rel="stylesheet" href="../../css/estilos_login.css">
    <script src='../../js/jquery.min.js'></script>
    <!--<script src="js/jquerylogin.js"></script>-->
    <?php
    // Verifica si se ha pasado el parámetro "session_closed" en la URL
    if (isset($_GET['session_closed']) && $_GET['session_closed'] == 'true') {
        echo '<script>alert("Tu sesión ha sido cerrada debido a inactividad.");</script>';
    }
    ?>

</head>

<body>

    <div class="cont">
        <div class="demo">
            <div class="login">
                <center><img src="../../imagenes/logo_progel_v3.png" class="logo"></center>

                <div class="login__form">
                    <?php
                    // Obtener el tar_id de la URL si existe
                    $tar_id = isset($_GET['tar_id']) ? $_GET['tar_id'] : null;
                    ?>

                    <form action="../../seguridad/user_valida.php" method="post">
                        <!-- Si existe un tar_id, lo pasamos como valor oculto en el formulario -->
                        <?php if ($tar_id): ?>
                            <input type="hidden" name="tar_id" value="<?php echo $tar_id; ?>">
                        <?php endif; ?>
                        <div class="login__row">
                            <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
                                <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
                            </svg>
                            <input type="text" class="login__input" name="txtUser" id="txtUser" placeholder="Usuario" required value="" />
                        </div>
                        <div class="login__row">
                            <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
                                <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
                            </svg>
                            <input type="password" class="login__input pass" name="txtPwr" id="txtPwr" placeholder="Contraseña" required value="" />
                        </div>
                        <button type="submit" class="login__submit">Iniciar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
