<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

/*$cadena = mysqli_query($cnx, "SELECT YEAR(lote_fecha) as fecha, lote_mes  FROM lotes") or die(mysql_error()."Error: en consultar el inventario");*/


$cadena = mysqli_query($cnx, "SELECT DISTINCT lote_mes, YEAR(lote_fecha) as fecha  FROM lotes") or die(mysql_error()."Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);
//$rows = mysqli_num_rows($cadena);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
  <!--paginación de tabla-->
  $(document).ready(function()
  {
    $('#tabla_lotes').dataTable( { 
      "sPaginationType": "full_numbers"
    } );
  })
</script>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
  <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lotes">
    <thead>
      <tr align="center">
        <th>Año</th>
        <th>Més</th>
        <th>Total de lotes</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $ren = 1;
      $flt_kg = 0;
      $flt_kg_t = 0;
      do{
        $cad_sum = mysqli_query($cnx, "SELECT  lote_folio FROM lotes WHERE lote_mes = ".$registros['lote_mes']." ") or die(mysqli_error()."Error: en consultar el inventario");
        $regSum = mysqli_fetch_assoc($cad_sum);
        $regSum = mysqli_num_rows($cad_sum);

        ?>
        <tr height="20">
         <td><?php echo $registros['fecha'] ?></td>
         <td><?php echo $registros['lote_mes'].' - '.fnc_formato_mes($registros['lote_mes']) ?></td>
         <td><a href="rep_general_reporte_mes.php?mes=<?php echo $registros['lote_mes'];?>"><?php echo $regSum  ?></a></td>
       </tr>
       <?php 
       $ren += 1;

       $flt_kg += $registros['inv_kilos'];
       $flt_kg_t += $registros['inv_kg_totales'];

     }while($registros = mysqli_fetch_assoc($cadena));?>

   </tbody>

   <tfoot>
     <?php for($i=$ren; $i <= 12; $i++){?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    <?php }?>
    <tr>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </tfoot>
</table>
</div>