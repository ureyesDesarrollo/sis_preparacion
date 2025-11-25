<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
	
require_once('../../conexion/conexion.php');
$cnx =  Conectarse();

$est_id = $_POST['est_id'];

$cad_cbx =  mysqli_query($cnx, "SELECT ciu_id, ciu_descripcion FROM ciudades WHERE est_id = '$est_id' ORDER BY ciu_descripcion") or die(mysql_error()."Error: en consultar la ciudad");
$reg_cbx =  mysqli_fetch_array($cad_cbx);

?>
<option value="">Seleccionar Ciudad</option>
<?php 
do
{?>
	<option value="<?php echo $reg_cbx['ciu_id'] ?>"><?php echo $reg_cbx['ciu_descripcion'] ?></option>
<?php	
}while($reg_cbx =  mysqli_fetch_array($cad_cbx));
?>		