<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();

$cad_tipo_eq = mysqli_query($cnx, "SELECT et_descripcion from equipos_tipos order by et_descripcion");
$reg_tipo_eq =  mysqli_fetch_assoc($cad_tipo_eq);

?>
<div class="container" id="tipos_equipos" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
    <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
        <thead>
            <tr>
                <th>Clave</th>
                <th>Descripción</th>
                <th>Sigla</th>
                <th>Orden</th>
                <th>Estatus</th>
                <th>Indicador</th>
                <th>Imagen</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ren = 1;
            do {
                if (isset($reg_tipo_eq['ep_tipo'])) {

            ?>
                    <tr>
                        <td><?php echo $reg_tipo_eq['et_id'] ?></td>
                        <td><?php echo $reg_tipo_eq['et_descripcion'] ?></td>
                        <td><?php echo $reg_tipo_eq['et_tipo'] ?></td>
                        <td><?php echo $reg_tipo_eq['et_orden'] ?></td>
                        <td><?php echo $reg_tipo_eq['et_estatus'] ?></td>
                        <td><?php echo $reg_estatus['et_imagen'] ?></td>
                        <td><?php if ($reg_tipo_eq['ban_almacena'] == 'S') {
                                echo "Almacen";
                            } else {
                                echo "";
                            } ?></td>
                        <td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_editar') == 1) { ?><a href="#" onClick="javascript:fnc_editar(<?= $reg_tipo_eq['ep_id']; ?>)" alt="Editar"><i class="fa-regular fa-pen-to-square"></i><?php } ?></td>
                    </tr>
            <?php
                    $ren += 1;
                }
            } while ($reg_tipo_eq =  mysqli_fetch_assoc($cad_tipo_eq)); ?>

        </tbody>

        <tfoot>
            <!--   <?php for ($i = $ren; $i <= 12; $i++) { ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
 -->
        </tfoot>
    </table>
</div>