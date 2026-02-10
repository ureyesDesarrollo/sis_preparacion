<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT b.ba_id as Clave, u.usu_usuario as Usuario, b.ba_fecha as Fecha, b.pep_tipo as Parametro, b.pro_id as Proceso,b.ba_valor as Valor, p.pep_descripcion as Etapa, tipo_alerta(b.ba_tipo) as Tipo, b.ba_comentarios as Observaciones   
	 FROM bitacora_alertas as b
	 INNER JOIN usuarios as u on (b.usu_id = u.usu_id)
	 INNER JOIN preparacion_etapas_param as p on (b.pep_id = p.pep_id)
	 ";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la informaci�n de los movimientos");

$biatacora = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$biatacora[] = $rows;
}


if(!empty($biatacora)) 
{

$filename = "bitacora_alertas.xls";

header("Content-Type: application/vnd.ms-excel");

header("Content-Disposition: attachment; filename=".$filename);

$mostrar_columnas = false;

foreach($biatacora as $registro) 
{

	if(!$mostrar_columnas) 
	{
		echo implode("\t", array_keys($registro)) . "\n";
	
		$mostrar_columnas = true;

	}

	echo implode("\t", array_values($registro)) . "\n";
}
}
else
{
	echo "No hay datos a exportar";
}

mysqli_close($cnx);
?>