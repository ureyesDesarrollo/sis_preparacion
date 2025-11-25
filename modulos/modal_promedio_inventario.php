<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
$cnx =  Conectarse();
if (isset($_POST['action']) && $_POST['action'] == 'obtener_promedio') {
    $ac_id = $_POST['ac_id'];
    $query = "SELECT inv_folio_interno,inv_alcalinidad, inv_calcios,inv_humedad, inv_ce, inv_extrac FROM inventario WHERE ac_id = $ac_id AND inv_tomado = 0";

    $resultado = mysqli_query($cnx, $query);
    $total = mysqli_num_rows($resultado);
    $datosInvetario = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $datosInvetario[] = $fila;
    }

    echo json_encode(["datos" => $datosInvetario, "total" => $total]);
    exit();
}
?>
<div class="modal-dialog modal-lg">
    <form id="form_cajones">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Promedio inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="text" class="d-none" id="ac_id" value="<?= $_POST['ac_id'] ?>">
            <div class=" modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Extractibilidad</th>
                                <th>Alcalinidad</th>
                                <th>Calcios</th>
                                <th>Humedad</th>
                                <th>Ce</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-inventario">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Promedio</th>
                                <th id="promedio-extrac"></th>
                                <th id="promedio-alcalinidad"></th>
                                <th id="promedio-calcios"></th>
                                <th id="promedio-humedad"></th>
                                <th id="promedio-ce"></th>
                            </tr>
                        </tfoot>
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
<script>
    $(document).ready(function() {
        const ac_id = $('#ac_id').val();
        $.ajax({
            url: 'modal_promedio_inventario.php',
            type: 'POST',
            data: {
                action: 'obtener_promedio',
                ac_id: ac_id
            },
            success: function(response) {
                let res;
                try {
                    res = JSON.parse(response);
                } catch (e) {
                    console.error("Error al parsear JSON:", e);
                    return;
                }

                console.log(res);

                let datos = res.datos;
                let totalValidos = 0;

                let sumExtrac = 0,
                    sumAlcalinidad = 0,
                    sumCalcios = 0,
                    sumHumedad = 0,
                    sumCe = 0;

                datos.forEach(function(item) {
                    if (item.inv_extrac !== null || item.inv_alcalinidad !== null || item.inv_calcios !== null || item.inv_humedad !== null || item.inv_ce !== null) {
                        totalValidos++;

                        let extrac = item.inv_extrac !== null ? parseFloat(item.inv_extrac) : 0;
                        let alcalinidad = item.inv_alcalinidad !== null ? parseFloat(item.inv_alcalinidad) : 0;
                        let calcios = item.inv_calcios !== null ? parseFloat(item.inv_calcios) : 0;
                        let humedad = item.inv_humedad !== null ? parseFloat(item.inv_humedad) : 0;
                        let ce = item.inv_ce !== null ? parseFloat(item.inv_ce) : 0;

                        $('#tabla-inventario').append(`
                        <tr>
                            <td>${item.inv_folio_interno}</td>
                            <td>${extrac !== 0 ? extrac : ''}</td>
                            <td>${alcalinidad !== 0 ? alcalinidad : ''}</td>
                            <td>${calcios !== 0 ? calcios : ''}</td>
                            <td>${humedad !== 0 ? humedad : ''}</td>
                            <td>${ce !== 0 ? ce : ''}</td>
                        </tr>
                    `);

                        sumExtrac += extrac;
                        sumAlcalinidad += alcalinidad;
                        sumCalcios += calcios;
                        sumHumedad += humedad;
                        sumCe += ce;
                    }
                });

                // Calcular promedios solo con los valores válidos
                let promedioExtrac = totalValidos > 0 ? (sumExtrac / totalValidos).toFixed(2) : '';
                let promedioAlcalinidad = totalValidos > 0 ? (sumAlcalinidad / totalValidos).toFixed(2) : '';
                let promedioCalcios = totalValidos > 0 ? (sumCalcios / totalValidos).toFixed(2) : '';
                let promedioHumedad = totalValidos > 0 ? (sumHumedad / totalValidos).toFixed(2) : '';
                let promedioCe = totalValidos > 0 ? (sumCe / totalValidos).toFixed(2) : '';

                // Mostrar los promedios en el footer
                $('#promedio-extrac').text(promedioExtrac);
                $('#promedio-alcalinidad').text(promedioAlcalinidad);
                $('#promedio-calcios').text(promedioCalcios);
                $('#promedio-humedad').text(promedioHumedad);
                $('#promedio-ce').text(promedioCe);
            }
        });
    });
</script>