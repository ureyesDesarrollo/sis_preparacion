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
            editar: <?php echo fnc_permiso($_SESSION['privilegio'], 41, 'upe_editar') ? 1 : 0; ?>,
            listar: <?php echo (fnc_permiso($_SESSION['privilegio'], 41, 'upe_listar') || $_SESSION['privilegio'] == 22) ? 1 : 0; ?>,
            calidad: <?php echo ($_SESSION['privilegio'] === '28' || $_SESSION['privilegio'] === '1' || $_SESSION['privilegio'] === '2') ? 1 : 0; ?>
        };

        const proIdMapping = {
            '1': 'FINOSA',
            '2': 'FINOSB',
			'3': 'FINOSC'
        };

        $('#dataTableTarimas').DataTable({
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
                        title: 'Listado Tarimas',
                        filename: 'Listado_tarimas_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Tarimas',
                        filename: 'Listado_tarimas_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Tarimas',
                        filename: 'Listado_tarimas_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/tarimas_listado.php',
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
                    data: 'tar_id'
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
                    data: 'niv_id',
                    render: function(data, type, row) {
                        return data ? `${row.rac_descripcion} : ${row.niv_codigo}` : 'Sin Posición';
                    }
                },
                {
                    data: 'usu_nombre'
                },
                {
                    data: 'tar_fecha'
                },
                {
                    data: 'tar_rendimiento',
                    width: "30px",
                    render: function(data, type, row) {

                        if (permisos.editar) {
                            if (data === null && !proIdMapping.hasOwnProperty(row.pro_id)) {
                                if (row.lote_estatus === '3') {
                                    return `<a href="#"><i class="btn-rendimiento fa-solid fa-calculator" data-pro="${row.pro_id}" data-folio="${row.tar_folio}" data-tar="${row.tar_id}" data-pro2="${row.pro_id_2}"></i></a>`;
                                } else if (row.lote_estatus === '2') {
                                    return '<a href="#" style="pointer-events: none; color: gray"><i class="btn-rendimiento fa-solid fa-calculator" disabled></i></a>';
                                }
                            } else {
                                let resultado = (data * 100).toFixed(2);
                                if (proIdMapping.hasOwnProperty(row.pro_id)) resultado = 0;
                                return `<span>${resultado}%</span>`;
                            }
                        }
                        return '';
                    },
                    visible: permisos.editar
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (permisos.editar) {
                            if (row.tar_estatus === '0' || proIdMapping.hasOwnProperty(row.pro_id)) {
                                return `<a href="#"><i class="btn-parametros fa-regular fa-pen-to-square" data-tar="${row.tar_id}"></i></a>`;
                            } else {
                                return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Tarima en almacén"><i class="fa-regular fa-pen-to-square"></i></a>';
                            }
                        }
                        return '';
                    },
                    visible: permisos.editar
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (permisos.listar) {
                            return `<a href="#"><i class="btn-qr fa-solid fa-qrcode" data-tar="${row.tar_id}"></i></a>`;
                        }
                        return '';
                    }
                },
                {
                    data: 'tar_rechazado',
                    render: function(data, type, row) {
                        if (data === null) {
                            return '<span>Por capturar</span>';
                        } else {
                            if (data === 'C') {
                                return '<span>Cuarentena</span>';
                            } else if (data === 'R') {
                                return '<span style="color: red">RECHAZADA</span>';
                            } else {
                                return '<span>Aceptada</span>';
                            }
                        }
                    }
                },
                {
                    data: 'cal_descripcion'
                },
                {
                    data: 'tar_estatus',
                    render: function(data, type, row) {
                        const privilegio = <?php echo $_SESSION['privilegio']; ?>;
                        return renderEstatus(data, row, privilegio);
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (row.tar_rechazado === 'R') {
                            return '<a href="#" style="color: red" data-bs-toggle="tooltip" data-bs-placement="top" title="Tarima Rechazada"><i class="fa-solid fa-triangle-exclamation"></i></a>';
                        } else if (permisos.editar && row.tar_estatus === '0' && row.cal_id && row.cal_id !== '0') {
                            return '<a href="#"><i class="btn-enviar fa-solid fa-warehouse" data-tar="' + row.tar_id + '"></i></a>';
                        }

                        return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Faltan parametros"><i class="fa-solid fa-warehouse"></i></a>';
                    },
                    visible: permisos.editar
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (permisos.calidad) {
                            return '<a href="#" style="color: red"><i class="btn-cambiar-estatus fa-solid fa-triangle-exclamation" data-tar="' + row.tar_id + '"></i></a>';
                        } else if (row.tar_rechazado === 'R') {
                            return '<a href="#" style="color: red" data-bs-toggle="tooltip" data-bs-placement="top" title="Tarima Rechazada"><i class="fa-solid fa-triangle-exclamation"></i></a>';
                        }
                        return '';
                    },
                    visible: permisos.calidad
                }
            ]
        });


        $('#dataTableTarimas').on('click', '.btn-move', function() {
            let rac_id = $(this).data('rac');
            let niv_id = $(this).data('niv');
            let tar_id = $(this).data('tar');
            let pro_id = $(this).data('pro');
            let tar_folio = $(this).data('folio');
            abrir_modal_mover(niv_id, rac_id, tar_id, pro_id, tar_folio);
        });

        $('#dataTableTarimas').on('click', '.btn-rendimiento', function() {
            let pro_id = $(this).data('pro');
            let tar_folio = $(this).data('folio');
            let tar_id = $(this).data('tar');
            let pro_id_2 = $(this).data('pro2');

            if (pro_id_2) {
                abrir_modal_rendimiento_proceso2(pro_id, pro_id_2, tar_folio, tar_id);
                console.log(pro_id_2);
            } else {
                abrir_modal_rendimiento(pro_id, tar_folio, tar_id);
            }
        });


        $('#dataTableTarimas').on('click', '.btn-parametros', function() {
            let tar_id = $(this).data('tar');
            abrir_modal_parametros(tar_id);
        });


        $('#dataTableTarimas').on('click', '.btn-enviar', function() {
            let tar_id = $(this).data('tar');
            enviar_almacen(tar_id);
        });

        $('#dataTableTarimas').on('click', '.btn-cambiar-estatus', function() {
            let tar_id = $(this).data('tar');
            autorizar_cambio_estatus(tar_id);
        });

    });

    // Función para renderizar según el estado y privilegios
    function renderEstatus(data, row, privilegio) {
        const {
            tar_estatus,
            cal_id,
            tar_id
        } = row;

        switch (tar_estatus) {
            case '0': // Registro inicial
                /* if ([20, 1, 2].includes(privilegio)) {
                    if (cal_id && cal_id !== '0') {
                        // Parámetros capturados: enlace funcional
                        return `<a href="#"><i class="btn-enviar fa-solid fa-warehouse" data-tar="${tar_id}"></i></a>`;
                    } else {
                        // Falta capturar parámetros
                        return `<a href="#" style="color: gray" data-bs-toggle="tooltip" title="Falta capturar parámetros">
                                <i class="fa-solid fa-warehouse" data-tar="${tar_id}"></i>
                            </a>`;
                    }
                } else {
                    // Sin privilegios: ícono desactivado
                    return `<a href="#" style="color: gray"><i class="fa-solid fa-warehouse"></i></a>`;
                } */
            case '0':
                return "En proceso";

            case '1': // En almacén
                return "En almacén";

            case '2': // Tomado para revoltura
                return "Por revolver";

            case '3': // En proceso de revoltura
                return "Control revolturas";

            case '4': // Tomado para mezcla
                return "Por mezclar";

            case '5': // En proceso de mezcla
                return "Control mezclas";

            case '6': // Empacado
                return "Empacado";

            default: // Estado desconocido o no mapeado
                return "Enviado";
        }
    }


    function determinarCalidad() {
        let tar_bloom = $('#tar_bloom').val();
        let tar_viscosidad = $('#tar_viscosidad').val();

        let dataForm = {
            'tar_bloom': tar_bloom,
            'tar_viscosidad': tar_viscosidad
        }

        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_determinar_calidad.php',
            data: dataForm,
            success: function(response) {
                let res = JSON.parse(response);

                $('#cal_descripcion').val(res.calidad);
                $('#cal_id').val(res.cal_id);
            }
        });
    }

    $('#dataTableTarimas').on('click', '.btn-qr', function() {
        let tar_id = $(this).data('tar');

        // Mostrar el Swal de confirmación antes de llamar a la función
        Swal.fire({
            title: "¿Seguro que deseas imprimir la etiqueta?",
            text: '',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            // Si el usuario confirma, llamar a la función para generar el QR
            if (result.isConfirmed) {
                generarQRListado(tar_id); // Ejecutamos la función que gestiona la generación del QR
            } else {
                Swal.fire({
                    title: "Operación cancelada",
                    text: "No se generó el código QR",
                    icon: "info"
                });
            }
        });
    });

    function generarQRListado(tar_id) {
        // Mostrar el Swal de carga antes de hacer la solicitud
        Swal.fire({
            title: 'Generando etiqueta...',
            text: 'Por favor espera mientras se genera el código QR.',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Llamada AJAX para generar el QR
        $.ajax({
            type: 'GET',
            url: 'funciones/tarimas_generar_qr.php',
            data: {
                tar_id: tar_id,
                opcion: 1
            },
            cache: false, // Evitar caché
            headers: {
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            success: function(response) {
                let res = JSON.parse(response);

                // Cerrar el Swal de carga
                Swal.close();

                if (res.success) {
                    // Mostrar el Swal de éxito si la respuesta es positiva
                    Swal.fire({
                        title: "Enviado!",
                        text: `${res.success}`,
                        icon: "success"
                    });
                } else {
                    // Mostrar el Swal de error si la respuesta es negativa
                    Swal.fire({
                        title: "Ocurrio un error!",
                        text: `${res.error}`,
                        icon: "error"
                    });
                }
            },
            error: function(xhr, status, error) {
                // En caso de error en la solicitud AJAX, mostramos un Swal de error
                Swal.close(); // Cerrar el Swal de carga
                Swal.fire({
                    title: "Error en la solicitud!",
                    text: `Error: ${error}`,
                    icon: "error"
                });
            }
        });
    }

    // Función principal para renderizar el contenido de tar_rendimiento
    function renderTarRendimiento(data, row, tienePermisoEditar) {
        if (!tienePermisoEditar) return ''; // Si no tiene permisos, devuelve vacío

        if (data === null && row.pro_id !== '1') {
            return renderIconoCalculadora(row); // Renderiza el ícono de calculadora
        } else {
            return renderResultado(data, row); // Renderiza el resultado del rendimiento
        }
    }

    // Función para renderizar el ícono de la calculadora
    function renderIconoCalculadora(row) {
        if (row.lote_estatus === '3') {
            return `<a href="#">
                    <i class="btn-rendimiento fa-solid fa-calculator" 
                       data-pro="${row.pro_id}" 
                       data-folio="${row.tar_folio}" 
                       data-tar="${row.tar_id}" 
                       data-pro2="${row.pro_id_2}">
                    </i>
                </a>`;
        } else if (row.lote_estatus === '2') {
            return '<a href="#" style="pointer-events: none; color: gray">' +
                '<i class="btn-rendimiento fa-solid fa-calculator" disabled></i>' +
                '</a>';
        }
        return ''; // En caso de que no coincida con ningún estatus
    }

    // Función para renderizar el resultado del rendimiento
    function renderResultado(data, row) {
        const proIdMapping = {
            '1': 'FINOSB',
            '2': 'BARREDURA',
            '3': 'CHURRO'
        };

        let resultado = (data * 100).toFixed(2); // Calcula el rendimiento
        if (proIdMapping.hasOwnProperty(row.pro_id)) {
            resultado = 0; // Si el pro_id está en el mapeo, el resultado es 0
        }
        return `<span>${resultado}%</span>`;
    }
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Registro de tarimas</li>
                </ol>
            </nav>
        </div>
        <div>
            <div style="width: 20px; height: 20px; background-color: #d1e7dd; vertical-align: middle; display: inline-block; border: 1px solid #000;"></div>
            <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Es fino y tiene menos de 1000 kilos</span>
            <div style="width: 20px; height: 20px; background-color: #fffcda; vertical-align: middle; display:inline-block; border: 1px solid #000;"></div>
            <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Es fino</span>
            <div style="width: 20px; height: 20px; background-color: #DCDCDC; vertical-align: middle; display:inline-block; border: 1px solid #000;"></div>
            <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Tiene menos de 1000 kilos</span>
        </div>
        <div class="row justify-content-end">
            <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_agregar') == 1 || $_SESSION['privilegio'] == 22) { ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_rack_insertar" onclick="abrir_modal_tarimas()">
                        <i class="fa fa-plus"></i> Agregar Tarimas
                    </button>
                </div>
            <?php } ?>
        </div>

    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableTarimas" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Proceso</th>
                        <th>Folio</th>
                        <th>Kilos</th>
                        <th>Posición</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Rendimiento</th>
                        <th>Parametros</th>
                        <th>Código QR</th>
                        <th>Estatus calidad</th>
                        <th>Calidad</th>
                        <th>Estatus tarima</th>
                        <th>Enviar almacen</th>
                        <th>Rechazar</th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_tarimas_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_tarimas_mover" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_tarimas_rendimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_tarimas_parametros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_agrupar_procesos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_tarimas() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_modal_insertar.php',
            success: function(result) {
                $('#modal_tarimas_insertar').html(result);
                $('#modal_tarimas_insertar').modal('show');
            }
        });
    }

    function abrir_modal_agrupar_procesos() {
        $('#modal_tarimas_insertar').modal('hide');
        $.ajax({
            type: 'GET',
            url: 'administrador/agrupar_procesos_modal.php',
            success: function(data) {
                $('#modal_agrupar_procesos').html(data);
                $('#modal_agrupar_procesos').modal('show');
            }
        })
    }

    function abrir_modal_mover(niv_id, rac_id, tar_id, pro_id, tar_folio) {
        let dataForm = {
            'niv_id': niv_id,
            'rac_id': rac_id,
            'tar_id': tar_id,
            'pro_id': pro_id,
            'tar_folio': tar_folio
        }


        console.log(dataForm)
        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_mover_posicion.php',
            success: function(result) {
                $('#modal_tarimas_mover').html(result);
                $('#modal_tarimas_mover').modal('show');
            }
        });
    }

    function abrir_modal_rendimiento(pro_id, tar_folio, tar_id) {
        let dataForm = {
            'pro_id': pro_id,
            'tar_folio': tar_folio,
            'tar_id': tar_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_rendimiento.php',
            success: function(result) {
                $('#modal_tarimas_rendimiento').html(result);
                $('#modal_tarimas_rendimiento').modal('show');
            }
        });
    }

    function abrir_modal_rendimiento_proceso2(pro_id, pro_id_2, tar_folio, tar_id) {
        let dataForm = {
            'pro_id': pro_id,
            'pro_id_2': pro_id_2,
            'tar_folio': tar_folio,
            'tar_id': tar_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_rendimiento_proceso2.php',
            success: function(result) {
                $('#modal_tarimas_rendimiento').html(result);
                $('#modal_tarimas_rendimiento').modal('show');
            }
        });
    }

    function abrir_modal_parametros(tar_id) {
        let dataForm = {
            'tar_id': tar_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_parametros.php',
            success: function(result) {
                $('#modal_tarimas_parametros').html(result);
                $('#modal_tarimas_parametros').modal('show');
            }
        });
    }


    function enviar_almacen(id) {
        let dataForm = {
            "tar_id": id
        };

        Swal.fire({
            title: "¿Seguro que deseas enviar a almacén?",
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
                    url: 'funciones/tarimas_enviar_almacen.php',
                    data: dataForm,
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Enviado!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            $('#dataTableTarimas').DataTable().ajax.reload();
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
    }

    async function autorizar_cambio_estatus(tar_id) {
        const {
            value: clave
        } = await Swal.fire({
            title: "Ingresa tu clave de autorización para marcarla como Rechazada",
            input: "password",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Autorizar",
            cancelButtonText: "Cancelar",
            showLoaderOnConfirm: true,
            preConfirm: async (clave) => {
                if (!clave) {
                    Swal.showValidationMessage("Por favor ingresa una clave de autorización.");
                    return false;
                }

                try {
                    let response = await fetch("administrador/autorizacion_clave.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `usu_clave_auth=${encodeURIComponent(clave)}`
                    });

                    if (!response.ok) throw new Error("Error de conexión con el servidor");

                    let data = await response.json();

                    if (!data.success) {
                        throw new Error(data.error || "Error en la validación de la clave");
                    }

                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (!clave) return; // Si la clave no fue ingresada o validada, salir

        try {
            let response = await fetch("funciones/tarimas_cambiar_estatus.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `tar_id=${encodeURIComponent(tar_id)}`
            });

            if (!response.ok) throw new Error("Error al cambiar el estado de la tarima");

            let res = await response.json();

            if (res.success) {
                $('#dataTableTarimas').DataTable().ajax.reload();
                await imprimirEtiquetaRechazada(tar_id);
            } else {
                Swal.fire({
                    title: "Clave autorizada",
                    text: res.error,
                    icon: "error"
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                title: "Error",
                text: "Hubo un problema al procesar la solicitud",
                icon: "error"
            });
        }
    }

    async function imprimirEtiquetaRechazada(tar_id) {
        console.info(tar_id);
        try {
            let response = await fetch('funciones/tarimas_generar_qr_rechazado.php', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `tar_id=${encodeURIComponent(tar_id)}`
            });

            if (!response.ok) throw new Error("Error al generar la etiqueta");

            let res = await response.json();

            Swal.fire({
                title: res.success ? "Etiqueta generada!" : "Ocurrió un error!",
                text: res.success || res.error,
                icon: res.success ? "success" : "error"
            });
        } catch (error) {
            console.error(error);
            Swal.fire({
                title: "Error",
                text: "No se pudo generar la etiqueta",
                icon: "error"
            });
        }
    }


    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>