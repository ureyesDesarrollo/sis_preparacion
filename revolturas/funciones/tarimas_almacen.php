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
            revolver: <?php echo ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) ? 1 : 0; ?>,
            mezclar: <?php echo ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) ? 1 : 0; ?>,
            parcializar: <?php echo ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) ? 1 : 0 ?>,
            ventas: <?php echo (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 26)) ? 1 : 0; ?>,
        };

        const proIdMapping = {
            '1': 'FINOSB',
            '2': 'BARREDURA',
            '3': 'CHURRO'
        };

        $('#dataTableTarimasAlmacen').DataTable({
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/tarimas_almacen_listado.php',
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
                        if (proIdMapping.hasOwnProperty(row.pro_id)) {
                            return proIdMapping[row.pro_id];
                        } else {
                            return row.pro_id_2 ? `${row.pro_id}/${row.pro_id_2}` : row.pro_id;
                        }
                    }
                },
                {
                    data: 'tar_folio'
                },
                {
                    data: 'tar_kilos'
                },
                {
                    data: 'tar_bloom'
                },
                {
                    data: 'tar_viscosidad'
                },
                {
                    data: 'tar_ph'
                },
                {
                    data: 'tar_trans'
                },
                {
                    data: 'tar_color'
                },
                {
                    data: 'tar_par_extr'
                },
                {
                    data: 'tar_par_ind'
                },
                {
                    data: 'tar_redox'
                },
                {
                    data: 'tar_malla_30'
                },
                {
                    data: 'tar_malla_45'
                },
                {
                    data: 'tar_humedad'
                },
                {
                    data: 'cal_descripcion'
                },
                {
                    data: 'tar_rechazado',
                    render: function(data, type, row) {
                        return `<span>${(data == 'C' ? 'Cuarentena' : 'Aceptado')}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (permisos.parcializar) {
                            return '<button class="btn-parcializar btn btn-sm btn-outline-info" data-tar="' + row.tar_id + '"><i class="fa-solid fa-divide"></i></button>';
                        }
                        return '';
                    },
                    visible: permisos.parcializar
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if ((permisos.ventas || permisos.revolver) && !proIdMapping.hasOwnProperty(row.pro_id)) {
                            return '<button class="btn-revoltura btn btn-sm btn-outline-info" data-tar="' + row.tar_id + '" data-kilos="' + row.tar_kilos + '"><i class="fa-solid fa-blender"></i></button>';
                        }
                        return '';
                    },
                    visible: [permisos.revolver, permisos.ventas]
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (permisos.mezclar && !proIdMapping.hasOwnProperty(row.pro_id)) {
                            return '<button class="btn-mezcla btn btn-sm btn-outline-info" data-tar="' + row.tar_id + '" data-kilos="' + row.tar_kilos + '"><i class="fa-solid fa-mortar-pestle"></i></button>';
                        }
                        return '';
                    },
                    visible: permisos.mezclar
                }
            ]
        });

        $('#dataTableTarimasAlmacen').on('click', '.btn-revoltura', function() {
            let tar_id = $(this).data('tar');
            let tar_kilos = $(this).data('kilos');
            tomar_tarima_revoltura(tar_id, tar_kilos);
        });

        $('#dataTableTarimasAlmacen').on('click', '.btn-mezcla', function() {
            let tar_id = $(this).data('tar');
            let tar_kilos = $(this).data('kilos');
            tomar_tarima_mezcla(tar_id, tar_kilos);
        });


        $('#dataTableTarimasAlmacen').on('click', '.btn-parcializar', function() {
            let tar_id = $(this).data('tar');
            abrir_modal_parcilizar(tar_id);
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
                    <li class="breadcrumb-item active" aria-current="page">Tarimas en almacen</li>
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
                <?php if ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) { ?>
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#" onclick="abrir_modal_receta()">
                        <i class="fa-solid fa-receipt"></i> Pre-revoltura
                    </button>
                <?php } ?>
                <?php if ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) { ?>
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#" onclick="abrir_modal_filtro()">
                        <i class="fa-solid fa-filter"></i> Filtrar
                    </button>
                <?php } ?>
                <?php if ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 26)) { ?>
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#" onclick="abrir_modal_revoltura()">
                        <i class="fa fa-plus"></i> Crear Revoltura
                    </button>
                <?php } ?>
                <?php if ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2)) { ?>
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#" onclick="abrir_modal_mezcla()">
                        <i class="fa fa-plus"></i> Crear Mezcla
                    </button>
                <?php } ?>
            </div>

        </div>


    </div>

</div>

<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableTarimasAlmacen" style="width: 100%;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Proceso</th>
                    <th>Tarima</th>
                    <th>Kilos</th>
                    <th>Bloom</th>
                    <th>Viscosidad</th>
                    <th>PH</th>
                    <th>Trans</th>
                    <th>Color</th>
                    <th>Part.Ext</th>
                    <th>Indi</th>
                    <th>Redox</th>
                    <th>Malla 30</th>
                    <th>Malla 45</th>
                    <th>Humedad</th>
                    <th>Calidad</th>
                    <th>Cuarentena</th>
                    <th>Parcializar</th>
                    <th>Revolver</th>
                    <th>Mezclar</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_crear_revolturas" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_crear_mezclas" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_filtro" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_parcializar" tabindex="-1" aria-labelledby="exampleModalLabel4" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_prerevoltura" tabindex="-1" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_revoltura() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_crear_revoltura.php',
            success: function(result) {
                $('#modal_crear_revolturas').html(result);
                $('#modal_crear_revolturas').modal('show');
            }
        });
    }

    function abrir_modal_mezcla() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_crear_mezcla.php',
            success: function(result) {
                $('#modal_crear_mezclas').html(result);
                $('#modal_crear_mezclas').modal('show');
            }
        });
    }

    function abrir_modal_filtro() {
        $.ajax({
            type: 'POST',
            url: 'funciones/revoltruras_modal_filtro.php',
            success: function(result) {
                $('#modal_filtro').html(result);
                $('#modal_filtro').modal('show');
            }
        });
    }


    async function tomar_tarima_revoltura(id, tar_kilos) {
        try {
            // Obtener las tarimas
            const tarimas = await obtenerTarimasRevoltura();
            console.log(tarimas);
            // Verificar los kilos
            let kilos = 0;
            $.each(tarimas, function(index, item) {
                kilos += parseFloat(item.tar_kilos);
            });


            let kilos_t = kilos + parseFloat(tar_kilos);

            if (kilos_t > 5000.00) {
                Swal.fire({
                    title: "¡Ya se han tomado los 5000 kilos para la revoltura o al agregar la tarima excede los 5000 kilos permitidos!",
                    text: "",
                    icon: "info"
                });
                return;
            }
            Swal.fire({
                title: "¿Seguro que deseas revolver la tarima?",
                text: '',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'funciones/tarimas_almacen_tomar.php',
                        data: {
                            'tar_id': id,
                            action: 'revoltura'
                        },
                        success: function(result) {
                            let res = JSON.parse(result);
                            if (res.success) {
                                Swal.fire({
                                    title: "¡Tomada!",
                                    text: `${res.success}`,
                                    icon: "success"
                                });
                                $('#dataTableTarimasAlmacen').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: "¡Ocurrió un error!",
                                    text: `${res.error}`,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        } catch (e) {
            console.error('Error al obtener las tarimas:', e);
            Swal.fire({
                title: "¡Error!",
                text: "No se pudieron obtener las tarimas.",
                icon: "error"
            });
        }
    }

    async function tomar_tarima_mezcla(id, tar_kilos) {
        try {
            const tarimas = await obtenerTarimasMezcla();

            // Verificar los kilos
            let kilos = 0;
            $.each(tarimas, function(index, item) {
                kilos += parseFloat(item.tar_kilos);
            });

            let kilos_t = kilos + parseFloat(tar_kilos);

            if (kilos_t > 5000.00) {
                Swal.fire({
                    title: "¡Ya se han tomado los 5000 kilos para la revoltura o al agregar la tarima excede los 5000 kilos permitidos!",
                    text: "",
                    icon: "info"
                });
                return;
            }
            Swal.fire({
                title: "¿Seguro que deseas mezclar la tarima?",
                text: '',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'funciones/tarimas_almacen_tomar.php',
                        data: {
                            'tar_id': id,
                            action: 'mezcla'
                        },
                        success: function(result) {
                            let res = JSON.parse(result);
                            if (res.success) {
                                Swal.fire({
                                    title: "Tomada!",
                                    text: `${res.success}`,
                                    icon: "success"
                                });
                                $('#dataTableTarimasAlmacen').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: "Ocurrio un error!",
                                    text: `${res.error}`,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        } catch (e) {
            console.error('Error al obtener las tarimas:', e);
            Swal.fire({
                title: "¡Error!",
                text: "No se pudieron obtener las tarimas.",
                icon: "error"
            });
        }
    }

    function obtenerTarimasRevoltura() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_almacen_modal_crear_revoltura.php',
                data: {
                    action: 'obtener_tarimas'
                },
                success: function(data) {
                    try {
                        let res = JSON.parse(data);
                        resolve(res);
                    } catch (e) {
                        reject(e);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    reject(errorThrown);
                }
            });
        });
    }

    function obtenerTarimasMezcla() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_almacen_modal_crear_mezcla.php',
                data: {
                    action: 'obtener_tarimas'
                },
                success: function(data) {
                    try {
                        let res = JSON.parse(data);
                        resolve(res);
                    } catch (e) {
                        reject(e);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    reject(errorThrown);
                }
            });
        });
    }

    function abrir_modal_parcilizar(tar_id) {
        console.log(tar_id);
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_parcializar.php',
            data: {
                'tar_id': tar_id
            },
            success: function(result) {
                $('#modal_parcializar').html(result);
                $('#modal_parcializar').modal('show');
            }
        });
    }

    function abrir_modal_receta() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_prerevoltura.php',
            success: function(result) {
                $('#modal_prerevoltura').html(result);
                $('#modal_prerevoltura').modal('show');
            }
        });
    }
</script>