<?php
/*Desarrollado por CCA Consultores*/
/*31 - Octubre - 2021*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);
$cad = mysqli_query($cnx, "SELECT ep_carga_max FROM equipos_preparacion WHERE ep_id = '" . $eq_nuevo . "' ");
$reg =  mysqli_fetch_array($cad);

echo $reg['ep_carga_max'];
