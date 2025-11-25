<?php
/*Desarrollado por CCA Consultores*/
/*01 - Enero - 2018*/
  include "../conexion/conexion.php";
  include "../funciones/funciones.php";
  $cnx =  Conectarse();

extract($_POST); 

//Selecciona los datos de todo el inventario
$cad_inv2 = mysqli_query($cnx, "SELECT inv_id, inv_kg_totales FROM inventario");

while($regTipo =  mysqli_fetch_array($cad_inv2))
{
	$html= "<option value='".$regTipo['inv_id']."'>".$regTipo['inv_kg_totales']."</option>";
}

echo $html;
?>