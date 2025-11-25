<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT b.ba_id as Clave, u.usu_usuario as Usuario, b.ba_fecha as Fecha, m.bm_descripcion as Modulo, tipo_accion(b.ba_accion) as Accion, b.ba_valor as Valor 
	 FROM bitacora_acciones as b
	 INNER JOIN usuarios as u on (b.usu_id = u.usu_id)
	 inner join bitacora_modulos as m on (b.bm_id = m.bm_id)";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información de la bitacora");

$inventario = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$inventario[] = $rows;
}


if(!empty($inventario)) 
{

$filename = "listado_acciones.xls";

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