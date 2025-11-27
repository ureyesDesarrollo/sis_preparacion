<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviemvbre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$fechaActual = date("Y-m-d");

$oe_id = null;

$oe_id = isset($_POST['oe_id']) ? json_decode($_POST['oe_id']) : null;


?>


<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Liberar embarque</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="text" id="orden_id" name="orden_id" value="<?= $oe_id ?>" hidden>
            <form id="form_liberacion_embarque" method="POST">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-12">
                        <label for="cliente" class="form-label d-inline-block me-2 mb-0">Cliente:</label>
                        <input name="cte_id" id="cte_id" class="form-control d-inline-block me-2" style="width: 80px;">
                        <input name="cte_nombre" id="cte_nombre" class="form-control d-inline-block" style="width: calc(100% - 180px);">
                    </div>
                </div>
                <div class="row">
                    <div class="">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Revoltura</th>
                                    <th>Empaque</th>
                                    <th>Cantidad solicitada</th>
                                    <th>Liberación</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Asegurar que el modal de confirmación esté oculto
        $('#modalLiberarEtiqueta').modal('hide');

        // Simulación para cargar empaques en la tabla
        cargarDatosEmpaques();

        // Click en botón Liberar dentro del primer modal
        // Evento para el botón liberar
        $(document).on('click', '.btn-liberar', function() {

            let empaque_id = $(this).data('id');
            console.log('Empaque seleccionado:', empaque_id);

            $('#btnConfirmarLiberacion').data('id', empaque_id);

            // Cerrar y luego abrir el siguiente modal
            $('#modal_orden_embarque').one('hidden.bs.modal', function() {
                $('#modalLiberarEtiqueta').modal('show');
            }).modal('hide');
        });

        // Evento para el botón cerrar (X)
        $('#modal_orden_embarque .btn-close, .modal-footer .btn-secondary').on('click', function() {
            $('#modal_orden_embarque').off('hidden.bs.modal'); // Elimina cualquier handler pendiente
        });

        // Click en botón Confirmar del segundo modal
        $('#btnConfirmarLiberacion').off().on('click', function() {
            let empaque_id = $(this).data('id');
            empaque_id = JSON.parse(empaque_id);

            // Mostrar SweetAlert con loading
            Swal.fire({
                title: 'Liberando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Aquí haces la lógica de liberación...
            $.ajax({
                type: 'POST',
                url: 'funciones/orden_embarque_etiqueta_liberacion.php',
                data: {
                    oe_id: $('#orden_id').val(),
                    empaque_id: empaque_id,
                    cantidad_etiquetas: $('#cantidad_etiquetas').val()
                },
                success: function(response) {
                    let data = JSON.parse(response);
                    Swal.close();
                    // Cerrar el modal secundario
                    $('#modalLiberarEtiqueta').modal('hide');

                    // Mostrar éxito en SweetAlert
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Liberación exitosa',
                            text: data.success,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo liberar el empaque'
                    });
                }
            });
        });

        $(document).on('click', '.btn-certificado', function(e) {
            e.preventDefault();

            const CERTIFICADOS = {
                2: {
                    url: 'certificado_danone.php'
                },
                88: {
                    url: 'certificado_danone.php'
                },
                116: {
                    url: 'certificado_dgari.php'
                }
            };

            const cte_id = parseInt($('#cte_id').val());
            const certificado = CERTIFICADOS[cte_id] || {
                url: 'certificado_varios.php'
            };

            let empaque_id = $(this).data('id');
            empaque_id = JSON.parse(empaque_id);
            let oe_id = $('#orden_id').val();

            // Construir la URL con parámetros
            const url = `funciones/certificados_calidad/${certificado.url}?empaque_id=${empaque_id}&oe_id=${oe_id}`;

            // Crear un enlace invisible y hacer clic para descargar
            const link = document.createElement('a');
            link.href = url;
            link.target = '_self';
            link.click();
        });



    });

    // Función para cargar los datos del localStorage y mostrarlos en la tabla
    function cargarDatosEmpaques() {
        const oe_id = $('#orden_id').val();
        $.ajax({
            type: 'POST',
            url: 'funciones/orden_embarque_detalle_listado.php',
            data: {
                oe_id: oe_id
            },
            success: function(response) {
                let data = JSON.parse(response);
                setTimeout(() => {
                    obtenerClientes(data[0].cliente_id);
                    let tableBody = $('#table tbody');
                    tableBody.empty();
                    data.forEach((item, index) => {
                        let row = `<tr>
                            <td>${index + 1}</td>
                            <td>${item.rev_folio}</td>
                            <td>${item.presentacion_descripcion}</td>
                            <td>${item.cantidad_solicitada}</td>
                            <td><button type="button" class="btn btn-outline-success btn-liberar" data-id='${JSON.stringify(item.empaque_id)}' title="Imprimir etiqueta"><i class="fa-solid fa-tags"></i></button>
                            <button type="button" class="btn btn-outline-success btn-certificado" data-id='${JSON.stringify(item.empaque_id)}' title="Generar certificado"><i class="fa-solid fa-file-word"></i></button></td>
                        </tr>`;
                        tableBody.append(row);
                    });
                    localStorage.setItem('embarque', JSON.stringify(data));
                }, 100);
            },
            error: function() {
                alert('Error al cargar los datos de los empaques.');
            }
        });
    }

    function obtenerClientes(cliente_id = '') {
        $.ajax({
            type: 'GET',
            url: 'catalogos/clientes_listado.php',
            success: function(data) {
                let clientes = JSON.parse(data);
                let options = '';
                let cte_id = 0;
                let cte_nombre = '';
                clientes.forEach(function(cte) {
                    if (cte.cte_id == cliente_id) {
                        $('#cte_id').empty().val(cte.cte_id);
                        $('#cte_nombre').empty().val(cte.cte_nombre);
                    }
                });
            },
            error: function() {
                alert('Error al cargar los clientes.');
            }
        });
    }
</script>