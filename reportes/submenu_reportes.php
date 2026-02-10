<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
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
			<li class="breadcrumb-item active">Reportes</li>
		</ol>
	</nav>
	<ul class="nav container">
		<div class="row">
			<?php if (fnc_permiso($_SESSION['privilegio'], 4, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="acciones_reporte.php">
						<img class="icon" src="../iconos/bitacora.png" alt="">
						<span>Listado Acciones</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 6, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="mov_inventario_reporte.php">
						<img class="icon" src="../iconos/movimientos.png" alt="">
						<span>Mov. Inventario</span>
					</a>
				</li>
			<?php } ?>
			<!-- <?php if (fnc_permiso($_SESSION['privilegio'], 2, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="indicadores.php">
						<img class="icon" src="../iconos/indicadores.png" alt="">
						<span>Indicadores</span>
					</a>
				</li>
			<?php } ?> -->
			<?php if (fnc_permiso($_SESSION['privilegio'], 15, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="mov_estatus_reporte.php">
						<img class="icon" src="../iconos/estatus.png" alt="">
						<span>Mov. Estatus</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 16, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="bitacora_reporte.php">
						<img class="icon" src="../iconos/bitacora2.png" alt="">
						<span>List. Bitacora Historial</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 16, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="bitacora.php">
						<img class="icon" src="../iconos/bitacora2.png" alt="">
						<span>Listado bitacoras</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 19, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="rep_general_reporte.php">
						<img class="icon" src="../iconos/rep_gral.png" alt="">
						<span>Rep. General</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 22, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="reporte_quimicos.php">
						<img class="icon" src="../iconos/quimico.png" alt="">
						<span>Químicos</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 23, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="alertas_reporte.php">
						<img class="icon" src="../iconos/alarma2.png" alt="">
						<span>Rep. Alertas</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 28, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="rep_cuero_preparacacion.php">
						<img class="icon" src="../iconos/tipos.png" alt="">
						<span>Cuero a preparación</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 18, 'upe_listar') == 1) {  ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="rep_lotes.php">
						<img class="icon" src="../iconos/lote.png" alt="">
						<span>Lotes</span>
					</a>
				</li>
			<?php   }  ?>
			<?php if ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2) {  ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="rep_reporte.php">
						<img class="icon" src="../iconos/bitacora2.png" alt="">
						<span>Reporte</span>
					</a>
				</li>
			<?php   }  ?>
			<!-- <?php if (fnc_permiso($_SESSION['privilegio'], 28, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="mat_recibido_naciona_internacional.php">
					<img class="icon" src="../iconos/contabilidad.png" alt="">
						<span>Reportes contabilidad</span>
					</a>
				</li>
			<?php } ?> -->

			<!-- <?php if (fnc_permiso($_SESSION['privilegio'], 14, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 10px">
					<a href="bitacora_pelambre.php">
						<img class="icon" src="../iconos/bitacora2.png" alt="">
						<span>Bitacora Pelambre</span>
					</a>
				</li>
			<?php } ?> -->

		</div>

	</ul>

</div>
<?php include "../generales/pie_pagina.php"; ?>