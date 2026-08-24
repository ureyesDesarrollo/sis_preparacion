<?php
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

$sql_parametros = "SELECT 
                        parametro_id,
                        clave,
                        nombre,
                        unidad,
                        limite_texto_default,
                        valor_min_default,
                        valor_max_default,
                        decimales,
                        orden
                   FROM lab_parametros
                   WHERE activo = 1
                   ORDER BY orden ASC";

$res_parametros = mysqli_query($cnx, $sql_parametros);

$parametros = array();

while ($fila = mysqli_fetch_assoc($res_parametros)) {
    $parametros[] = $fila;
}

$total_parametros = count($parametros);
?>

<div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
        <div class="modal-header py-2">
            <h5 class="modal-title" style="font-size: 1rem;">Nueva prueba de comportamiento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body" style="padding: 0.5rem; display: flex; flex-direction: column; height: calc(100vh - 120px);">
            <form id="formPruebaComportamiento" style="display: flex; flex-direction: column; height: 100%;">
                <!-- Campos principales compactos -->
                <div class="row g-1 mb-2">
                    <div class="col-md-4">
                        <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.1rem;">Título</label>
                        <?php
                        $meses = [
                            1 => 'enero',
                            2 => 'febrero',
                            3 => 'marzo',
                            4 => 'abril',
                            5 => 'mayo',
                            6 => 'junio',
                            7 => 'julio',
                            8 => 'agosto',
                            9 => 'septiembre',
                            10 => 'octubre',
                            11 => 'noviembre',
                            12 => 'diciembre'
                        ];

                        $mes = $meses[(int) date('n')];
                        ?>

                        <input
                            type="text"
                            class="form-control form-control-sm"
                            id="titulo"
                            name="titulo"
                            value="PRUEBA MUESTRAS <?= date('d') ?> <?= strtoupper($mes) ?> <?= date('Y') ?>"
                            style="font-size: 0.8rem; height: 28px;">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.1rem;">Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" style="font-size: 0.8rem; height: 28px;">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.1rem;">Observaciones</label>
                        <input type="text" class="form-control form-control-sm" id="observaciones" name="observaciones" style="font-size: 0.8rem; height: 28px;">
                    </div>
                </div>

                <div class="card mb-0" style="flex: 1; display: flex; flex-direction: column; min-height: 0;">
                    <div class="card-header py-1 d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; flex-shrink: 0;">
                        <strong style="font-size: 0.8rem;">Resultados por muestra</strong>
                        <button type="button" class="btn btn-success btn-sm py-0" id="btnAgregarFilaMuestra" style="font-size: 0.75rem; height: 26px;">
                            <i class="fa fa-plus"></i> Agregar muestra
                        </button>
                    </div>

                    <div class="card-body p-1" style="flex: 1; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                        <div class="alert alert-info py-1 px-2 mb-1" style="font-size: 0.7rem; flex-shrink: 0;">
                            <i class="fa fa-info-circle"></i> Los límites se toman automáticamente de la configuración de parámetros.
                        </div>

                        <!-- Contenedor con scroll horizontal y vertical -->
                        <div style="flex: 1; overflow: auto; min-height: 0; position: relative;">
                            <div style="min-width: max-content; height: 100%;">
                                <table class="table table-bordered table-sm align-middle mb-0" id="tablaMuestrasComportamiento" style="font-size: 0.7rem; width: auto; table-layout: auto;">
                                    <thead style="position: sticky; top: 0; z-index: 20; background-color: #e9ecef;">
                                        <tr>
                                            <th rowspan="2" style="min-width:100px;">Muestra</th>
                                            <th rowspan="2" style="min-width:100px;">Marca</th>

                                            <th colspan="<?php echo $total_parametros; ?>" class="text-center" style="padding: 0.15rem 0.3rem; font-size: 0.65rem; background-color: #e9ecef;">
                                                Resultados de laboratorio
                                            </th>

                                            <th colspan="2" class="text-center" style="padding: 0.15rem 0.3rem; font-size: 0.65rem; background-color: #e9ecef;">
                                                Fuera del refrigerador
                                            </th>

                                            <th colspan="2" class="text-center" style="padding: 0.15rem 0.3rem; font-size: 0.65rem; background-color: #e9ecef;">
                                                Refrigeración
                                            </th>

                                            <th rowspan="2" style="min-width:35px; padding: 0.15rem 0.3rem; position: sticky; right: 0; z-index: 30; background-color: #e9ecef;">
                                            </th>
                                        </tr>

                                        <tr>
                                            <?php foreach ($parametros as $parametro) { ?>
                                                <th style="min-width:75px; padding: 0.1rem 0.2rem; font-size: 0.6rem; text-align: center; white-space: nowrap;">
                                                    <?php echo htmlspecialchars($parametro['nombre']); ?>

                                                    <?php if (!empty($parametro['unidad'])) { ?>
                                                        <br><span style="font-size: 0.55rem;"><?php echo htmlspecialchars($parametro['unidad']); ?></span>
                                                    <?php } ?>

                                                    <?php if (!empty($parametro['limite_texto_default'])) { ?>
                                                        <br>
                                                        <span style="font-size: 0.52rem; color: #dc3545; font-weight: 600;">
                                                            <?php echo htmlspecialchars($parametro['limite_texto_default']); ?>
                                                        </span>
                                                    <?php } ?>
                                                </th>
                                            <?php } ?>

                                            <th style="min-width:60px; padding: 0.1rem 0.2rem; font-size: 0.65rem;">Colorante</th>
                                            <th style="min-width:60px; padding: 0.1rem 0.2rem; font-size: 0.65rem;">Natural</th>
                                            <th style="min-width:60px; padding: 0.1rem 0.2rem; font-size: 0.65rem;">Colorante</th>
                                            <th style="min-width:60px; padding: 0.1rem 0.2rem; font-size: 0.65rem;">Natural</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <small class="text-muted" style="font-size: 0.65rem; flex-shrink: 0; margin-top: 2px;">
                            <i class="fa fa-arrows-alt"></i> Desplázate horizontalmente para ver todos los parámetros
                        </small>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer py-1" style="flex-shrink: 0;">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                Cerrar
            </button>
            <button type="button" class="btn btn-primary btn-sm" id="btnGuardarPruebaComportamiento">
                <i class="fa fa-save"></i> Guardar prueba
            </button>
        </div>
    </div>
