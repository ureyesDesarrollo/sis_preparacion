<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT mto_id as Clave, mt_descripcion AS Descripcion, mto_kilos As Kilos, mto_fecha As Fecha, prv_nombre as Proveedor
		FROM materiales_tipo_obj AS o
		INNER JOIN materiales_tipo AS t ON (o.mt_id = t.mt_id)
		INNER JOIN proveedores as p on(o.prv_id = p.prv_id)  where mto_fecha >= '2024-01-01'";

$resultado = mysqli_query($cnx, $sql) or die(mysqli_error($cnx) . "Error al consultar la informacion");
$tipos_proceso = array();

while ($rows = mysqli_fetch_assoc($resultado)) {
	$tipos_proceso[] = $rows;
}

if (!empty($tipos_proceso)) {

	$filename = "materiales_obj.xls";

	header("Content-Type: application/vnd.ms-excel");

	header("Content-Disposition: attachment; filename=" . $filename);

	$mostrar_columnas = false;

	foreach ($tipos_proceso as $tupla) {

		if (!$mostrar_columnas) {

			echo implode("\t", array_keys($tupla)) . "\n";

			$mostrar_columnas = true;
		}

		echo implode("\t", array_values($tupla)) . "\n";
	}
} else {

	echo "No hay datos a exportar";
}

mysqli_close($cnx);
