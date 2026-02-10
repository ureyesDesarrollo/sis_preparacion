<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT b.bce_id as Clave, b.bce_fecha as Fecha, u.usu_usuario as Usuario, e.le_estatus as Est_actual, l.le_estatus as Est_Nuevo, b.bce_descripcion as Descripcion, pp_id as Clave_Paleto, pl_id as Clave_Lavador
		FROM bitacora_cambio_estatus AS b
		INNER JOIN usuarios as u on (b.usu_id = u.usu_id)
		INNER JOIN listado_estatus AS e ON(b.bce_est_actual = e.le_id)
		INNER JOIN listado_estatus AS l ON(b.bce_est_nuevo = l.le_id)
	 ";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información de los movimientos");

$inventario = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$inventario[] = $rows;
}


if(!empty($inventario)) 
{

$filename = "mov_estatus.xls";

header("Content-Type: application/vnd.ms-excel");

header("Content-Disposition: attachment; filename=".$filename);

$mostrar_columnas = false;

foreach($inventario as $proveedor) 
{

	if(!$mostrar_columnas) 
	{
		echo implode("\t", array_keys($proveedor)) . "\n";
	
		$mostrar_columnas = true;

	}

	echo implode("\t", array_values($proveedor)) . "\n";
}
}
else
{
	echo "No hay datos a exportar";
}


mysqli_close($cnx);
?>