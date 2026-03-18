<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../../seguridad/user_seguridad.php";

?>

<script>
    $(document).ready(function() {
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        $('#dataTableEmpaques').DataTable({
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
                [1, 'desc']
            ],
            sDom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 'B><'col-sm-12 col-md-4'f>r>t<'row'<'col-md-4'i>><'row'p>",
            buttons: {
                dom: {
                    button: {
                        className: 'btn'
                    },
                },
                buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Listado Empacado',
                        filename: 'Listado_empacado_excel',
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Empacado',
                        filename: 'Listado_empacado_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Empacado',
                        filename: 'Listado_empacado_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/facturas_empacado_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'tipo_producto',
                    render: function(data) {
                        if (data === 'REVOLTURA') {
                            return `<span class="badge bg-success">${data}</span>`;
                        } else if (data === 'EXTERNO') {
                            return `<span class="badge bg-warning text-dark">${data}</span>`;
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'revoltura'
                },
                {
                    data: 'calidad',
                    render: function(data) {
                        return `<span class="badge bg-primary">${data}</span>`;
                    }
                },
                {
                    data: 'pres_descrip'
                },
                {
                    data: 'rr_ext_inicial'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const kg = parseFloat(row.pres_kg) * parseFloat(row.rr_ext_inicial);
                        return formatter.format(kg);
                    }
                },
                {
                    data: 'rr_ext_real'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const kg = parseFloat(row.pres_kg) * parseFloat(row.rr_ext_real);
                        return formatter.format(kg);
                    }
                },
                {
                    data: null,
                    render: function(row) {
                        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

                        const existe = empaquesArray.some(function(empaque) {
                            return (
                                (row.tipo_producto === 'EMPACADO' &&
                                    empaque.tipo_producto === 'EMPACADO' &&
                                    empaque.rr_id == row.rr_id) ||
                                (row.tipo_producto === 'EXTERNO' &&
                                    empaque.tipo_producto === 'EXTERNO' &&
                                    empaque.pe_id == row.pe_id)
                            );
                        });

                        if (existe) {
                            return '<a href="#" class="btn-facturar disabled" data-emp=\'' + JSON.stringify(row) + '\' style="color: gray; pointer-events: none;"><i class="fa-solid fa-hand" style="display: none;"></i></a>';
                        } else {
                            return '<a href="#" class="btn-facturar" data-emp=\'' + JSON.stringify(row) + '\'><i class="fa-solid fa-hand"></i></a>';
                        }
                    }
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                let totalInicial = 0;
                let totalReal = 0;

                data.forEach(function(row) {
                    const kilosIniciales = parseFloat(row.pres_kg) * parseFloat(row.rr_ext_inicial);
                    const kilosReales = parseFloat(row.pres_kg) * parseFloat(row.rr_ext_real);

                    totalInicial += kilosIniciales;
                    totalReal += kilosReales;
                });

                $('#kilos-iniciales-total').html(formatter.format(totalInicial));
                $('#kilos-reales-total').html(formatter.format(totalReal));
            }
        });

        $('#dataTableEmpaques').on('click', '.btn-facturar', function(e) {
            e.preventDefault();
            let empData = $(this).data('emp');
            agregarEmpaque(empData);
        });
    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        Empaques
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row justify-content-end">
            <div class="d-md-flex justify-content-md-end">
                <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" onclick="abrir_modal_cliente_empaque()">
                    <i class="fa-solid fa-file-invoice"></i> Apartar Empaque
                </button>
                <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" onclick="abrir_modal_facturas()">
                    <i class="fa-solid fa-file-invoice"></i> Orden de embarque
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover display" cellpadding="0" cellspacing="0" id="dataTableEmpaques" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Tipo de Producto</th>
                        <th>Revoltura</th>
                        <th>Calidad</th>
                        <th>Presentación</th>
                        <th>Existencia Inicial</th>
                        <th>Kilos Inicial</th>
                        <th>Existencia Real</th>
                        <th>Kilos Real</th>
                        <th>Tomar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Totales:</th>
                        <th></th>
                        <th id="kilos-iniciales-total"></th>
                        <th></th>
                        <th id="kilos-reales-total"></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_facturas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    $('#modal_facturas').on('hidden.bs.modal', function() {
        localStorage.removeItem('empaques');
    });

    function abrir_modal_facturas() {
        $.ajax({
            type: 'POST',
            url: 'funciones/orden_embarque_modal.php',
            success: function(result) {
                $('#modal_facturas').html(result);
                $('#modal_facturas').modal('show');
            }
        });
    }

    function abrir_modal_cliente_empaque() {
        $.ajax({
            type: 'POST',
            url: 'funciones/cliente_empacado_modal.php',
            success: function(result) {
                $('#modal_facturas').html(result);
                $('#modal_facturas').modal('show');
            }
        });
    }

    function agregarEmpaque(empData) {
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

        const existe = empaquesArray.some(function(empaque) {
            return (
                (empData.tipo_producto === 'REVOLTURA' &&
                    empaque.tipo_producto === 'REVOLTURA' &&
                    empaque.rr_id == empData.rr_id) ||
                (empData.tipo_producto === 'EXTERNO' &&
                    empaque.tipo_producto === 'EXTERNO' &&
                    empaque.pe_id == empData.pe_id)
            );
        });

        if (existe) {
            Swal.fire({
                icon: 'info',
                title: 'Ya existe',
                text: 'Este producto ya fue agregado previamente.'
            });
        } else {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas agregar este producto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    empaquesArray.push(empData);
                    localStorage.setItem('empaques', JSON.stringify(empaquesArray));

                    Swal.fire(
                        'Agregado!',
                        'El producto ha sido agregado correctamente.',
                        'success'
                    );

                    $('#dataTableEmpaques').DataTable().ajax.reload();
                }
            });
        }
    }
</script>
