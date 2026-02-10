<?php
/*Desarrollado por: Ca & Ce Technologies */
/*21 - Abril - 2024*/
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx = Conectarse();
extract($_POST);

/* total solicitado */
$tot_solicitado = mysqli_query($cnx, "SELECT i.inv_id, i.inv_no_ticket, i.inv_folio_interno, i.inv_kg_totales, m.mat_id, m.mat_nombre, i.ac_id
FROM inventario as i
INNER JOIN materiales as m ON (i.mat_id = m.mat_id)
WHERE i.inv_solicitado = 'S' AND i.inv_tomado = 0 
ORDER BY m.mat_nombre ASC") or die(mysqli_error($cnx) . "Error: en consultar inventario");

if (mysqli_num_rows($tot_solicitado) > 0) {
    $reg_tot_solicitado = mysqli_fetch_assoc($tot_solicitado);
} else {
    $reg_tot_solicitado = null;
}
?>
<style>
    .alert {
        padding: 0.5rem;
        margin-bottom: 0px;
    }
</style>

<div class="modal-dialog modal-lg">
    <form id="form_cajones">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Material solicitado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Folio</th>
                                <th>Kilos</th>
                                <th>Material</th>
                                <th>Cajón</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tot = 0;
                            if ($reg_tot_solicitado) {
                                do {
                                    if (!is_null($reg_tot_solicitado['ac_id'])) {
                                        $cajon = mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_id = " . $reg_tot_solicitado['ac_id']);
                                        if (mysqli_num_rows($cajon) > 0) {
                                            $reg_caj = mysqli_fetch_assoc($cajon);
                                            $ac_descripcion = $reg_caj['ac_descripcion'] ?? 'Sin descripción';
                                        } else {
                                            $ac_descripcion = 'Sin descripción';
                                        }
                                    } else {
                                        $ac_descripcion = 'Sin descripción';
                                    }
                            ?>
                                    <tr>
                                        <td><?php echo $reg_tot_solicitado['inv_no_ticket'] ?></td>
                                        <td><?php echo $reg_tot_solicitado['inv_folio_interno'] ?></td>
                                        <td><?php echo number_format($reg_tot_solicitado['inv_kg_totales']) ?></td>
                                        <td><?php echo $reg_tot_solicitado['mat_nombre'] ?></td>
                                        <td><?php echo $ac_descripcion ?></td>
                                    </tr>
                            <?php
                                    $tot += $reg_tot_solicitado['inv_kg_totales'];
                                } while ($reg_tot_solicitado = mysqli_fetch_assoc($tot_solicitado));
                            } else {
                                echo "<tr><td colspan='5'>No hay materiales solicitados.</td></tr>";
                            }
                            ?>
                            <tr style="font-weight: bold;">
                                <td></td>
                                <td>Total</td>
                                <td><?php echo number_format($tot) ?></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.reload()">
                    <i class="fa-solid fa-rectangle-xmark"></i> Cerrar
                </button>
            </div>
    </form>
</div>