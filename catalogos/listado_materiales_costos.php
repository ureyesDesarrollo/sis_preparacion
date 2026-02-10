<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_GET);

$cadena = mysqli_query($cnx, "SELECT m.mc_costo,ma.mat_nombre, p.prv_nombre,m.mc_fe_alta, m.mc_year 
FROM materiales_costos as m
inner join materiales as ma on(m.mat_id = ma.mat_id)
inner join proveedores as p on(m.prv_id = p.prv_id) 
WHERE m.mat_id ='$hdd_id'") or die(mysqli_error($cnx) . "Error: en consultar");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

?>

<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <td>Costo</td>
                <td>Material</td>
                <td>Proveedor</td>
                <td>Fecha Alta</td>
                <td>Año</td>
            </tr>
        </thead>
        <tbody>
            <?php

            do { ?>
                <tr>
                    <td><?php echo $registros['mc_costo'] ?></td>
                    <td><?php echo $registros['mat_nombre'] ?></td>
                    <td><?php echo $registros['prv_nombre'] ?></td>
                    <td><?php echo $registros['mc_fe_alta'] ?></td>
                    <td><?php echo $registros['mc_year'] ?></td>

                </tr>
            <?php } while ($registros = mysqli_fetch_assoc($cadena));
            ?>

        </tbody>
    </table>
</div>