<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

if (isset($_POST['action']) && $_POST['action'] == 'obtener_datos') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $cte_id = isset($_POST['cte_id']) ? intval($_POST['cte_id']) : 0;

        if ($cte_id <= 0) {
            throw new Exception("Cliente inválido");
        }

        /*
            Ahora usamos la vista de disponibilidad de PT cliente.

            Regla:
            existencia disponible = rrc_ext_real - órdenes abiertas comprometidas

            Estados abiertos:
            PENDIENTE, PROCESO, ETIQUETA LIBERADA, LIBERADO
        */
        $sql = "
            SELECT 
                v.rrc_ext_inicial AS rr_ext_inicial,
                v.rrc_id,
                v.rev_id,
                v.pres_id,
                v.cte_id,
                v.rrc_ext_real AS rr_ext_real,
                v.cantidad_comprometida,
                v.cantidad_disponible,
                pres.pres_descrip,
                pres.pres_kg,
                rev.rev_folio AS revoltura
            FROM vw_rev_revolturas_pt_cliente_disponible v
            INNER JOIN rev_presentacion pres 
                ON pres.pres_id = v.pres_id
            INNER JOIN rev_revolturas rev 
                ON rev.rev_id = v.rev_id
            WHERE v.cte_id = ?
              AND v.cantidad_disponible > 0
              AND rev.rev_count_etiquetado > 0
            ORDER BY rev.rev_folio DESC, v.rrc_id ASC
        ";

        $stmt = mysqli_prepare($cnx, $sql);
        mysqli_stmt_bind_param($stmt, "i", $cte_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            throw new Exception(mysqli_error($cnx));
        }

        $datos = array();

        while ($fila = mysqli_fetch_assoc($result)) {
            $fila['rr_ext_inicial'] = floatval($fila['rr_ext_inicial']);
            $fila['rr_ext_real'] = floatval($fila['rr_ext_real']);
            $fila['cantidad_comprometida'] = floatval($fila['cantidad_comprometida']);
            $fila['cantidad_disponible'] = floatval($fila['cantidad_disponible']);
            $fila['pres_kg'] = floatval($fila['pres_kg']);

            $datos[] = $fila;
        }

        echo json_encode($datos);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }

    exit();
}
?>

<script>
    $(document).ready(function() {
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        $('#dataTableEmpaquesClientes').DataTable({
            responsive: true,
            bDestroy: true,
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ )",
                search: "Buscar:",
                zeroRecords: "No se encontraron registros coincidentes",
                paginate: {
                    next: "Siguiente",
                    previous: "Anterior"
                },
            },
            order: [
                [0, 'desc']
            ],
            "sDom": "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 'B><'col-sm-12 col-md-4'f>r>t<'row'<'col-md-4'i>><'row'p>",
            buttons: {
                dom: {
                    button: {
                        className: 'btn'
                    },
                },
                buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Listado Cliente Empacado',
                        filename: 'Listado_cliente_empacado_excel',
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Cliente Empacado',
                        filename: 'Listado_cliente_empacado_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Cliente Empacado',
                        filename: 'Listado_cliente_empacado_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/cliente_empacado_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'cte_nombre'
                },
                {
                    data: 'total_presentaciones'
                },
                {
                    data: 'total_empaques'
                },
                {
                    data: 'total_kilos',
                    render(data) {
                        return formatter.format(data);
                    }
                },
                {
                    data: null,
                    render: function(row) {
                        return `<button class="btn btn-primary btn-facturar-cliente" data-emp="${row.cte_id}">Crear orden</button>`;
                    }
                }
            ]
        });

        $('#dataTableEmpaquesClientes').on('click', '.btn-facturar-cliente', function() {
            let empData = $(this).data('emp');

            localStorage.removeItem('empaques');
            localStorage.setItem('cliente_id', empData);

            agregarEmpaque(empData);
        });

        function abrir_modal_facturas() {
            $.ajax({
                type: 'POST',
                url: 'funciones/orden_embarque_modal.php',
                success: function(result) {
                    $('#modal_facturas').html(result);
                    $('#modal_facturas').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo abrir el modal de orden de embarque.'
                    });
                }
            });
        }

        function agregarEmpaque(empData) {
            $.ajax({
                url: 'funciones/cliente_empacado.php',
                type: 'POST',
                data: {
                    cte_id: empData,
                    action: 'obtener_datos'
                },
                success: function(data) {
                    let response;

                    try {
                        response = typeof data === 'string' ? JSON.parse(data) : data;
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Respuesta inválida del servidor.'
                        });
                        return;
                    }

                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error
                        });
                        return;
                    }

                    if (!Array.isArray(response) || response.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin disponible',
                            text: 'Este cliente no tiene producto disponible para embarque.'
                        });
                        return;
                    }

                    let disponibles = [];

                    response.forEach(empaque => {
                        const cantidadDisponible = parseFloat(empaque.cantidad_disponible) || 0;

                        if (cantidadDisponible > 0) {
                            disponibles.push({
                                tipo_producto: 'REVOLTURA',

                                revoltura: empaque.revoltura,
                                rev_id: empaque.rev_id,

                                rr_id: null,
                                rrc_id: empaque.rrc_id,
                                pe_id: null,

                                pres_id: empaque.pres_id,
                                pres_descrip: empaque.pres_descrip,

                                rr_ext_inicial: empaque.rr_ext_inicial,
                                rr_ext_real: empaque.rr_ext_real,

                                cantidad_comprometida: empaque.cantidad_comprometida,
                                cantidad_disponible: empaque.cantidad_disponible,

                                pres_kg: empaque.pres_kg
                            });
                        }
                    });

                    if (disponibles.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin disponible',
                            text: 'Este cliente no tiene producto disponible para embarque.'
                        });
                        return;
                    }

                    /*
                        Nuevo flujo:
                        - empaques_cliente_disponibles = catálogo para seleccionar
                        - empaques = partidas reales de la orden, inicia vacío
                    */
                    localStorage.setItem('cliente_id', empData);
                    localStorage.setItem('empaques_cliente_disponibles', JSON.stringify(disponibles));
                    localStorage.setItem('empaques', JSON.stringify([]));

                    abrir_modal_facturas();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let mensaje = `Error al crear orden: ${textStatus}, ${errorThrown}`;

                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        mensaje = jqXHR.responseJSON.error;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: mensaje,
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            });
        }

        $('#modal_facturas').on('hidden.bs.modal', function() {
            localStorage.removeItem('empaques');
            localStorage.removeItem('empaques_cliente_disponibles');
            localStorage.removeItem('cliente_id');
        });
    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        Clientes empacado
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" id="dataTableEmpaquesClientes" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Presentaciones totales</th>
                        <th>Empaques totales</th>
                        <th>Kilos totales real</th>
                        <th>Generar orden</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_facturas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>