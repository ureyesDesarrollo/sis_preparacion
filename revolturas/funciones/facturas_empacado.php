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
                [0, 'desc']
            ],
            "sDom": "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 'B><'col-sm-12 col-md-4'f>r>t<'row'<'col-md-4'i>><'row'p>",
            buttons: {
                dom: {
                    button: {
                        className: 'btn' //Primary class for all buttons
                    },
                },
                buttons: [{
                        //Botón para Excel
                        extend: 'excel',
                        footer: true,
                        title: 'Listado Empacado',
                        filename: 'Listado_empacado_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Empacado',
                        filename: 'Listado_empacado_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Empacado',
                        filename: 'Listado_empacado_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/facturas_empacado_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'revoltura'
                },
                {
                    data: 'calidad',
                    render: function(data, type, row) {
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
                        const kg = row.pres_kg * row.rr_ext_inicial;
                        return `${formatter.format(kg)}`;
                    }
                },
                {
                    data: null,
                    render: function(row) {
                        if (row.rr_ext_real == 0.00) {
                            return row.rr_ext_inicial;
                        } else {
                            return row.rr_ext_real;
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let kg = 0;
                        if (row.rr_ext_real == 0.00) {
                            return `${formatter.format(row.pres_kg * row.rr_ext_inicial)}`;
                        } else {
                            return `${formatter.format(row.pres_kg * row.rr_ext_real)}`;
                        }
                    }
                },
                {
                    data: null,
                    render: function(row) {
                        // Recupera el arreglo del localStorage o inicializa un arreglo vacío
                        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

                        // Comprueba si el elemento ya existe en el localStorage 
                        const existe = empaquesArray.some(empaque => empaque.rr_id === row.rr_id);

                        // Si ya existe, muestra el botón en gris o sin icono; si no, muestra el botón normal
                        if (existe) {
                            return '<a href="#" class="btn-facturar disabled" data-emp=\'' + JSON.stringify(row) + '\' style="color: gray;"><i class="fa-solid fa-hand" style="display: none;"></i></a>';
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
                    const kilosIniciales = row.pres_kg * row.rr_ext_inicial;
                    const kilosReales = row.rr_ext_real == 0.00 ? kilosIniciales : row.pres_kg * row.rr_ext_real;

                    totalInicial += kilosIniciales;
                    totalReal += kilosReales;
                });

                // Actualiza los valores en el footer
                $('#kilos-iniciales-total').html(formatter.format(totalInicial));
                $('#kilos-reales-total').html(formatter.format(totalReal));
            }
        });

        $('#dataTableEmpaques').on('click', '.btn-facturar', function() {
            let empData = $(this).data('emp'); // Obtiene el objeto completo de datos del botón
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
            <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" id="dataTableEmpaques" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Revoltura</th>
                        <th>Calidad</th>
                        <th>Presentac&iacute;on</th>
                        <th>Existencia Incial</th>
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
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Totales:</th>
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
        localStorage.clear();
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
        // Recupera los datos del Local Storage o inicializa un arreglo vacío
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

        const existe = empaquesArray.some(empaque => empaque.rr_id === empData.rr_id);

        if (existe) {
            // Muestra una alerta de que el empaque ya está agregado
            Swal.fire({
                icon: 'info',
                title: 'Ya existe',
                text: 'Este empaque ya ha sido agregado previamente.'
            });
        } else {
            // Muestra la alerta de confirmación para agregar el empaque
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas agregar este empaque?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Agrega el nuevo objeto al arreglo
                    empaquesArray.push(empData);

                    // Almacena el arreglo actualizado en el Local Storage
                    localStorage.setItem('empaques', JSON.stringify(empaquesArray));

                    // Muestra un mensaje de éxito
                    Swal.fire(
                        'Agregado!',
                        'El empaque ha sido agregado correctamente.',
                        'success'
                    );

                    $('#dataTableEmpaques').DataTable().ajax.reload();

                }
            });
        }
    }
</script>