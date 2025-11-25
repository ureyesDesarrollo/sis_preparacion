<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT pt_id as Clave, pt_descripcion as Descripcion, pt_revision AS Revision, tipo_estatus(pt_estatus) as Estatus
		FROM preparacion_tipo";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la informaci�n de los tipos de proceso");
$tipos_proceso = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$tipos_proceso[] = $rows;
}

if(!empty($tipos_proceso)) 
{

$filename = "tipos_proceso.xls";

header("Content-Type: application/vnd.ms-excel");

header("Content-Disposition: attachment; filename=".$filename);

$mostrar_columnas = false;

foreach($tipos_proceso as $tupla) 
{

	if(!$mostrar_columnas) 
	{

		echo implode("\t", array_keys($tupla)) . "\n";
	
		$mostrar_columnas = true;

	}

	echo implode("\t", array_values($tupla)) . "\n";

}
}
else
{

	echo "No hay datos a exportar";

}

mysqli_close($cnx);
?>