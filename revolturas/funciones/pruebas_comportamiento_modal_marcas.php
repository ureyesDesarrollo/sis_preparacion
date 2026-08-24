<?php

include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

$reporte_id = isset($_POST['reporte_id']) ? (int)$_POST['reporte_id'] : 0;

if ($reporte_id <= 0) {
    echo '<div class="modal-dialog"><div class="modal-content"><div class="modal-body">Reporte inválido</div></div></div>';
    exit;
}

$sql_reporte = "SELECT reporte_id, titulo, fecha
                FROM lab_reportes
                WHERE reporte_id = $reporte_id
                LIMIT 1";

$res_reporte = mysqli_query($cnx, $sql_reporte);
$reporte = mysqli_fetch_assoc($res_reporte);

if (!$reporte) {
    echo '<div class="modal-dialog"><div class="modal-content"><div class="modal-body">Reporte no encontrado</div></div></div>';
    exit;
}

$sql_muestras = "SELECT
                    rm.reporte_muestra_id,
                    rm.nombre_muestra,
                    rm.orden,
                    rm.marca_id,
                    rm.marca_estatus,
                    ma.nombre AS marca_nombre
                 FROM lab_reporte_muestras rm
                 LEFT JOIN lab_marcas ma
                    ON rm.marca_id = ma.marca_id
                 WHERE rm.reporte_id = $reporte_id
                 ORDER BY rm.orden ASC";

$res_muestras = mysqli_query($cnx, $sql_muestras);

$muestras = array();

while ($fila = mysqli_fetch_assoc($res_muestras)) {
    $muestras[] = $fila;
}

$sql_marcas = "SELECT marca_id, nombre
               FROM lab_marcas
               WHERE activo = 1
               ORDER BY nombre ASC";

$res_marcas = mysqli_query($cnx, $sql_marcas);

$marcas = array();

while ($fila = mysqli_fetch_assoc($res_marcas)) {
    $marcas[] = $fila;
}
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                Asignar marcas - <?php echo htmlspecialchars($reporte['titulo']); ?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <input type="hidden" id="marca_reporte_id" value="<?php echo (int)$reporte_id; ?>">

            <div class="alert alert-info">
                Asigna la marca correspondiente a cada muestra. Esto no modifica los resultados capturados.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Muestra</th>
                            <th>Marca</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($muestras as $muestra) { ?>
                            <tr class="fila-marca" data-reporte-muestra-id="<?php echo (int)$muestra['reporte_muestra_id']; ?>">
                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($muestra['nombre_muestra']); ?>
                                    </strong>
                                </td>

                                <td>
                                    <select class="form-control form-control-sm marca-id">
                                        <option value="">Seleccionar marca</option>

                                        <?php foreach ($marcas as $marca) { ?>
                                            <option value="<?php echo (int)$marca['marca_id']; ?>"
                                                <?php echo ((int)$muestra['marca_id'] === (int)$marca['marca_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($marca['nombre']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>

                                <td>
                                    <?php if ($muestra['marca_estatus'] == 'ASIGNADA') { ?>
                                        <span class="badge bg-success">ASIGNADA</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">PENDIENTE</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Cerrar
            </button>

            <button type="button" class="btn btn-primary" id="btnGuardarMarcasPrueba">
                <i class="fa fa-save"></i> Guardar marcas
            </button>
        </div>
    </div>
</div>

<script>
    $('#btnGuardarMarcasPrueba').on('click', function() {
        let datos = {
            reporte_id: $('#marca_reporte_id').val(),
            marcas: []
        };

        $('.fila-marca').each(function() {
            let fila = $(this);
            let reporte_muestra_id = fila.data('reporte-muestra-id');
            let marca_id = fila.find('.marca-id').val();

            datos.marcas.push({
                reporte_muestra_id: reporte_muestra_id,
                marca_id: marca_id
            });
        });

        $.ajax({
            type: 'POST',
            url: 'funciones/pruebas_comportamiento_guardar_marcas.php',
            data: JSON.stringify(datos),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            beforeSend: function() {
                $('#btnGuardarMarcasPrueba').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(resp) {
                if (resp.success) {
                    alert(resp.message);

                    $('#modal_pruebas_comportamiento_marcas').modal('hide');

                    if ($.fn.DataTable.isDataTable('#dataTablePruebasComportamiento')) {
                        $('#dataTablePruebasComportamiento').DataTable().ajax.reload(null, false);
                    }
                } else {
                    alert(resp.message || 'Error al guardar marcas');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Error de servidor al guardar marcas');
            },
            complete: function() {
                $('#btnGuardarMarcasPrueba').prop('disabled', false).html('<i class="fa fa-save"></i> Guardar marcas');
            }
        });
    });
</script>