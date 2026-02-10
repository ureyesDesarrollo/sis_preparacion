<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT mt_id as Clave, mt_descripcion as Origen_material,  tipo_estatus(mt_est) as Estatus
		FROM materiales_tipo";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información de los materiales");
$materiales = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$materiales[] = $rows;
}

if(!empty($materiales)) 
{

$filename = "tipo_materiales.xls";

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