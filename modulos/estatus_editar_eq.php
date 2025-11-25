 <?php
  /*Desarrollado por: CCA Consultores TI */
  /*Contacto: contacto@ccaconsultoresti.com */
  /*Actualizado: Mayo-2024*/
  include "../conexion/conexion.php";
  include "../funciones/funciones.php";
  $cnx =  Conectarse();

  $cadena = mysqli_query($cnx, "SELECT e.*, l.le_estatus, t.ban_almacena
			FROM equipos_preparacion as e 
			INNER JOIN listado_estatus as l on (e.le_id = l.le_id) 
			INNER JOIN equipos_tipos as t on (e.ep_tipo = t.et_tipo )
			WHERE e.ep_id = '" . $_POST['hdd_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
  $registros = mysqli_fetch_assoc($cadena);

  ?>
 <script>
   $(document).ready(function() {
     $("#formEtapasEditar").submit(function() {
       //alert('editar');
       var formData = $(this).serialize();
       $.ajax({
         url: "estatus_actualizar_eq.php",
         type: 'POST',
         data: formData,
         success: function(result) {
           data = JSON.parse(result);
           //alert("Guardo el registro");
           alertas("#alerta-errorEtapaEditar", 'Listo!', data["mensaje"], 1, true,
             5000);
           //$('#form').each (function(){this.reset();});  
         }
       });
       return false;
     });
   });

   function campo_ot() {
     var estatus = document.getElementById('cbxEstatus').value;
     //reparación
     if (estatus == '13') {
       document.getElementById('txt_ot').setAttribute('required', 'true');
     } else {
       document.getElementById('txt_ot').removeAttribute('required');
     }
   }
 </script>


 <div class="modal-dialog modal-md" role="document" style="height: 200px">
   <div class="modal-content">
     <form id="formEtapasEditar">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar estatus equipo <?php echo $registros['ep_descripcion'] ?></h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>

       <div class="modal-body">
         <div class="row">
           <div class="col-md-5">
             <label for="recipient-name" class="col-form-label">Estatus Actual:</label>
             <select onchange="campo_ot()" name="cbxEstatus" class="form-control is-valid" id="cbxEstatusAct" placeholder="" disabled>
               <?php
                /* $cad_est = mysqli_query($cnx, "select * from listado_estatus WHERE le_aplica = '' and le_id <> 10 and le_id <> 11 order by le_estatus asc"); */
                /*  $cad_est = mysqli_query($cnx, "select * from listado_estatus WHERE le_aplica = '' and le_id <> 10 order by le_estatus asc"); */
                $cad_est = mysqli_query($cnx, "select * from listado_estatus WHERE le_aplica = '' order by le_estatus asc");
                while ($reg_est =  mysqli_fetch_assoc($cad_est)) {
                ?>
                 <option value="<?php echo mb_convert_encoding($reg_est['le_id'], "UTF-8");  ?>" <?php if (mb_convert_encoding($reg_est['le_id'], "UTF-8") == $registros['le_id']) { ?>selected="selected" <?php } ?>><?php echo mb_convert_encoding($reg_est['le_estatus'], "UTF-8");  ?>
                 </option>
               <?php } ?>
             </select>
             <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $_POST['hdd_id'] ?>" />
             <input name="hdd_est" type="hidden" id="hdd_est" value="<?php echo $registros['le_id'] ?>" />
           </div>
           <div class="col-md-4">
             <?php
              $cad_pro = mysqli_query($cnx, "SELECT p.pro_id   
                            FROM procesos as p
                            inner join procesos_equipos as e ON(p.pro_id = e.pro_id)
                            WHERE e.ep_id = '" . $_POST['hdd_id'] . "' AND p.pro_estatus = 1 and e.pe_ban_activo = 1 ");
              $reg_pro = mysqli_fetch_array($cad_pro);
              ?>
             <label for="recipient-name" class="col-form-label">Proceso:</label>
             <input type="text" class="form-control" name="txt_pro" id="txt_pro" placeholder="Proceso" value="<?php echo $reg_pro['pro_id'] ?>" disabled>
           </div>
         </div>
         <div class="row">
           <div class="col-md-5">
             <label for="recipient-name" class="col-form-label">Estatus:</label>
             <select onchange="campo_ot()" name="cbxEstatus" class="form-control is-valid" id="cbxEstatus" placeholder="" required>
               <option value="">Selecciona...</option>
               <?php
                // Mostrar solo los estatus indicados
                $str_html = "";

                if ($registros['le_id'] == 9) {
                  $str_html .= "<option value='12'>Descompuesto</option>";
                }


                if ($registros['le_id'] == 12) {
                  $str_html .= "<option value='13'>Reparación</option>";
                }


                #si encuentra datos en inventario pelambre
                if ($registros['le_id'] == 13 and $reg_pro['pro_id'] == '') {
                  $str_html .= "<option value='9'>Libre</option>";
                }
                if ($registros['le_id'] == 13 and $reg_pro['pro_id'] != '') {
                  $str_html .= "<option value='11'>Ocupado</option>";
                }


                if (($registros['le_id'] == 11 || $registros['le_id'] == 10) and $registros['ban_almacena'] == 'N') {
                  $str_html .= "<option value='12'>Descompuesto</option>";
                }

                if ($registros['le_id'] == 11 and $registros['ban_almacena'] == 'S') {
                  $str_html .= "<option value='9'>Libre</option>";
                }

                echo $str_html;
                ?>
             </select>

           </div>
           <div class="col-md-4">
             <label for="recipient-name" class="col-form-label">Orden de trabajo:</label>

             <input type="text" class="form-control" name="txt_ot" id="txt_ot" placeholder="Orden de trabajo">
           </div>
         </div>
         <div class="row">
           <div class="col-md-12">
             <label for="recipient-name" class="col-form-label">Comentarios:</label>
             <textarea name="txaComentarios" cols="75" rows="3" ic="txaComentarios" required></textarea>
           </div>
         </div>
         <div class="modal-footer" style="margin-top: 8%;">
           <!--mensajes-->
           <div class="alert alert-info hide" id="alerta-errorEtapaEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
             <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
             <strong>Titulo</strong> &nbsp;&nbsp;
             <span> Mensaje </span>
           </div>
           <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
           <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt="">
             Guardar</button>
         </div>
       </div>
     </form>

   </div>
 </div>