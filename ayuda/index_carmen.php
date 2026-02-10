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
  <title>Videos usuarios</title>
</head>
<body>

<!-- Opciones Almacen -->
<?php if ($_SESSION['privilegio'] == 7 or $_SESSION['privilegio'] == 2) { ?>
<h3>Opciones Almacen</h3>

<h4>Video Proveedores</h4>
<video width="640" height="360" controls><source src="proveedores.mp4" type="video/mp4"></video>

<h4>Video Origen y Materiales</h4>
<video width="640" height="360" controls><source src="materialyorigen.mp4" type="video/mp4"></video>
<?php } ?>

<!-- Opciones Admin -->
<?php if ($_SESSION['privilegio'] == 2) { ?>
<h3>Video usuarios</h3>
<video width="640" height="360" controls><source src="usuarios.mp4" type="video/mp4"></video>
<?php } ?>

<?php if ($_SESSION['privilegio'] == 13 or $_SESSION['privilegio'] == 2) { ?>
<h3>Video tipos y equipos</h3>
<video width="640" height="360" controls><source src="equiposytipos.mp4" type="video/mp4"></video>
<?php } ?>

</body>
</html>