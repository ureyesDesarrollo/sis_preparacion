<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
$mysqli = new mysqli('localhost:3306', 'root', 'Pr0gel+2024', 'bd_sis_preparacion');

if ($mysqli->connect_error) {

	die('Error en la conexion' . $mysqli->connect_error);
}
