<?php
/*Desarrollado por: Ca & Ce Technologies */
/*22 - Julio - 2021*/
require_once('../conexion/conexion.php');
include "../seguridad/user_seguridad.php";
include "../funciones/funciones.php";
$cnx = Conectarse();

//Selección de etapas a monitorear por parametros
$cad_etapas = mysqli_query($cnx, "select pe_id, pep_descripcion, pep_tipo, pep_inicio, pep_fin, pep_tabla, pep_columna, pep_tabla_p, pep_columna_p from preparacion_etapas_param where pep_id = ".$_POST['pep_id']." ORDER BY pep_tipo") or die(mysql_error()."Error: en consultar las etapas");
$reg_etapas = mysqli_fetch_assoc($cad_etapas);

//Selección de los materiales de la mezcla
$cad_materiales = mysqli_query($cnx, "select m.* 
								from preparacion_etapas_mezclas as p
								inner join mezclas_materiales as x on (p.mez_id = x.mez_id)
								inner join materiales as m on (x.mat_id = m.mat_id)
								where p.pep_id = ".$_POST['pep_id']." and x.mez_id = ".$_POST['mez_id']) or die(mysql_error()."Error: en consultar los materiales");
$reg_materiales = mysqli_fetch_assoc($cad_materiales);


//Consulta parametros del renglon

$cad_parametros = mysqli_query($cnx, "select ".$reg_etapas['pep_columna']." as valor, pro_id, d.usu_id from ".$reg_etapas['pep_tabla']." as d 
							inner join ".$reg_etapas['pep_tabla_p']." as g on (g.".$reg_etapas['pep_columna_p']." = d.".$reg_etapas['pep_columna_p'].")
							where ".$reg_etapas['pep_columna']." < '$reg_etapas[pep_inicio]' or ".$reg_etapas['pep_columna']." > '$reg_etapas[pep_fin]' ") 
							or die(mysql_error()."Error: en consultar el proces");
$reg_parametros = mysqli_fetch_assoc($cad_parametros);
?>

<div class="modal-dialog modal-full-height modal-right modal-notify modal-primary modal-lg" role="document">
  <div class="modal-content">
    <!--Header-->
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial</h5>
		<h4>
		Etapa <input type="text" value="<?php echo $reg_etapas['pep_descripcion']?>" readonly="True" size="12">
		Tipo <input type="text" value="<?php echo $reg_etapas['pep_tipo']?>" readonly="True" size="5">
		Parametro inicial <input type="text" value="<?php echo $reg_etapas['pep_inicio']?>" readonly="True" size="5">
		Parametro final <input type="text" value="<?php echo $reg_etapas['pep_fin']?>" readonly="True" size="5">
		</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		<h4>Materiales: <?php $str_mat = ''; do { $str_mat .= $reg_materiales['mat_nombre']." ";}while($reg_materiales = mysqli_fetch_assoc($cad_materiales)); echo $str_mat;?></h4>
      </div>
    <!--Body-->
    <div class="modal-body">
      <div class="text-center"  action="#!">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Proceso</th>
                <th scope="col">Usuario</th>
                <th scope="col">Valor</th>
				<th scope="col">Material</th>
              </tr>
            </thead>
            <tbody>
			<?php 
			$cont = 1;
			do{ 
			
			
			$cad_usu = mysqli_query($cnx, "SELECT usu_nombre FROM usuarios WHERE usu_id = '$reg_parametros[usu_id]' ") or die(mysql_error()."Error: en consultar los paletos");
			$reg_usu = mysqli_fetch_assoc($cad_usu);
			
			$cad_mat_pro = mysqli_query($cnx, "SELECT DISTINCT x.mat_id, m.mat_nombre 
									FROM procesos_materiales as x
									inner join materiales as m on (x.mat_id = m.mat_id)
									WHERE pro_id = '$reg_parametros[pro_id]' 
									ORDER BY mat_id ") or die(mysql_error()."Error: en consultar los procesos");
		  $reg_mat_pro = mysqli_fetch_assoc($cad_mat_pro);
			?>
              <tr <?php 
			  
			  /*$dato = $reg_parametros[fnc_tipo_campo($reg_etapas['pep_tipo'])];*/
			  
			  if($reg_parametros['valor'] < $reg_etapas['pep_inicio'] or $reg_parametros['valor'] > $reg_etapas['pep_fin'])// and trim($str_mat) == $reg_mat_pro['mat_nombre']
			  {
				  if($reg_parametros['valor'] == 0.00 and $reg_etapas['pep_tipo'] != 'ppm')
				  {
					  echo "style='background:#ffffff'"; $val = "aqui";
				  }
				  else
				  {
					if( trim($str_mat) == trim($reg_mat_pro['mat_nombre']))
					{
						echo "style='background:#FC8C8C; color:#fff'"; $val = "este";
					}
					else{
						echo "style='background:#ffffff'"; $val = "aca".trim($mat).$reg_mat_pro['mat_nombre'];
					}
				  }
				  
			  }
			 else
			  {
					  echo "style='background:#ffffff'"; $val = "alla";
			 } ?>>
                <th scope="row"><?php echo $cont; //echo $val;?></th>
                <td><?php echo $reg_parametros['pro_id']?></td>
                <td><?php echo $reg_usu['usu_nombre']?></td>
                <td align="center"><?php echo $reg_parametros['valor']?></td>
				<td><?php echo $reg_mat_pro['mat_nombre']?></td>
              </tr>
			<?php 
			$cont += 1;
			}while($reg_parametros = mysqli_fetch_assoc($cad_parametros));?>
            </tbody>
          </table>
        </div>
      </div>
      <!--Footer-->
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary">Guardar</button>-->
      </div>
  </div>
</div>
