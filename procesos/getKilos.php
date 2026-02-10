<?php
/*Desarrollado por CCA Consultores*/
/*01 - Enero - 2018*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);


//Selecciona los datos de todo el inventario
echo "SELECT MAX(inv_id) as inv_id,inv_id_key FROM inventario WHERE inv_id_key = '" . $inv_id . "'";
$cad_inv = mysqli_query($cnx, "SELECT MAX(inv_id) as inv_id,inv_id_key FROM inventario WHERE inv_id_key = '" . $inv_id . "' ");
$reg_inv =  mysqli_fetch_array($cad_inv);


//Selecciona los datos de todo el inventario
/* version_1
$cad_inv2 = mysqli_query($cnx, "SELECT i.inv_id, i.inv_kg_totales,p.prv_nombre,i.inv_fecha,inv_fe_recibe FROM inventario as i INNER JOIN proveedores as p 
	ON(i.prv_id = p.prv_id) 
	WHERE inv_id = ".$reg_inv['inv_id'].""); 
  
  version_2
    $cad_inv2 = mysqli_query($cnx, "SELECT i.inv_id, i.inv_kg_totales,p.prv_nombre,i.inv_fecha,inv_fe_recibe FROM inventario as i INNER JOIN proveedores as p 
	ON(i.prv_id = p.prv_id) 
	WHERE mat_id = ".$mat_id."");
  
  */
/* $cad_inv2 = mysqli_query($cnx, "SELECT i.inv_id,i.inv_id_key, i.inv_no_ticket, i.inv_kg_totales,p.prv_nombre, i.prv_id, i.inv_fecha, i.inv_fe_recibe, i.inv_enviado, p.prv_tipo
	from inventario as i 
	inner join proveedores as p on (i.prv_id = p.prv_id)
	where mat_id = '$mat_id' and 
	i.inv_tomado = 0 and i.inv_kg_totales > 0 
	and ( (p.prv_tipo = 'L' and  i.inv_enviado = 0) or (p.prv_tipo = 'L' and i.inv_enviado = 2) or (p.prv_tipo != 'L' and i.inv_enviado = 2)) order by inv_kg_totales");
   */
$cad_inv2 = mysqli_query($cnx, "SELECT i.inv_id,i.inv_id_key, i.inv_no_ticket, i.inv_kg_totales,p.prv_nombre, i.prv_id, i.inv_fecha, i.inv_fe_recibe, i.inv_enviado, p.prv_tipo
	from inventario as i 
	inner join proveedores as p on (i.prv_id = p.prv_id)
	WHERE inv_id = " . $reg_inv['inv_id'] . "");


//$regTipo =  mysqli_fetch_array($cad_inv2);

/* do {
	$html= "<option value='".$regTipo['inv_id']."'>".$regTipo['inv_kg_totales']." / ".$regTipo['prv_nombre']." / ".$regTipo['inv_fecha']." / ".$regTipo['inv_fe_recibe']."</option>";
} while ($regTipo =  mysqli_fetch_array($cad_inv2)); */


while ($row =  mysqli_fetch_array($cad_inv2)) {
 /*  if ($row['inv_id_key'] == $reg_inv['inv_id_key']) {
    $var = ' selected="selected" ';
  } else {
    $var = '';
  } */

 /*  echo '<option value="' . mb_convert_encoding($row['inv_id'], "UTF-8") . '"' . $var . '>';
  echo '' . mb_convert_encoding($row['inv_kg_totales'] . ' / ' . $row['prv_nombre'] . " / " . $row['inv_fecha'] . " / " . $row['inv_fe_recibe'] . " (" . $row['inv_id'] . ")", "UTF-8") . '';
  echo '</option>'; */
  $html= "<option style='text-align:right' value='".$row['inv_id']."'>".$row['inv_kg_totales']." / ".$row['prv_nombre']." / ".$row['inv_fecha']." / ".$row['inv_fe_recibe']. " (" . $row['inv_id'] . ")</option>";

}

echo $html;
//echo explode(" ", $html[1]);
