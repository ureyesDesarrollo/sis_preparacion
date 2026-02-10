<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();
extract($_POST);
// Ejecutar la consulta SQL

$str_filtro = '';

if ($fecha_inicio != '') {
    $str_filtro .= " and  p.pro_fe_carga >= '$fecha_inicio'";
}
if ($fecha_fin != '') {
    $str_filtro .= " and p.pro_fe_carga <= '$fecha_fin'";
}

$cad = mysqli_query($cnx, "SELECT i.inv_id,m.mat_nombre, i.inv_kg_totales ,i.inv_fecha, YEAR(i.inv_fecha) as anio,SUM(i.inv_kg_totales) as total 
FROM inventario as i 
INNER JOIN materiales as m ON i.mat_id = m.mat_id 
WHERE YEAR(i.inv_fecha) = '2023' 
GROUP BY m.mat_id");
$tot = mysqli_num_rows($cad);

// Convertir el resultado de la consulta a un objeto JSON
$bitacora = array();
while ($reg_bitacora = mysqli_fetch_assoc($cad)) {
    $bitacora[] = $reg_bitacora;
}


// Cerrar la conexión a la base de datos
mysqli_close($cnx);

// Devolver el objeto JSON
echo json_encode($bitacora);
