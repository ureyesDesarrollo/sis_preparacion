 <script>
  function filtro(){
    var datos={
      "mes": $("#mes").val(),
    }
    $.ajax({
      type:'post',
      url: 'lotes_filtro.php',
      data: datos,
      //data: {nombre:n},
      success: function(d){
        $("#tab2").html(d);
         document.getElementById('fechaInicio').value = '';
          document.getElementById('fechaFinal').value = '';
      } 
    });
    //return false;
  }

//opción imprimir
function imprimir(){
 window.print();
 return false;
}

function exportar(){
  var mes = document.getElementById('mes').value;
  var fechaInicio = document.getElementById('fechaInicio').value;
  var fechaFinal = document.getElementById('fechaFinal').value;
        /*window.location.href = 'formatos/listado_historial.php?fchi='+ encodeURIComponent(fchi) 
        +'&fchf=' + encodeURIComponent(fchf);*/
        window.open('../exportar/lotes_exportar.php?mes='+ encodeURIComponent(mes)
          +'&fechaInicio=' + encodeURIComponent(fechaInicio)
          +'&fechaFinal=' + encodeURIComponent(fechaFinal));
      }

      function reset(){
        location.href = "listado_lotes.php";
      //var fchi = document.getElementById('mes').value = '';
      //var fchf = document.getElementById('fechaFinal').value = '';

    }


  //AGREGADO POR CC 04-05
  function filtroAño(){
    var datos={
      "fechaini": $("#fechaInicio").val(),
      "fechafin": $("#fechaFinal").val(),
    }


    $.ajax({
      type:'post',
      url: 'lotes_filtro_rango.php',
      data: datos,
      //data: {nombre:n},
      success: function(d){
        $("#tab2").html(d);
        document.getElementById('mes').value = '';
      } 
    });
    //return false;
  }

</script>

<style>
  @media print{
    .ocultar{
      display: none !important;
    }
    @page {
      size: A4 landscape; 
    }
  }
}
</style>
<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 
$year = date("Y");
$cadena = mysqli_query($cnx, "SELECT * FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' ") or die(mysql_error()."Error: en consultar lotes");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

