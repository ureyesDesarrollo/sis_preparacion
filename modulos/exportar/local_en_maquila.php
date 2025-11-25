<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT i.inv_no_ticket as 'No. ticket', inv_fecha as Fecha, inv_placas as Placas, inv_camioneta as Camioneta, p.prv_nombre as Proveedor, tipo_proveedor(p.prv_tipo) as Tipo, m.mat_nombre as Material, inv_kilos as 'Kg entrada', inv_prueba as 'Prueba Secador', inv_desc_ag as 'Desc. Agua',	inv_desc_d	as 'Desc. descarne', inv_desc_ren as 'Desc. Rendimiento', inv_kg_totales as 'Kg a pagar'
						 FROM inventario as i
						 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
						 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
						 WHERE p.prv_tipo = 'L' and i.inv_enviado = 1 and p.prv_ban = 1 and i.inv_tomado = 0 ORDER BY prv_tipo";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información del inventario");

$inventario = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$inventario[] = $rows;
}


if(!empty($inventario)) 
{

$filename = "inventario_local_en_maquila.xls";

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