<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/


include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php')
?>

<link rel="stylesheet" href="../css/estilos_submenu.css">
<link href='../bootstrap/fonts/Sans.css' rel='stylesheet' type='text/css'>
<link href='../bootstrap/fonts/Roboto.css' rel='stylesheet' type='text/css'>

<div class="container">

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">Indicadores</li>
		</ol>
	</nav>
	<ul class="nav container">
		<div class="row">
			<?php
			//echo fnc_permiso($_SESSION['privilegio'], 10, 'upe_listar' );
			if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 5 or $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 15 or $_SESSION['privilegio'] == 28) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="../indicadores/index.php">
						<img class="icon" src="../iconos/process.png" alt="">
						<span>Supervisor</span>
					</a>
				</li><?php } ?>
			<?php if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 15) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="../indicadores/tablero_direccion.php">
						<img class="icon" src="../iconos/process.png" alt="">
						<span>Dirección</span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 15 or $_SESSION['privilegio'] == 28) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="../indicadores/tablero_laboratorio.php">
						<img class="icon" src="../iconos/process.png" alt="">
						<span>Laboratorio</span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 15) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="../reportes/listado_oee.php">
						<img class="icon" src="../iconos/dashb.png" alt="">
						<span>OEE</span>
					</a>
				</li>
			<?php } ?>
			<!-- <?php
					if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 7 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 5 or $_SESSION['privilegio'] == 13 or $_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 15) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="../indicadores/tablero_pelambre.php">
						<img class="icon" src="../iconos/process.png" alt="">
						<span>Pelambre</span>
					</a>
				</li><?php } ?> -->
		</div>

	</ul>

</div>
<?php include "../generales/pie_pagina.php"; ?>