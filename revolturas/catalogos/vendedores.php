<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Vendedores</li>
                </ol>
            </nav>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_recetas_insertar" onclick="abrir_modal_vendedores()"> <i class="fa fa-plus"></i> Agregar Vendedor</button>
        </div>
    </div>
</div>

<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" id="tabla_vendedores" style="width: 100%;">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th>Nomina</th>
                    <th>Comisión</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modal_vendedores_insertar" tabindex="-1" aria-labelledby="modalAgregarVendedorLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"></div>

<script>
    $(document).ready(function() {
        $('#tabla_vendedores').DataTable({
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
                [0, 'asc']
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
                        title: 'Listado vendedores',
                        filename: 'Listado_vendedores_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado vendedores',
                        filename: 'Listado_vendedores_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado vendedores',
                        filename: 'Listado_vendedores_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/vendedores_listado.php',
                dataSrc: 'data'
            },
            columns: [{
                    data: 'ven_id'
                },
                {
                    data: 'ven_nombre',
                },
                {
                    data: 'ven_numero_nomina',
                },
                {
                    data: 'ven_porcentaje_comision',
                    render: function(data, type, row) {
                        return data + '%';
                    }
                },
                {
                    data: 'ven_estatus',
                    render: function(data, type, row) {
                        return data === 'A' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const accion = row.ven_estatus === 'A' ? 'B' : 'A';
                        // Aquí se generan los botones de acción
                        if (row.ven_estatus === 'A') {
                            return `
                                <button class="btn btn-outline-primary btn-sm" onclick="abrir_modal_vendedores(${row.ven_id})" title="Editar vendedor"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminar_vendedor(${row.ven_id}, '${accion}')" title="Eliminar vendedor"><i class="fas fa-trash-alt"></i></button>
                            `;
                        }
                        // Si el vendedor está inactivo, se muestra un botón para activar
                        if (row.ven_estatus === 'B') {
                            return `
                                <button class="btn btn-outline-success btn-sm" onclick="eliminar_vendedor(${row.ven_id}, '${accion}')" title="Activar vendedor"><i class="fas fa-check"></i></button>
                            `;
                        }
                        return '';
                    }
                }
            ]
        });
    });


    function abrir_modal_vendedores(ven_id = null) {
        if (ven_id) {
            // Si ven_id está definido, se trata de una edición
            $.ajax({
                type: 'POST',
                url: 'catalogos/vendedores_modal_editar.php',
                data: { ven_id: ven_id },
                success: function(result) {
                    $('#modal_vendedores_insertar').html(result);
                    $('#modal_vendedores_insertar').modal('show');
                }
            });
            return;
        }

        // Si ven_id no está definido, se trata de una inserción
       $.ajax({
            type: 'POST',
            url: 'catalogos/vendedores_modal_insertar.php',
            success: function(result) {
                $('#modal_vendedores_insertar').html(result);
                $('#modal_vendedores_insertar').modal('show');
            }
        });
    }

    function eliminar_vendedor(ven_id, accion) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción dara de baja al vendedor!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'catalogos/vendedores_eliminar.php',
                    data: JSON.stringify({ ven_id: ven_id , accion: accion }),
                    contentType: 'application/json',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Eliminado', res.message, 'success');
                            $('#tabla_vendedores').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar el vendedor.', 'error');
                    }
                });
            }
        });
    }

</script>