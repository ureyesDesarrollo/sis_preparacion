<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();
try {

    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT rev_estatus, rev_kilos FROM rev_revolturas WHERE rev_id = '" . $_POST['rev_id'] . "'"));
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
<style>
    .custom-checkbox {
        width: 40px;
        transform: scale(1.5);
    }
</style>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Revoltura: <?= $_POST['rev_folio'] ?> / <?= $res['rev_kilos'] ?> kg</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_empacar" method="POST">
                <input type="text" name="rev_id" value="<?= $_POST['rev_id']  ?>" class="d-none" id="rev_id">
                <?php if ($res['rev_estatus'] != '3') { ?>
                    <div id="campos_dinamicos">

                    </div>
                    <div class="row">
                        <div class="col-md-3 d-flex align-items-center">
                            <button type="button" id="agregarCampo" class="btn btn-primary w-100">Agregar Campo</button>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <button form="form_empacar" type="submit" class="btn btn-primary w-100" id="btn_form">
                                <img src="../iconos/guardar.png" alt=""> Guardar
                            </button>
                        </div>
                    </div>

                <?php } ?>
            </form>

            <div class="col-md-10 mt-3">
                <label for="tarimas" class="form_label">Listado de empacado</label>
                <table class="table table-bordered" id="presentaciones_revolturas">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cantidad</th>
                            <th>Presentación</th>
                            <th>KG</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-9 mb-3">
                    <div id="alerta-empacar" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button id="btn_actualizar" class="btn btn-primary d-none">Actualizar</button>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let detalle_ids = [];
        const fieldHTML = `
    <div class="row"> 
        <div class="col-md-5 mb-3">
            <label for="" class="form-label">Cantidad</label>
            <input type="text" name="cantidad[]" class="form-control" required value="" onkeypress="return isNumberKey(event, this);"/>
        </div>
        <div class="col-md-5 mb-3">
            <label for="presentacion" class="form-label">Presentación</label>
            <select name="presentacion[]" class="form-select"></select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="total" class="form-label">Total</label>
            <input name="total[]" class="form-control total" readonly />
        </div>
    </div>`;

        $('#campos_dinamicos').append(fieldHTML);
        // Llamamos la función para obtener presentaciones en la tabla al cargar el documento
        obtenerPresentacionesTabla();

        let total_kilos = 0;
        let kilos_empacados = 0;
        $('#campos_dinamicos').on('input change', 'input[name="cantidad[]"], select[name="presentacion[]"]', function() {
            const $row = $(this).closest('.row');
            const cantidad = parseFloat($row.find('input[name="cantidad[]"]').val()) || 0;
            const kilos = parseFloat($row.find('select[name="presentacion[]"] option:selected').data('kilos')) || 0;

            const total = cantidad * kilos;
            $row.find('.total').val(total.toFixed(2));

            totalKilos = 0;
            $('#campos_dinamicos .total').each(function() {
                totalKilos += parseFloat($(this).val());
            });

            console.log(totalKilos);
        });



        // Evento para manejar el submit del formulario
        $('#form_empacar').submit(function(e) {
            e.preventDefault();
            const revKilos = <?= (float)$res['rev_kilos'] ?>;
            let dataForm = $(this).serialize();
            console.log(kilos_empacados);
            let kilosTotales = kilos_empacados + totalKilos;
            console.log(kilosTotales);
            if ((totalKilos > revKilos) || (kilosTotales > revKilos)) {
                let msg = `Los kilos a empacar superan el máximo permitido (${revKilos} kg)`;
                alertas_v5("#alerta-empacar", 'Error!', msg, 4, true, 5000);
            } else {
                agregarCampos(dataForm);
            }
        });

        // Evento para agregar campos dinámicos
        $('#agregarCampo').click(function() {
            const fieldHTML = `
            <div class="row"> 
            <div class="col-md-5 mb-3">
                <label for="" class="form-label">Cantidad</label>
                <input type="text" name="cantidad[]" class="form-control" required value="" onkeypress="return isNumberKey(event, this);"/>
            </div>
            <div class="col-md-5 mb-3">
                <label for="presentacion" class="form-label">Presentación</label>
                <select name="presentacion[]" class="form-select"></select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="total" class="form-label">Total</label>
                <input name="" class="form-control total" readonly />
            </div>
            </div>`;

            $('#campos_dinamicos').append(fieldHTML);
            cargarPresentaciones($('#campos_dinamicos select').last());
        });

        // Función para agregar campos y enviar datos por AJAX
        function agregarCampos(dataForm) {
            $.ajax({
                type: 'POST',
                url: 'funciones/revolturas_orden_empaque_insertar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        alertas_v5("#alerta-empacar", 'Listo!', res.success, 1, true, 5000);
                        // Reseteamos los campos dinámicos
                        $('#campos_dinamicos').html('');
                        $('#campos_dinamicos').append(fieldHTML);
                        obtenerPresentacionesTabla();
                        cargarPresentaciones($('select[name="presentacion[]"]').first());
                        $('#dataTableRevolturas').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-empacar", 'Error!', res.error, 3, true, 5000);
                        $('#dataTableRevolturas').DataTable().ajax.reload();
                    }
                }
            });
        }

        // Función para cargar las presentaciones en el select
        function cargarPresentaciones(selectElement) {
            $.ajax({
                type: 'GET',
                url: 'catalogos/presentaciones_listado.php',
                success: function(data) {
                    let presentaciones = JSON.parse(data);
                    let options = '<option value="">Seleccione</option>';
                    presentaciones.forEach(function(presentacion) {
                        if (presentacion.pres_estatus == 'A') {
                            options += `<option value="${presentacion.pres_id}" data-kilos="${presentacion.pres_kg}">${presentacion.pres_descrip} - ${presentacion.pres_kg}</option>`;
                        }
                    });
                    selectElement.append(options);
                },
                error: function() {
                    alert('Error al cargar las presentaciones.');
                }
            });
        }

        // Función para obtener las presentaciones en la tabla
        function obtenerPresentacionesTabla() {
            let rev_id = $('#form_empacar #rev_id').val(); // Selector más específico

            $.ajax({
                type: 'POST',
                url: 'funciones/revolturas_orden_empaque_listado.php',
                data: {
                    'rev_id': rev_id
                },
                success: function(data) {
                    try {
                        let res = JSON.parse(data);
                        let tbody = $('#presentaciones_revolturas tbody');
                        tbody.empty(); // Limpiamos el contenido del tbody
                        let total = 0; // Inicializamos el total

                        if (res.length > 0) {
                            $.each(res, function(index, item) {
                                let kg = parseFloat(item.cantidad_solicitada) * parseFloat(item.kilos_por_unidad);
                                total += kg;
                                let row = `<tr>
                                <td>${index + 1}</td>
                                <td>${
                                    item.estado === 'PENDIENTE' 
                                    ? `<input class="form-control cantidad-input" data-detalle-id="${item.detalle_id}" value="${item.cantidad_solicitada}"/>` 
                                    : item.cantidad_solicitada
                                }</td>
                                <td>${item.nombre_presentacion}</td>
                                <td>${kg.toFixed(2)}</td>
                                <td>${item.estado}</td>
                               </tr>`;
                                tbody.append(row);
                            });

                            let totalRow = `<tr>
                                <td colspan="4"><strong>Total</strong></td>
                                <td><strong>${total.toFixed(2)}</strong></td>
                               </tr>`;
                            tbody.append(totalRow);

                            if (res[0].estado === 'PENDIENTE') {
                                $('#btn_actualizar').removeClass('d-none');
                            }

                        } else {
                            tbody.append('<tr><td colspan="5">No hay datos disponibles</td></tr>');
                        }
                        kilos_empacados = total;
                        terminarRevoltura(total);
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                    }
                },
                error: function() {
                    alert('Error al obtener presentaciones.');
                }
            });
        }

        function terminarRevoltura(total) {
            const revKilos = <?= (float)$res['rev_kilos'] ?>;
            let rev_id = $('#form_empacar #rev_id').val();
            if (total === revKilos) {
                $('#campos_dinamicos').hide();
                $('#btn_form').hide();
                $('#agregarCampo').hide();
                $('#dataTableRevolturas').DataTable().ajax.reload();
            }


        }
        // Cargar presentaciones en el select inicial
        cargarPresentaciones($('select[name="presentacion[]"]').first());

        $('#btn_actualizar').click(function() {
            detalle_ids = [];
            $('.cantidad-input').each(function() {
                let cantidad = $(this).val();
                let detalle_id = $(this).data('detalle-id');
                detalle_ids.push({
                    detalle_id: detalle_id,
                    cantidad: cantidad
                });
            });


            $.ajax({
                url: 'funciones/orden_empaque_actualizar.php',
                type: 'POST',
                data: {
                    detalle_ids: detalle_ids
                },
                success: function(res) {
                    let data = JSON.parse(res);
                    console.log(data);
                    alertas_v5("#alerta-empacar", 'Listo!', data.success, 1, true, 5000);
                    // Reseteamos los campos dinámicos
                    $('#campos_dinamicos').html('');
                    $('#campos_dinamicos').append(fieldHTML);
                    obtenerPresentacionesTabla();
                },
                error: function(err) {
                    console.error('Error:', err);
                    alert('Error al actualizar');
                }
            });
        });

    });
</script>