<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT i.idm_id as Clave, u.usu_usuario as Usuario, i.idm_fecha as Fecha, i.idm_documento as Documento, m.mat_nombre as Material, i.idm_cant_ing as Cant_Ingreso, i.idm_cant_ant as Cant_Anterior, i.idm_cant_new as Cant_Nuevo 
	 FROM inventario_diario_materiales as i
	 INNER JOIN usuarios as u on (i.usu_id = u.usu_id)
	 INNER JOIN materiales as m on (i.mat_id = m.mat_id)
	 ";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información de los movimientos");

$inventario = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$inventario[] = $rows;
}


if(!empty($inventario)) 
{

$filename = "mov_inventario.xls";

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