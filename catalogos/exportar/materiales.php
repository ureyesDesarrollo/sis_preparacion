<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT mat_id as Clave, mt_descripcion as Tipo,  mat_nombre as Material, um_descripcion as Unidad, mat_costo as Costo, mat_stock_min as StockMin, mat_stock_max as StockMin, mat_existencia as Existencia, tipo_estatus(mat_est) as Estatus, mat_comentarios as Comentarios 
	FROM materiales 
	inner join materiales_tipo as mt on(materiales.mt_id = mt.mt_id) 
	inner join unidades_medida as um on(materiales.um_id = um.um_id)";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información de los materiales");
$materiales = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$materiales[] = $rows;
}

if(!empty($materiales)) 
{

$filename = "materiales.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);

$mostrar_columnas = false;
foreach($materiales as $material) 
{
	if(!$mostrar_columnas) 
	{
		echo implode("\t", array_keys($material)) . "\n";
		$mostrar_columnas = true;
	}
	echo implode("\t", array_values($material)) . "\n";
}
}
else
{
	echo "No hay datos a exportar";
}
	
mysqli_close($cnx);
?>