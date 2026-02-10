<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
?>

<script>
    $(document).ready(function() {

        const permisos = {
            ventas: <?php echo (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 26)) ? 1 : 0; ?>,
        };

        const proIdMapping = {
            '1': 'FINOSA',
            '2': 'FINOSB',
			'3': 'FINOSC'
        };

        $('#dataTableTarimasAlmacenVenta').DataTable({
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
                        title: 'Listado Tarimas Almacen',
                        filename: 'Listado_tarimas_almacen_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Tarimas Almacen',
                        filename: 'Listado_tarimas_almacen_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Tarimas Almacen',
                        filename: 'Listado_tarimas_almacen_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    }
			]},
            ajax: {
                url: 'funciones/tarimas_almacen_venta_listado.php',
                dataSrc: ''
            },
            rowCallback: function(row, data, index) {


                if (data.tar_fino === 'F') {
                    $('td', row).css('background-color', '#fffcda');
                }

                if (data.tar_kilos < 1000.00) {
                    $('td', row).css('background-color', '#DCDCDC');
                }

                if (data.tar_fino === 'F' && data.tar_kilos < 1000.00) {
                    $('td', row).css('background-color', '#d1e7dd');
                }


            },
            columns: [{
                    data: 'tar_fecha'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const proIdMapped = proIdMapping[row.pro_id] || `P${row.pro_id}`;
                        const proIdFormatted = row.pro_id_2 ? `P${row.pro_id}/${row.pro_id_2}` : proIdMapped;

                        return `${proIdFormatted}T${row.tar_folio}`;
                    }
                },
                {
                    data: 'tar_kilos'
                },
                {
                    data: 'tar_id',
                    render: function(data, type, row) {
                        return `<button class="btn-facturar-tar btn btn-outline-primary" data-tar='${JSON.stringify(row)}'><i class="fa-solid fa-file-invoice-dollar"></i></button>`;
                    },
                    visible: permisos.ventas
                }
            ]
        });

        $('#dataTableTarimasAlmacenVenta').on('click', '.btn-facturar-tar', function() {
            try {
                const tarima = $(this).data('tar');
                let tarimas = JSON.parse(localStorage.getItem('tarimas')) || [];

                const isTarimaAlreadyAdded = tarimas.some(t => t.tar_id === tarima.tar_id);

                if (!isTarimaAlreadyAdded) {
                    tarimas.push(tarima);
                    localStorage.setItem('tarimas', JSON.stringify(tarimas));
                    showSwalNotification('success', 'Tarima Agregada', `Tarima ${getProId(tarima)}T${tarima.tar_folio} ha sido agregada correctamente.`);
                } else {
                    showSwalNotification('warning', 'Tarima ya agregada', `Tarima ${getProId(tarima)}T${tarima.tar_folio} ya ha sido agregada correctamente.`);
                }
            } catch (error) {
                console.error('Error al procesar la tarima:', error);
                showSwalNotification('error', 'Error', 'Hubo un problema al procesar la tarima.');
            }
        });

        function getProId(tarima) {
            if (proIdMapping.hasOwnProperty(tarima.pro_id)) {
                return proIdMapping[tarima.pro_id];
            } else {
                return tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
            }
        }

        function showSwalNotification(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

    });
</script>

<div class="container-fluid">
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toastAlert" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    Tarima agregada correctamente.
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Tarimas en almacen para venta</li>
                </ol>
            </nav>
            <div>
                <div style="width: 20px; height: 20px; background-color: #d1e7dd; vertical-align: middle; display: inline-block; border: 1px solid #000;"></div>
                <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Es fino y tiene menos de 1000 kilos</span>
                <div style="width: 20px; height: 20px; background-color: #fffcda; vertical-align: middle; display:inline-block; border: 1px solid #000;"></div>
                <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Es fino</span>
                <div style="width: 20px; height: 20px; background-color: #DCDCDC; vertical-align: middle; display:inline-block; border: 1px solid #000;"></div>
                <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Tiene menos de 1000 kilos</span>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="d-md-flex justify-content-md-end">
                <?php if ($_SESSION['privilegio'] == 26 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) { ?>
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#" onclick="abrir_modal_factura_tar()">
                        <i class="fa fa-file-invoice"></i> Facturar
                    </button>
                <?php } ?>
            </div>

        </div>


    </div>

</div>

<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableTarimasAlmacenVenta" style="width: 100%;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tarima</th>
                    <th>Kilos</th>
                    <th>Facturar</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="modal_facturar_tar" tabindex="-1" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_factura_tar() {
        $.ajax({
            type: 'POST',
            url: 'funciones/facturas_tarimas_modal.php',
            success: function(result) {
                $('#modal_facturar_tar').html(result);
                $('#modal_facturar_tar').modal('show');
            }
        });
    }
</script>