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
			<li class="breadcrumb-item active">Catálogos</li>
		</ol>
	</nav>
	<ul class="nav container">
		<div class="row">
			<?php
			//echo fnc_permiso($_SESSION['privilegio'], 10, 'upe_listar' );
			if (fnc_permiso($_SESSION['privilegio'], 10, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="usuarios_catalogo.php">
						<img class="icon" src="../iconos/user.png" alt="">
						<span>Usuarios</span>
					</a>
				</li><?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 7, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="proveedores_catalogo.php">
						<img class="icon" src="../iconos/prov.png" alt="">
						<span>Proveedores</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 8, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="mat_tipo_catalogo.php">
						<img class="icon" src="../iconos/tipos.png" alt="">
						<span>Origen material</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 5, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="materiales_catalogo.php">
						<img class="icon" src="../iconos/materiales.png" alt="">
						<span>Materiales</span>
					</a>
				</li>
			<?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_listar') == 1) { ?>
				<li class="fb" style="margin-bottom: 20px">
					<a href="../catalogos/equipos_add.php">
						<img class="icon" src="../iconos/nuevo_img/equipo.png" alt="">
						<span>Equipos</span>
					</a>
				</li>
			<?php } ?>

			<?php if (fnc_permiso($_SESSION['privilegio'], 33, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="parametros_catalogo.php">
						<img class="icon" src="../iconos/procesos.png" alt="">
						<span>Parámetros</span>
					</a>
				</li><?php } ?>
			<?php if (fnc_permiso($_SESSION['privilegio'], 34, 'upe_listar') == 1) { ?>
				<li class="hm" style="margin-bottom: 20px">
					<a href="proveedores_permisos.php">
						<img class="icon" src="../iconos/hidden.png" alt="">
						<span>Visibilidad proveedor</span>
					</a>
				</li><?php } ?>

		</div>

	</ul>

</div>
<?php include "../generales/pie_pagina.php"; ?>