<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

/*if(isset($_POST["export_data"])) 
{*/

$reg_autorizado = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'"));

if ($reg_autorizado['up_ban'] == 1) 
{
    //echo $registros['prv_nombre'];
	$str_campo = 'prv_nombre';
} else {
    //echo $registros['prv_ncorto'];
	$str_campo = 'prv_ncorto';
}

$sql = "SELECT prv_id as Clave, $str_campo as Nombre,prv_nom_comercial as Nombre_comercial, tipo_proveedor(prv_tipo) as Tipo, prv_rfc as RFC, prv_telefono as Telefono, prv_email as Email, prv_contacto as Contacto, tipo_estatus(prv_est) as Estatus
		FROM proveedores";

$resultado = mysqli_query($cnx, $sql) or die(mysqli_error($cnx) . "Error al consultar la informacion de los proveedores");
$proveedores = array();

while ($rows = mysqli_fetch_assoc($resultado)) {
	$proveedores[] = $rows;
}


if (!empty($proveedores)) {

	$filename = "proveedores.xls";

	header("Content-Type: application/vnd.ms-excel");

	header("Content-Disposition: attachment; filename=" . $filename);

	$mostrar_columnas = false;

	foreach ($proveedores as $proveedor) {

		if (!$mostrar_columnas) {

			echo implode("\t", array_keys($proveedor)) . "\n";

			$mostrar_columnas = true;
		}

		echo implode("\t", array_values($proveedor)) . "\n";
	}
} else {

	echo "No hay datos a exportar";
}

/*	exit;

}*/

mysqli_close($cnx);

//https://www.acens.com/white-papers/exportar-datos-excel-php-mysql/
?>