$cont1 = mysqli_query($cnx, "SELECT COUNT(tarima_bloom) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_bloom > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont1 = mysqli_fetch_assoc ($cont1);
$cont1 = $reg_cont1['cont'];

$cont2 = mysqli_query($cnx, "SELECT COUNT(tarima_viscocidad) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_viscocidad > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont2 = mysqli_fetch_assoc ($cont2);
$cont2 = $reg_cont2['cont'];

$cont3 = mysqli_query($cnx, "SELECT COUNT(tarima_ph_final) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_ph_final > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont3 = mysqli_fetch_assoc ($cont3);
$cont3 = $reg_cont3['cont'];

$cont4 = mysqli_query($cnx, "SELECT COUNT(tarima_transparencia) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_transparencia > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont4 = mysqli_fetch_assoc ($cont4);
$cont4 = $reg_cont4['cont'];

$cont5 = mysqli_query($cnx, "SELECT COUNT(tarima_porcen_t) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_porcen_t > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont5 = mysqli_fetch_assoc ($cont5);
$cont5 = $reg_cont5['cont'];

$cont6 = mysqli_query($cnx, "SELECT COUNT(tarima_ntu) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_ntu > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont6 = mysqli_fetch_assoc ($cont6);
$cont6 = $reg_cont6['cont'];

$cont7 = mysqli_query($cnx, "SELECT COUNT(tarima_humedad) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_humedad > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont7 = mysqli_fetch_assoc ($cont7);
$cont7 = $reg_cont7['cont'];

$cont8 = mysqli_query($cnx, "SELECT COUNT(tarima_cenizas) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_cenizas > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont8 = mysqli_fetch_assoc ($cont8);
$cont8 = $reg_cont8['cont'];

$cont9 = mysqli_query($cnx, "SELECT COUNT(tarima_redox) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_redox > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont9 = mysqli_fetch_assoc ($cont9);
$cont9 = $reg_cont9['cont'];

$cont10 = mysqli_query($cnx, "SELECT COUNT(tarima_color) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_color > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont10 = mysqli_fetch_assoc ($cont10);
$cont10 = $reg_cont10['cont'];

$cont11 = mysqli_query($cnx, "SELECT COUNT(tarima_grano) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_grano > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont11 = mysqli_fetch_assoc ($cont11);
$cont11 = $reg_cont11['cont'];

$cont12 = mysqli_query($cnx, "SELECT COUNT(tarima_part_ext) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_part_ext > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont12 = mysqli_fetch_assoc ($cont12);
$cont12 = $reg_cont12['cont'];

$cont13 = mysqli_query($cnx, "SELECT COUNT(tarima_part_ind) as cont FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' AND tarima_part_ind > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont13 = mysqli_fetch_assoc ($cont13);
$cont13 = $reg_cont13['cont'];


$cad_tar =  mysqli_query($cnx, "SELECT SUM(tarima_lim_param) AS limite, SUM(tarima_bloom) as bloom, SUM(tarima_viscocidad) as viscosidad,SUM(tarima_ph_final) as ph_f, SUM(tarima_transparencia) as trans,SUM(tarima_porcen_t) AS porc, sum(tarima_ntu) as ntu, sum(tarima_humedad) as humedad,sum(tarima_cenizas) as cenizas,sum(tarima_redox) as redox,sum(tarima_color) as color, sum(tarima_grano) as grano,sum(tarima_olor) as olor, sum(tarima_part_ext) as partE,sum(tarima_part_ind) as partI FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."' ") or die(mysql_error()."Error: en consultar tarimas");
$reg_tar =  mysqli_fetch_array($cad_tar);

?>

<!DOCTYPE html>
<html lang="en" >
<head>
 <meta charset="UTF-8">
 <title>Listado de Inventario <?php echo date("d-m-Y"); ?></title>
 <link rel="stylesheet" href="../../css/estilos_formatos.css">
 <style type="text/css">
  td{
    border: 1px solid #e6e6e6
  }
</style>
</head>

<body>
	<div class="container">
    <table class="ocultar" style="border: 1px solid #000;width: 100%"> 
      <tr style="border: 15px solid #fff">
        <td style="font-size: 18px;font-weight: bold;border: 15px solid #fff" width="799" height="28" align="right">Filtrar por Mes:</td>
        <td>
         <select style="height: 25px;border-radius: 10px;width: 90px" class="form-control" id="mes" onchange="filtro()">
           <option value="">Selecciona</option>
           <?php 
           $cad_mes = mysqli_query($cnx,"SELECT DISTINCT lote_mes FROM lotes WHERE  SUBSTRING(lote_fecha, 1, 4) = '$year' ");
           while($reg_mes =  mysqli_fetch_assoc($cad_mes)) {
             ?>
             <option value="<?php echo mb_convert_encoding($reg_mes['lote_mes'], "UTF-8");  ?>">
              <?php echo mb_convert_encoding($reg_mes['lote_mes'], "UTF-8");  ?>
            </option>
          <?php }?>
        </select>
      </td>
      <td style="font-size: 18px;font-weight: bold;border: 15px solid #fff" width="799" height="28" align="right">Filtar por rango:</td>
      <td>
        <input   style="height: 25px;border-radius: 10px;width: 130px" type="date" id="fechaInicio" class="form-control"  name="fechaInicio">
      </td>
      <td style="border: 15px solid #fff">a</td>
      <td style="border: 15px solid #fff">
       <input  style="height: 25px;border-radius: 10px;width: 130px"  type="date" id="fechaFinal" class="form-control"  name="fechaFinal">
     </td>
     <td  style="font-size: 18px;font-weight: bold;border: 15px solid #fff" width="102" align="right"><button onclick="filtroAño()" type="button" style="height: 30px;border-radius: 5px;background: #2e6da4;border: 0px;color: #fff">Buscar  </button></td>

     <td  style="font-size: 18px;font-weight: bold;border: 15px solid #fff" width="102" align="right"><button onclick="imprimir()" type="button" style="height: 30px;border-radius: 5px;background: #2e6da4;border: 0px;color: #fff">Imprimir  </button></td>
     <td  style="font-size: 18px;font-weight: bold;border: 15px solid #fff" width="102" align="right"><button onclick="exportar()" type="button" style="height: 30px;border-radius: 5px;background: #2e6da4;border: 0px;color: #fff">Exportar	</button></td>
     <td  style="font-size: 18px;font-weight: bold;border: 15px solid #fff" width="150" align="left"><button onclick="reset()" type="button" style="height: 30px;border-radius: 5px;background: #2e6da4;border: 0px;color: #fff">Ver todo</button></td>
   </tr>
 </table>

 <div class="tablehead" style="width: 100%">
   <table width="1187" style="border: 1px solid #fff">
    <tr style="border: 1px solid #fff">
     <td align="center" style="border: 1px solid #fff"><img src="../../imagenes/logo_progel_v3.png"></td>
     <td align="center" style="border: 1px solid #fff"><h2>REPORTE DIARIO DE LOTES Y REVOLTURAS</h2></td>
     <td align="center" style="border: 1px solid #fff"><p>LAB F 009 - REV 004</p>
       <p>FECHA:<?php echo fnc_formato_fecha(date("Y-m-d")); ?></p></td>
     </tr>
   </table>
 </div>

 <div id="tab2" style="margin-bottom: 20px">
   <!--TABLA EXTRANJERO-->
   <table border="1" class="table table-striped" style="font-size: 12px;width: 100%;margin-bottom: 100px;border: 1px solid#F0EFEF">
    <thead>
      <tr style="text-align: center;border: 1px solid#9B9A9A">
        <th width="3%">FOLIO LOTE</th>
        <!--<th width="4%">LOTE</th>-->
        <th width="4%">NUMERO DE TARIMA</th>
        <th width="7%">FECHA</th>
        <th>HORA</th>
        <th>MES</th>
        <th>TURNO</th>
        <th></th>
        <th>BLOOM</th>
        <th>VISC.</th>
        <th>PH FINAL</th>
        <th>TRANS</th>
        <th>%T(620)</th>
        <th>NTU</th>
        <th>HUMEDAD</th>
        <th>CENIZAS</th>
        <th>REDOX</th>
        <th>COLOR</th>
        <th>GRANO MALLA #45</th>
        <th>OLOR</th>
        <th>PART. EXTRAÑAS</th>
        <th>PART. IND 6,66%</th>
        <th>HIDRATACIÓN</th>
          <th>ACEPT. / RECH.</th>
        </tr>
        <tr style="background: #e6e6e6;color: #000;text-align: center;">
          <!--<td style="border: 1px solid#F0EFEF"></td>-->
          <td style="border: 1px solid#F0EFEF"></td>
          <td style="border: 1px solid#F0EFEF"></td>
          <td style="border: 1px solid#F0EFEF"></td>
          <td style="border: 1px solid#F0EFEF"></td>
          <td style="border: 1px solid#F0EFEF"></td>
          <td style="border: 1px solid#F0EFEF"></td>
          <td style="border: 1px solid#F0EFEF">LIM PARAM</td>
          <td style="border: 1px solid#F0EFEF">MIN 100</td>
          <td style="border: 1px solid#F0EFEF">MIN .15-16 MAX</td>
          <td style="border: 1px solid#F0EFEF">5.5-6.0</td>
          <td style="border: 1px solid#F0EFEF">MIN 15</td>
          <td style="border: 1px solid#F0EFEF">70% MIN.</td>
          <td style="border: 1px solid#F0EFEF">60 MIN</td>
          <td style="border: 1px solid#F0EFEF">12%MAX</td>
          <td style="border: 1px solid#F0EFEF">1.5%MAX</td>
          <td style="border: 1px solid#F0EFEF">30 PPM MAX</td>
          <td style="border: 1px solid#F0EFEF">3 MAX</td>
          <td style="border: 1px solid#F0EFEF">40% MIN</td>
          <td style="border: 1px solid#F0EFEF">SIN OLOR EXTRAÑO</td>
          <td style="border: 1px solid#F0EFEF">0-25 MAX</td>
          <td style="border: 1px solid#F0EFEF">MAXIMO 6 GRANOS</td>
          <td>MAL-BIEN</td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <?php  do { 

          $lotes = mysqli_query($cnx, "SELECT * FROM lotes WHERE lote_id = '$registros[lote_id]' ") or die(mysql_error()."Error: en consultar lotes");
          $reg_lotes = mysqli_fetch_assoc($lotes);

if ($reg_lotes['lote_id'] == '') {?>
  <tr>
    <td style="text-align: center;font-size: 18px;color: #FD98B8" colspan="24">No hay registros del día</td>
  </tr>
<?php } else{ ?>
  
          <tr style="text-align: center;">
            <td><?php echo $reg_lotes['lote_folio'] ?></td>
            <!--<td><?php echo $reg_lotes['lote_id'] ?></td>-->
            <td><?php echo $registros['tarima_id'] ?></td>
            <td><?php echo fnc_formato_fecha($registros['tarima_fecha']) ?></td>
            <td><?php echo $reg_lotes['lote_hora'] ?></td>
            <td><?php echo $reg_lotes['lote_mes'] ?></td>
            <td><?php echo $reg_lotes['lote_turno'] ?></td>
            <td><?php echo $registros['tarima_lim_param'] ?></td>
            <td><?php echo $registros['tarima_bloom'] ?></td>
            <td><?php echo $registros['tarima_viscocidad'] ?></td>
            <td><?php echo $registros['tarima_ph_final'] ?></td>
            <td><?php echo $registros['tarima_transparencia'] ?></td>
            <td><?php echo $registros['tarima_porcen_t'] ?></td>
            <td><?php echo $registros['tarima_ntu'] ?></td>
            <td><?php echo $registros['tarima_humedad'] ?></td>
            <td><?php echo $registros['tarima_cenizas'] ?></td>
            <td><?php echo $registros['tarima_redox'] ?></td>
            <td><?php echo $registros['tarima_color'] ?></td>
            <td><?php echo $registros['tarima_grano'] ?></td>
            <td><?php echo $registros['tarima_olor'] ?></td>
            <td><?php echo $registros['tarima_part_ext'] ?></td>
            <td><?php echo $registros['tarima_part_ind'] ?></td>
            <td><?php echo $registros['tarima_hidratacion'] ?></td>
            <td><?php echo $registros['tarima_aceptado'] ?></td>
          </tr>
        <?php } } while ($registros = mysqli_fetch_assoc($cadena)); ?>
      </tbody>
       <tfoot style="background: #F4F3F3;text-align: center;font-weight: bold;">
        <tr style="background: #F4F3F3;text-align: center;font-weight: bold;border: 1px solid#e6e6e6">
         <td colspan="6">Promedio:</td>
         <td></td>
         <td align="center">---</td>
         <td><?php if($reg_tar['bloom'] == '' or $reg_tar['bloom'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['bloom'] / $cont1), 2); } ?></td>
         <td><?php if($reg_tar['viscosidad'] == '' or $reg_tar['viscosidad'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['viscosidad'] / $cont1), 2); } ?></td>
         <td><?php if($reg_tar['ph_f'] == '' or $reg_tar['ph_f'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['ph_f'] / $cont3), 2); } ?></td>
         <td><?php if($reg_tar['trans'] == '' or $reg_tar['trans'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['trans'] / $cont4), 2); } ?></td>
         <td><?php if($reg_tar['porc'] == '' or $reg_tar['porc'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['porc'] / $cont5), 2); } ?></td>
         <td><?php if($reg_tar['ntu'] == '' or $reg_tar['ntu'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['ntu'] / $cont6), 2); }?></td>
         <td><?php if($reg_tar['humedad'] == '' or $reg_tar['humedad'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['humedad'] / $cont7), 2); } ?></td>
         <td><?php if($reg_tar['cenizas'] == '' or $reg_tar['cenizas'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['cenizas'] / $cont8), 2); } ?></td>
         <td><?php if($reg_tar['redox'] == '' or $reg_tar['redox'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['redox'] / $cont9), 2); } ?></td>
         <td><?php if($reg_tar['color'] == '' or $reg_tar['color'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['color'] / $cont10), 2); }?></td>
         <td><?php if($reg_tar['grano'] == '' or $reg_tar['grano'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['grano'] / $cont11), 2); }?></td>
         <td>---</td>
         <td><?php if($reg_tar['partE'] == '' or $reg_tar['partE'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['partE'] / $cont12), 2); }?></td>
         <td><?php if($reg_tar['partI'] == '' or $reg_tar['partI'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['partI'] / $cont13), 2); }?></td>
          <td align="center">---</td>
           <td align="center">---</td>
       </tr>
     </tfoot>
    </table>

    <table border="1" class="table table-striped" style="font-size: 12px;margin-bottom: 100px;width: 75%;margin-bottom: 220px;">
      <thead>
        <tr style="text-align: center;">
         <th width="200px">TABLA DE COLOR/OLOR</th>
         <th width="200px">CALIFICACIÓN</th>
       </tr>
     </thead>
     <tbody>
      <tr style="text-align: center;">
        <td>EXCELENTE</td>
        <td>0</td>
      </tr>
      <tr style="text-align: center;">
        <td>MUY BIEN</td>
        <td>1</td>
      </tr>
      <tr style="text-align: center;">
        <td>BIEN</td>
        <td>2</td>
        <td width="200px"  style="border: 1px solid#fff"></td>
        <td width="200px"  colspan="" valign="top" style="border: 1px solid#fff;border-top: 1px solid#000">REVISÓ</td>
        <td width="50px"  style="border: 1px solid#fff"></td>
        <td width="200px"  colspan="" valign="top" style="border: 1px solid#fff;border-top: 1px solid#000">AUTORIZÓ</td>
      </tr>
      <tr style="text-align: center;">
        <td>ULTIMO COLOR ACEPTABLE</td>
        <td>3</td>
      </tr>
      <tr style="text-align: center;">
        <td>MAL</td>
        <td>4</td>
      </tr>
      <tr style="text-align: center;">
        <td>MUY MAL</td>
        <td>5</td>
      </tr>
    </tbody>
  </table>
</div>

<div style="padding-top: 120px">
<?php include "../../generales/pie_pagina_formato.php";?>
</div>
</body>
</html>

<script src="../../js/jquery.min.js"></script>
<script src="../../js/jspdf.js"></script>
<script src="../../js/pdfFromHTML.js"></script>

