<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include('../../seguridad/user_seguridad.php');

$cnx =  Conectarse();

$perfil_autorizado = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
$reg_autorizado = mysqli_fetch_assoc($perfil_autorizado);

if ($reg_autorizado['up_ban'] == 1) {
	$sql = "SELECT i.inv_no_ticket as 'No. ticket', inv_fecha as Fecha, inv_placas as Placas, inv_camioneta as Camioneta, p.prv_nombre as Proveedor,tipo_proveedor(p.prv_tipo) as Tipo, m.mat_nombre as Material, inv_kilos as 'Kg entrada', inv_prueba as 'Prueba Secador', inv_desc_ag as 'Desc. Agua',	inv_desc_d	as 'Desc. descarne', inv_desc_ren as 'Desc. Rendimiento', inv_kg_totales as 'Kg a pagar'
						 FROM inventario as i
						 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
						 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
						 ORDER BY prv_tipo ASC, inv_fecha ASC";
} else {
	$sql = "SELECT i.inv_no_ticket as 'No. ticket', inv_fecha as Fecha, inv_placas as Placas, inv_camioneta as Camioneta,p.prv_ncorto as Proveedor,tipo_proveedor(p.prv_tipo) as Tipo, m.mat_nombre as Material, inv_kilos as 'Kg entrada', inv_prueba as 'Prueba Secador', inv_desc_ag as 'Desc. Agua',	inv_desc_d	as 'Desc. descarne', inv_desc_ren as 'Desc. Rendimiento', inv_kg_totales as 'Kg a pagar'
	FROM inventario as i
	INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
	INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
	ORDER BY prv_tipo ASC, inv_fecha ASC";
}



$resultado = mysqli_query($cnx, $sql) or die(mysqli_error($cnx) . "Error al consultar la informaci�n del inventario");

$inventario = array();

while ($rows = mysqli_fetch_assoc($resultado)) {
	$inventario[] = $rows;
}


if (!empty($inventario)) {

	$filename = "inventario_total.xls; charset=utf-8";

	header("Content-Type: application/vnd.ms-excel");

	header("Content-Disposition: attachment; filename=" . $filename);

	$mostrar_columnas = false;

	foreach ($inventario as $proveedor) {

		if (!$mostrar_columnas) {
			echo implode("\t", array_keys($proveedor)) . "\n";
			$mostrar_columnas = true;
		}

		echo implode("\t", array_values($proveedor)) . "\n";
	}
} else {
	echo "No hay datos a exportar";
}


mysqli_close($cnx);
