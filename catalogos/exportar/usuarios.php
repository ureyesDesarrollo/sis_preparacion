<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$sql = "SELECT usu_id as Clave, usu_nombre as Nombre, usu_usuario as Usuario, up_nombre as Perfil, usu_email as Email, tipo_estatus(usu_est) as Estatus
		FROM usuarios inner join usuarios_perfiles as up on(usuarios.up_id = up.up_id)
		WHERE usu_id <> 1";
		
$resultado = mysqli_query ($cnx, $sql) or die (mysqli_error($cnx)."Error al consultar la información de los usuarios");

$usuarios = array();

while($rows = mysqli_fetch_assoc($resultado)) 
{
	$usuarios[] = $rows;
}

if(!empty($usuarios)) 
{

$filename = "usuarios.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);

$mostrar_columnas = false;
foreach($usuarios as $usuarios) 
{
	if(!$mostrar_columnas) 
	{
		echo implode("\t", array_keys($usuarios)) . "\n";
		$mostrar_columnas = true;
	}
	echo implode("\t", array_values($usuarios)) . "\n";
}
}
else
{
	echo "No hay datos a exportar";
}

mysqli_close($cnx);
?>