</div>

<script>
    var parametrosPruebaComportamiento = <?php echo json_encode($parametros); ?>;

    $(document).ready(function() {
        agregarFilaMuestra();

        $('#btnAgregarFilaMuestra').on('click', function() {
            agregarFilaMuestra();
            // Scroll al final de la tabla
            let container = $('.modal-body').find('div[style*="overflow: auto"]');
            container.scrollTop(container[0].scrollHeight);
        });

        $('#tablaMuestrasComportamiento').on('click', '.btnEliminarFilaMuestra', function() {
            let totalFilas = $('#tablaMuestrasComportamiento tbody tr').length;

            if (totalFilas <= 1) {
                alert('Debe existir al menos una fila');
                return;
            }

            $(this).closest('tr').remove();
            renumerarMuestras();
        });

        $('#btnGuardarPruebaComportamiento').on('click', function() {
            guardarPruebaComportamiento();
        });

        // Agregar soporte para tecla Enter
        $(document).on('keydown', '.resultado-parametro, .color-fuera-valor, .sabor-fuera-valor, .color-dentro-valor, .sabor-dentro-valor', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let inputs = $(this).closest('tr').find('input[type="number"]');
                let currentIndex = inputs.index(this);
                if (currentIndex < inputs.length - 1) {
                    inputs.eq(currentIndex + 1).focus();
                } else {
                    // Si es el último input, agregar nueva fila
                    $('#btnAgregarFilaMuestra').click();
                }
            }
        });
    });

    function renumerarMuestras() {
        $('#tablaMuestrasComportamiento tbody tr').each(function(index) {
            $(this).find('.muestra-nombre').val('MUESTRA ' + (index + 1));
        });
    }

    function agregarFilaMuestra() {
        let html = '';

        html += '<tr>';
        let numeroMuestra = $('#tablaMuestrasComportamiento tbody tr').length + 1;
        let nombreMuestra = 'MUESTRA ' + numeroMuestra;

        html += '   <td style="position: sticky; left: 0; z-index: 10; background-color: white;">';
        html += '       <input type="text" class="form-control form-control-sm muestra-nombre" value="' + nombreMuestra + '" readonly style="font-size: 0.7rem; padding: 0.1rem 0.2rem; width: 90px;">';
        html += '   </td>';

        html += '   <td style="position: sticky; left: 100px; z-index: 10; background-color: white;">';
        html += '       <span class="badge bg-warning text-dark">PENDIENTE</span>';
        html += '       <input type="hidden" class="muestra-tipo" value="OTRO">';
        html += '   </td>';

        parametrosPruebaComportamiento.forEach(function(parametro) {
            html += '   <td>';
            html += '       <input type="number" step="0.0001" class="form-control form-control-sm resultado-parametro" data-clave="' + parametro.clave + '" style="font-size: 0.7rem; padding: 0.1rem 0.2rem; width: 55px;">';
            html += '   </td>';
        });

        html += '   <td><input type="number" step="0.0001" class="form-control form-control-sm colorante-fuera-valor" style="font-size: 0.7rem; padding: 0.1rem 0.2rem; width: 55px;"></td>';
        html += '   <td><input type="number" step="0.0001" class="form-control form-control-sm natural-fuera-valor" style="font-size: 0.7rem; padding: 0.1rem 0.2rem; width: 55px;"></td>';
        html += '   <td><input type="number" step="0.0001" class="form-control form-control-sm colorante-dentro-valor" style="font-size: 0.7rem; padding: 0.1rem 0.2rem; width: 55px;"></td>';
        html += '   <td><input type="number" step="0.0001" class="form-control form-control-sm natural-dentro-valor" style="font-size: 0.7rem; padding: 0.1rem 0.2rem; width: 55px;"></td>';

        html += '   <td class="text-center" style="position: sticky; right: 0; z-index: 10; background-color: white; padding: 0.1rem;">';
        html += '       <button type="button" class="btn btn-danger btn-sm btnEliminarFilaMuestra" style="padding: 0.05rem 0.2rem; font-size: 0.6rem;">';
        html += '           <i class="fa fa-trash"></i>';
        html += '       </button>';
        html += '   </td>';
        html += '</tr>';

        $('#tablaMuestrasComportamiento tbody').append(html);
    }

    function obtenerDatosPruebaComportamiento() {
        let datos = {
            titulo: $('#titulo').val(),
            fecha: $('#fecha').val(),
            observaciones: $('#observaciones').val(),
            muestras: []
        };

        $('#tablaMuestrasComportamiento tbody tr').each(function(index) {
            let fila = $(this);
            let nombre = $.trim(fila.find('.muestra-nombre').val());

            if (nombre === '') {
                return;
            }

            let muestra = {
                nombre: nombre,
                tipo: fila.find('.muestra-tipo').val(),
                orden: index + 1,
                resultados: {},
                comportamiento: {
                    fuera: {
                        colorante: fila.find('.colorante-fuera-valor').val() !== '' ? parseFloat(fila.find('.colorante-fuera-valor').val()) : null,
                        natural: fila.find('.natural-fuera-valor').val() !== '' ? parseFloat(fila.find('.natural-fuera-valor').val()) : null
                    },
                    dentro: {
                        colorante: fila.find('.colorante-dentro-valor').val() !== '' ? parseFloat(fila.find('.colorante-dentro-valor').val()) : null,
                        natural: fila.find('.natural-dentro-valor').val() !== '' ? parseFloat(fila.find('.natural-dentro-valor').val()) : null
                    }
                }
            };

            fila.find('.resultado-parametro').each(function() {
                let input = $(this);
                let clave = input.data('clave');
                let valor = input.val();

                if (valor !== '') {
                    muestra.resultados[clave] = parseFloat(valor);
                }
            });

            datos.muestras.push(muestra);
        });

        return datos;
    }

    function guardarPruebaComportamiento() {
        let datos = obtenerDatosPruebaComportamiento();

        if ($.trim(datos.titulo) === '') {
            alert('El título es obligatorio');
            $('#titulo').focus();
            return;
        }

        if ($.trim(datos.fecha) === '') {
            alert('La fecha es obligatoria');
            $('#fecha').focus();
            return;
        }

        if (datos.muestras.length === 0) {
            alert('Debe capturar al menos una muestra');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'funciones/pruebas_comportamiento_guardar.php',
            data: JSON.stringify(datos),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            beforeSend: function() {
                $('#btnGuardarPruebaComportamiento').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(resp) {
                if (resp.success) {
                    alert(resp.message);
                    $('#modal_pruebas_comportamiento').modal('hide');
                    if ($.fn.DataTable.isDataTable('#dataTablePruebasComportamiento')) {
                        $('#dataTablePruebasComportamiento').DataTable().ajax.reload(null, false);
                    }
                } else {
                    alert(resp.message || 'Error al guardar la prueba');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Error de servidor al guardar la prueba');
            },
            complete: function() {
                $('#btnGuardarPruebaComportamiento').prop('disabled', false).html('<i class="fa fa-save"></i> Guardar prueba');
            }
        });
    }
</script>