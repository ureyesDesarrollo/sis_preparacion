<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
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
			<li class="breadcrumb-item active">Funciones</li>
		</ol>
	</nav>
	<ul class="nav container">
		<div class="row">
			<?php if (fnc_permiso($_SESSION['privilegio'], 9, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="tipo_proceso_modulo.php">
						<img class="icon" src="../iconos/procesos.png" alt="">
						<span>Tipos de proceso</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<?php if ($_SESSION['privilegio'] != 18) { ?>
						<a href="inventario_modulo.php">
							<img class="icon" src="../iconos/tipos.png" alt="">
							<span>Inventario</span>
						</a>
					<?php } else { #si es laboratorio almacen 
					?>
						<a href="inventario_modulo.php#listadohistorial">
							<img class="icon" src="../iconos/tipos.png" alt="">
							<span>Inventario</span>
						</a>
					<?php } ?>

				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 32, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="mp_croquis.php">
						<img class="icon" src="../iconos/croquis2.png" alt="">
						<span>Patio MP</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 17, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="materiales_obj_modulo.php">
						<img class="icon" src="../iconos/mat_obj.png" alt="">
						<span>Mat. Objetivo</span>
					</a>
				</li>
			<?php } ?>
			<!--<?php if (fnc_permiso($_SESSION['privilegio'], 20, 'upe_listar') == 1 or $_SESSION['idUsu'] == 25) { ?>
			<li class="fb" style="margin-bottom: 20px">
				<a href="respaldo.php">
					<img class="icon" src="../iconos/respaldo.png" alt="">
					<span>Respaldo</span>
					</a>
			</li>
			<?php } ?>-->
			<?php if (fnc_permiso($_SESSION['privilegio'], 12, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="etapas_catalogo.php">
						<img class="icon" src="../iconos/funciones.png" alt="">
						<span>Fases</span>
					</a>
				</li>
			<?php } ?>


			<?php if (fnc_permiso($_SESSION['privilegio'], 13, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="estatus_catalogo.php">
						<img class="icon" src="../iconos/estatus.png" alt="">
						<span>Estatus equipos</span>
						<!-- <span style="font-size:9px">(Lavadores/Paletos)</span> -->
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 18, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="lotes_modulo.php">
						<img class="icon" src="../iconos/lotes.png" alt="">
						<span>Lotes</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 21, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="mezcla_modulo.php">
						<img class="icon" src="../iconos/mezcla.PNG" alt="">
						<span>Mezclas</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 27, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="almacen_quimicos_add.php">
						<img class="icon" src="../iconos/nuevo_img/almacen-quimico.png" alt="">
						<span>Almacen químicos</span>
					</a>
				</li>
			<?php } ?>

		</div>
	</ul>

</div>
<?php include "../generales/pie_pagina.php"; ?>