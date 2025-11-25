<?php
/*Desarrollado por: Ca & Ce Technologies */
/*22 - Julio - 2021*/
require_once('../conexion/conexion.php');
include "../seguridad/user_seguridad.php";
include "../funciones/funciones.php";
$cnx = Conectarse();

//Selección de etapas a monitorear por parametros
$cad_etapas = mysqli_query($cnx, "select pe_id, pep_descripcion, pep_tipo, pep_inicio, pep_fin from preparacion_etapas_param where pep_id = ".$_POST['pep_id']." ORDER BY pep_tipo") or die(mysql_error()."Error: en consultar las etapas");
$reg_etapas = mysqli_fetch_assoc($cad_etapas);

//Consulta parametros de liberación
echo "select ".fnc_tipo_campo($reg_etapas['pep_tipo'])." as dato, pro_id, usu_id, prol_fecha from procesos_liberacion where pe_id = '$reg_etapas[pe_id]' ";

$cad_parametros = mysqli_query($cnx, "select ".fnc_tipo_campo($reg_etapas['pep_tipo'])." as dato, pro_id, usu_id, prol_fecha from procesos_liberacion where pe_id = '$reg_etapas[pe_id]' ") 
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
				<th scope="col">Fecha</th>
              </tr>
            </thead>
            <tbody>
			<?php 
			$cont = 1;
			do{ 
			
			
			$cad_usu = mysqli_query($cnx, "SELECT usu_nombre FROM usuarios WHERE usu_id = '$reg_parametros[usu_id]' ") or die(mysql_error()."Error: en consultar los paletos");
			$reg_usu = mysqli_fetch_assoc($cad_usu);
			?>
              <tr <?php 
			  
			 //$dato = $reg_parametros[fnc_tipo_campo($reg_etapas['pep_tipo'])];
			 $dato = $reg_parametros['dato'];
			  
			  if($dato < $reg_etapas['pep_inicio'] or $dato > $reg_etapas['pep_fin'] )
			  {
				  if($dato == 0.00 and $reg_etapas['pep_tipo'] != 'ppm')
				  {
				  	echo "style='background:#ffffff'";
				  }
				  else
				  {
					echo "style='background:#FC8C8C; color:#fff'";  
				  }
			  }
			 else
			  {
			  	echo "style='background:#ffffff'";
			  } ?>>
                <th scope="row"><?php echo $cont;?></th>
                <td><?php echo $reg_parametros['pro_id']?></td>
                <td><?php echo $reg_usu['usu_nombre']?></td>
                <td align="center"><?php echo $dato;?></td>
				<td><?php echo $reg_parametros['prol_fecha']?></td>
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
