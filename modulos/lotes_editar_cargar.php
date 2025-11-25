<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST);

$cadena = mysqli_query($cnx, "SELECT * FROM tarimas WHERE lote_id=".$_GET['hdd_id']."") or die(mysqli_error()."Error: en consultar tarimas");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

$cadena_lot = mysqli_query($cnx, "SELECT * FROM lotes WHERE lote_id=".$_GET['hdd_id']."
  ") or die(mysqli_error()."Error: en consultar lotes");
$reg_lot = mysqli_fetch_assoc($cadena_lot);

$i=1;
?>

  <div id="main">
            <center>  
              <table border="1" class="table table-striped" style="font-size: 11px;">
                <div style="background: #fff;height: 80px;position: fixed;margin-top: -15px;margin-left: -15px; width: 1070px"></div>
                <thead style="text-align: center;">
                  <tr>
                     <th id="bloque3"><div id="subencabezado"> </div></th>
                    <th id="bloque3">No.<div id="subencabezado"> </div></th>
                    <th id="bloque3">FECHA<div id="subencabezado"> </div></th>
                    <th id="bloque3"><div id="subencabezado">LIMITES PARAMETROS</div></th>
                    <th id="bloque3">BLOOM<div id="subencabezado"> MIN 100</div></th>
                    <th id="bloque3">VISC. <div id="subencabezado">MIN .15-16 MAX</div></th>
                    <th id="bloque3">PH FINAL <div id="subencabezado">5.5-6.0</div></th>
                    <th id="bloque3">TRANSPARENCIA <div id="subencabezado">MIN 15</div></th>
                    <th id="bloque3"> %T(620) <div id="subencabezado">70% MIN. </div></th>
                    <th id="bloque3"> NTU <div id="subencabezado">60 MIN </div></th>
                    <th id="bloque3"> HUMEDAD <div id="subencabezado">  12%MAX</div></th>
                    <th id="bloque3"> CENIZAS <div id="subencabezado">1.5%MAX </div></th>
                    <th id="bloque3"> REDOX <div id="subencabezado">30 PPM MAX </div></th>
                    <th id="bloque3"> COLOR <div id="subencabezado">3 MAX </div></th>
                    <th id="bloque3"> GRANO MALLA #45 <div id="subencabezado">40% MIN </div></th>
                    <th id="bloque3"> OLOR <div id="subencabezado">SIN OLOR EXTRAÑO </div></th>
                    <th id="bloque3"> PART. EXTRAÑAS <div id="subencabezado">0-25 MAX </div></th>
                    <th id="bloque3"> PART. IND 6,66% <div id="subencabezado">MAXIMO 6 GRANOS </div></th>
                    <th id="bloque3"> HIDRATACIÓN <div id="subencabezado">MAL-BIEN </div></th>
                    <th id="bloque3"> ACEPT. / RECH. <div id="subencabezado"> </div></th>
                  </tr>
                </thead>
                <tbody id="campos">
                      <?php 
                //$i= 1;
                 //$i= 0;
                  do { 
                    //++$i;
                    //$i+=1;

                    ?>
                    <tr>
                      <input type="hidden" id="hdd_id" name="hdd_id" value="<?php echo $_GET['hdd_id'] ?>">
                      <td style="color: #fff"><!--<a href="javascript:fnc_baja(<?=$registros['tarima_id']?>);"> <img src="../iconos/borrar.png"  alt=""></a>--></td>
                      <td><?php echo $i; ?></td>
                      <td>
                        <input style="text-align: center;" class="form-control input-sm activo" style="width: 80px" type="hidden" id="<?php echo 'txtid'.$i?>" name="<?php echo 'txtid'.$i?>" value="<?php if ($registros['tarima_id'] == '0'){ echo "0"; }else{ echo '1'; } ?>">
                        <input class="form-control input-sm activo" style="width: 80px" type="hidden" id="<?php echo 'valor'.$i?>" name="<?php echo 'valor'.$i?>" value="<?php echo ($registros['tarima_id']); ?>">

                        <input readonly="" class="form-control input-sm activo renglon" style="width:110px" type="text" id="<?php echo 'txtFecha'.$i?>" name="<?php echo "txtFecha".$i?>"  value="<?php if ($registros['tarima_fecha'] == ''){ echo date("Y-m-d"); }else{ echo $registros['tarima_fecha']; } ?>"   placeholder="NA"></td>

                        <td><input class="form-control input-sm activo renglon" style="width: 80px" type="text" id="<?php echo 'txtLimParam'.$i?>" name="<?php echo 'txtLimParam'.$i?>" value="<?php if ($registros['tarima_lim_param'] == '0'){ echo ""; }else{ echo $registros['tarima_lim_param']; } ?>"  placeholder="NA"></td>

                        <td>
                          <input class="form-control input-sm activo bloom" style="width: 80px" type="text" id="<?php echo 'txtBloom'.$i?>" name="<?php echo 'txtBloom'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promBloom(<?php echo $i ?>);" value="<?php if ($registros['tarima_bloom'] == '0'){ echo ""; }else{ echo $registros['tarima_bloom']; } ?>" placeholder="NA">
                        </td>

                        <td><input class="form-control input-sm activo viscosidad" style="width: 80px" type="text" id="<?php echo 'txtVisc'.$i?>" name="<?php echo 'txtVisc'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promVis(<?php echo $i ?>);" value="<?php if ($registros['tarima_viscocidad'] == '0'){ echo ""; }else{  echo $registros['tarima_viscocidad']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo ph" style="width: 80px" type="text" id="<?php echo 'txtPhFin'.$i?>" name="<?php echo 'txtPhFin'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promPh(<?php echo $i ?>);"  value="<?php if ($registros['tarima_ph_final'] == '0'){ echo ""; }else{  echo $registros['tarima_ph_final']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo trans" style="width: 80px" type="text" id="<?php echo 'txtTrans'.$i?>" name="<?php echo 'txtTrans'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promTrans(<?php echo $i ?>);"  value="<?php if ($registros['tarima_transparencia'] == '0'){ echo ""; }else{  echo $registros['tarima_transparencia']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo porcentaje" style="width: 80px" type="text" id="<?php echo 'txtPorcenT'.$i?>" name="<?php echo 'txtPorcenT'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promPorc(<?php echo $i ?>);"  value="<?php if ($registros['tarima_porcen_t'] == '0'){ echo ""; }else{  echo $registros['tarima_porcen_t']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo ntu" style="width: 80px" type="text" id="<?php echo 'txtNtu'.$i?>" name="<?php echo 'txtNtu'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promNtu(<?php echo $i ?>);"  value="<?php if ($registros['tarima_ntu'] == '0'){ echo ""; }else{  echo $registros['tarima_ntu']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo humedad" style="width: 80px" type="text" id="<?php echo 'txtHumedad'.$i?>" name="<?php echo 'txtHumedad'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promHum(<?php echo $i ?>);"  value="<?php if ($registros['tarima_humedad'] == '0'){ echo ""; }else{  echo $registros['tarima_humedad']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo cenizas" style="width: 80px" type="text" id="<?php echo 'txtCenizas'.$i?>" name="<?php echo 'txtCenizas'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promCen(<?php echo $i ?>);"  value="<?php if ($registros['tarima_cenizas'] == '0'){ echo ""; }else{  echo $registros['tarima_cenizas']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo redox" style="width: 80px" type="text" id="<?php echo 'txtRedox'.$i?>" name="<?php echo 'txtRedox'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promRed(<?php echo $i ?>);"  value="<?php if ($registros['tarima_redox'] == '0'){ echo ""; }else{  echo $registros['tarima_redox']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo color" style="width: 80px" type="text" id="<?php echo 'txtColor'.$i?>" name="<?php echo 'txtColor'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promCol(<?php echo $i ?>);"  value="<?php if ($registros['tarima_color'] == '0'){ echo ""; }else{  echo $registros['tarima_color']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo grano" style="width: 80px" type="text" id="<?php echo 'txtGrano'.$i?>" name="<?php echo 'txtGrano'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promGran(<?php echo $i ?>);"  value="<?php if ($registros['tarima_grano'] == '0'){ echo ""; }else{  echo $registros['tarima_grano']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo olor" style="width: 80px" type="text" id="<?php echo 'txtOlor'.$i?>" name="<?php echo 'txtOlor'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promOlor(<?php echo $i ?>);"  value="<?php if ($registros['tarima_olor'] == '0'){ echo ""; }else{  echo $registros['tarima_olor']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo parE" style="width: 80px" type="text" id="<?php echo 'txtPartExt'.$i?>" name="<?php echo 'txtPartExt'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promParE(<?php echo $i ?>);"  value="<?php if ($registros['tarima_part_ext'] == '0'){ echo ""; }else{  echo $registros['tarima_part_ext']; } ?>" placeholder="NA"></td>

                        <td><input class="form-control input-sm activo parI" style="width: 80px" type="text" id="<?php echo 'txtPartInd'.$i?>" name="<?php echo 'txtPartInd'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promParI(<?php echo $i ?>);"  value="<?php if ($registros['tarima_part_ind'] == '0'){ echo ""; }else{  echo $registros['tarima_part_ind']; } ?>" placeholder="NA"></td>
                        <td>
                          <select class="form-control input-sm activo hidratacion" style="width: 80px" id="<?php echo 'txtHidratacion'.$i ?>" name="<?php echo 'txtHidratacion'.$i ?>" value="">
                            <option value="N/A">N/A</option>
                            <option value="MAL">MAL</option>
                            <option value="BIEN">BIEN</option>
                            <option value="REG">REG</option>

                            <?php 
                            $cad_hidratacion = mysqli_query($cnx,"SELECT tarima_id,tarima_hidratacion from tarimas WHERE tarima_id = '".$registros['tarima_id']."' ");
                            while($reg_hid =  mysqli_fetch_assoc($cad_hidratacion)) {
                             ?>
                             <option value="<?php echo mb_convert_encoding($reg_hid['tarima_hidratacion'], "UTF-8");  ?>" 
                              <?php if(mb_convert_encoding($reg_hid['tarima_id'], "UTF-8") == $registros['tarima_id']){ ?>selected="selected"<?php }?>><?php echo mb_convert_encoding($reg_hid['tarima_hidratacion'], "UTF-8");  ?></option>
                            <?php }?>

                          </select>
                        </td>
                        <td>
                          <select class="form-control input-sm activo aceptado" style="width: 70px" id="<?php echo 'txtAcepRech'.$i?>" name="<?php echo 'txtAcepRech'.$i?>" value="">
                            <option value="N/A">N/A</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                            <?php 
                            $cad_aceptado = mysqli_query($cnx,"SELECT tarima_id,tarima_aceptado from tarimas WHERE tarima_id = '".$registros['tarima_id']."' ");
                            while($reg_aceptado =  mysqli_fetch_assoc($cad_aceptado)) {
                             ?>
                             <option value="<?php echo mb_convert_encoding($reg_aceptado['tarima_aceptado'], "UTF-8");  ?>" 
                              <?php if(mb_convert_encoding($reg_aceptado['tarima_id'], "UTF-8") == $registros['tarima_id']){ ?>selected="selected"<?php }?>><?php echo mb_convert_encoding($reg_aceptado['tarima_aceptado'], "UTF-8");  ?></option>
                            <?php }?>
                          </select>
                        </td>
                      </tr>

                      <?php 
                      $i += 1;
                    }while ($registros =  mysqli_fetch_array($cadena)); 
                    ?>


                    <?php 
                    $cont1=$i;
                    ?>
                    <input type="text" id="contador" value="<?php echo $cont1 ?>">
                  </tbody>
                  <tfoot>
                         <tr>
                          <td></td>
                      <td>
                        <input type="hidden" id="contadorE">
                      </td>
                      <td valign="center">Promedio:</td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="limite"  name="limite" placeholder="---" value=""></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="bloom"  name="bloom" value="<?php echo number_format(($reg_lot['lote_bloom']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="viscosidad"  name="viscosidad" value="<?php echo number_format(($reg_lot['lote_viscocidad']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="phfinal"  name="phfinal" value="<?php echo number_format(($reg_lot['lote_ph_final']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="transparencia"  name="transparencia" value="<?php echo number_format(($reg_lot['lote_transparencia']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="porcentaje"  name="porcentaje" value="<?php echo number_format(($reg_lot['lote_porcen_t']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="ntu"  name="ntu" value="<?php echo number_format(($registro['lote_bloom']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="humedad"  name="humedad" value="<?php echo number_format(($reg_lot['lote_humedad']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="cenizas"  name="cenizas" value="<?php echo number_format(($reg_lot['lote_cenizas']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="redox" name="redox" value="<?php echo number_format(($reg_lot['lote_redox']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="color" name="color" value="<?php echo number_format(($reg_lot['lote_color']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="grano" name="grano" value="<?php echo number_format(($reg_lot['lote_grano']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="olor"  name="olor" value="" placeholder="---"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="partE" name="partE" value="<?php echo number_format(($reg_lot['lote_part_ext']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="partI" name="partI" value="<?php echo number_format(($reg_lot['lote_part_ind']), 2);  ?>"></td>
                      <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" value="N/A" type="text" id="hidratacion" name="hidratacion"></td>
                      <td><input class="form-control input-sm" style="width: 70px;margin-top: -7px;" readonly="" value="N/A" type="text" id="rechazado" name="rechazado"></td>
                    </tr>
                  </tfoot>
                </table>
              </center>

            </div>