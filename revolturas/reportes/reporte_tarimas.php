<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../funciones/tarimas_validacion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte diario de lotes y revoturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../../js/jquery.min.js"></script>

    <!--DATATABLES-->
    <script src="../../assets/datatable/jquery.dataTables.min.js"></script>
    <script src="../../assets/datatable/dataTables.bootstrap5.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Buttons -->
    <link rel="stylesheet" href="../../assets/datatable/buttons.dataTables.min.css">
    <script src="../../assets/datatable/dataTables.buttons.min.js"></script>
    <script src="../../assets/datatable/jszip.min.js"></script>
    <script src="../../assets/datatable/pdfmake.min.js"></script>
    <script src="../../assets/datatable/vfs_fonts.js"></script>
    <script src="../../assets/datatable/buttons.html5.min.js"></script>
    <script src="../../assets/datatable/buttons.print.min.js"></script>
    <script src="../../assets/datatable/buttons.colVis.min.js"></script>
    <script src="../../assets/datatable/ellipsis.js"></script>

    <link href="../../assets/sweetalert/sweetalert.css" rel="stylesheet" />
    <script src="../../assets/sweetalert/sweetalert.js"></script>
    <script src="../../assets/sweetalert/sweetalert2.js"></script>

    <script src="../../assets/fontawesome/fontawesome.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->

    <?php
    date_default_timezone_set('America/Mexico_City');
    ?>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
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
                            className: 'btn'
                        },
                    },
                    buttons: [{
                            //Botón para Excel
                            extend: 'excel',
                            footer: true,
                            title: 'REPORTE DIARIO DE LOTES Y REVOLTURAS',
                            filename: 'Reporte_diario_lotes_revolturas_excel',

                            //Aquí es donde generas el botón personalizado
                            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
                                format: {
                                    body: function(data, row, column, node) {
                                        // Eliminar etiquetas HTML
                                        return $('<div>').html(data).text();
                                        return data.replace(/<br\s*\/?>/g, '\n');
                                    }
                                }
                            }
                        },
                        {
                            // Botón para PDF
                            extend: 'pdfHtml5',
                            footer: true,
                            title: 'REPORTE DIARIO DE LOTES Y REVOLTURAS',
                            filename: 'Reporte_diario_lotes_revolturas_pdf',
                            text: '<button title="Exportar PDF" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22]
                            },
                            customize: function(doc) {
                                // Configuración adicional para ajustar el diseño del PDF
                                doc.content[1].table.widths = Array(23).fill('*'); // Ajustar el ancho de las columnas
                                doc.styles.tableHeader.fontSize = 5; // Tamaño de la fuente del encabezado de la tabla
                                doc.styles.tableBodyOdd.fontSize = 6; // Tamaño de la fuente del cuerpo de la tabla (filas impares)
                                doc.styles.tableBodyEven.fontSize = 6; // Tamaño de la fuente del cuerpo de la tabla (filas pares)
                                doc.styles.tableBodyOdd.alignment = 'center'; // Alineación del texto en filas impares
                                doc.styles.tableBodyEven.alignment = 'center'; // Alineación del texto en filas pares
                                doc.pageMargins = [20, 30, 20, 30]; // Márgenes de la página [izquierda, arriba, derecha, abajo]

                                // Configurar el diseño de la tabla para agregar bordes
                                doc.content[1].layout = {
                                    fillColor: '#FFFFFF',
                                    hLineColor: '#000000',
                                    vLineColor: '#000000',
                                    hLineWidth: function(i, node) {
                                        return 1;
                                    },
                                    vLineWidth: function(i, node) {
                                        return 1;
                                    },
                                    paddingLeft: function(i, node) {
                                        return 4;
                                    },
                                    paddingRight: function(i, node) {
                                        return 4;
                                    },
                                    paddingTop: function(i, node) {
                                        return 2;
                                    },
                                    paddingBottom: function(i, node) {
                                        return 2;
                                    }
                                };
                            }
                        },
                        //Botón para print
                        {
                            extend: 'print',
                            footer: true,
                            title: 'REPORTE DIARIO DE LOTES Y REVOLTURAS',
                            filename: 'Reporte_diario_lotes_revolturas_print',
                            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22]
                            },
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            customize: function(win) {
                                $(win.document.head).append(
                                    '<style>' +
                                    '@page { margin-top: 60px; }' +
                                    'body { font-size: 10pt; margin-top: 200px; }' +
                                    'header { position: fixed; top: 0; left: 0; width: 100%; height: 100px; text-align: left; }' +
                                    'header img { width: 100px; margin-top: 20px; }' +
                                    '</style>'
                                );
                                $(win.document.body).prepend(
                                    '<header>' +
                                    '<table class="table table-bordered" style="width:100%;">' +
                                    '<tr>' +
                                    '<td><img src="../../imagenes/logo_progel_v3.png"></td>' +
                                    '<td style="text-align:center">REPORTE DIARIO DE LOTES Y REVOLTURAS</td>' +
                                    '<td style="text-align:center">LAB F009 - REV 004</td>' +
                                    '</tr>' +
                                    '</table>' +
                                    '</header>'
                                );
                            }
                        }
                    ]
                },
                ajax: {
                    url: 'reporte_tarimas_listado.php',
                    data: function(d) {
                        d.fecha_inicio = $('#fecha_inicio').val();
                        d.fecha_fin = $('#fecha_fin').val();
                    },
                    dataSrc: ''
                },

                columns: [{
                        data: 'tar_fecha'
                    },
                    {
                        render: function(data, type, row) {
                            let pro_id = (row.pro_id_2 == null) ? row.pro_id : `${row.pro_id}/${row.pro_id_2}`;
                            return `P${pro_id} T${row.tar_folio}`;
                        }
                    },
                    {
                        data: 'tar_bloom',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data < <?= $parametros['bloom']['rp_inicio'] ?> || data > <?= $parametros['bloom']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_viscosidad',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return (data < <?= $parametros['viscosidad']['rp_inicio'] ?> || data > <?= $parametros['viscosidad']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_ph',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return (data < <?= $parametros['ph']['rp_inicio'] ?> || data > <?= $parametros['ph']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_trans',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return (data < <?= $parametros['trans']['rp_inicio'] ?> || data > <?= $parametros['trans']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_porcentaje_t',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['por_t']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_ntu',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['ntu']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_humedad',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return (data < <?= $parametros['humedad']['rp_inicio'] ?> || data > <?= $parametros['humedad']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_cenizas',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['cenizas']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_ce',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['ce']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_redox',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['redox']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_color',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['color']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_malla_30',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            if(row.tar_fino == 'F'){
                                return `<span>${data}</span>`;
                            }
                            return (data < <?= $parametros['malla_30']['rp_inicio'] ?> || data > <?= $parametros['malla_30']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_malla_45',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            if(row.tar_fino == 'F'){
                                return `<span>${data}</span>`;
                            }
                            return (data < <?= $parametros['malla_45']['rp_inicio'] ?> || data > <?= $parametros['malla_45']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_olor',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data < <?= $parametros['olor']['rp_inicio'] ?> || data > <?= $parametros['olor']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_pe_1kg',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['pe_1kg']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_par_extr',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return (data < <?= $parametros['par_extr']['rp_inicio'] ?> || data > <?= $parametros['par_extr']['rp_fin'] ?>) ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_par_ind',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data > <?= $parametros['par_ind']['rp_fin'] ?> ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_hidratacion',
                        render: function(data, type, row) {
                            if (!data) {
                                return ''; // No muestra nada si el valor es nulo o vacío
                            }
                            return data === 'MAL' ? `<span style="color: red;">${data}</span>` : data;
                        }
                    },
                    {
                        data: 'tar_rechazado',
                        render: function(data, type, row) {
                            if (data === null) {
                                return '';
                            } else if (data === 'A') {
                                return `<span>X</span>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'tar_rechazado',
                        render: function(data, type, row) {
                            if (data === null) {
                                return '';
                            } else if (data === 'R' || data === 'C') {
                                return `<span style="color:red">X</span>`;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'cal_descripcion',
                        render: function(data, type, row) {
                            if (data != null) {
                                return '<div style="width: 50px; height: 50px; background-color:' + row.cal_color + '; display: flex; align-items: center; justify-content: center;">' + data + '</div>';
                            } else {
                                return '';
                            }
                        }
                    }
                ]

            });
            $('#filtrar').on('click', function() {
                $('#dataTable').DataTable().ajax.reload();
            });

        });
    </script>
</head>

<div class="container-fluid">
    <div class="row">
        <table id="encabezado" class="table table-bordered">
            <tr>
                <td><img src="../../imagenes/logo_progel_v3.png" alt=""></td>
                <td style="text-align:center">REPORTE DIARIO DE LOTES Y REVOLTURAS <span style="float:right"><?php echo date('H:i:s'); ?></span></td>
                <td style="text-align:center">LAB F009 - REV 004</td>
            </tr>
        </table>
    </div>
    <!-- tabla tarimas -->
    <form id="filtroFechas">
        <div class="row mb-3 d-flex align-items-end">
            <div class="col-md-7">
            </div>
            <div class="col-md-2">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
            </div>
            <div class="col-md-2">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
            </div>
            <div class="col-md-1" style="text-align: end;">
                <button type="button" class="btn btn-primary" id="filtrar">Filtrar</button>
            </div>
        </div>
    </form>
    <div class="row">
        <table class="table table-bordered" id="dataTable" style="width:auto">
            <thead style="background: #000">
                <tr>
                    <th>FECHA</th>
                    <th>LÍMITES DE PARAMETROS</th>
                    <th>BLOOM <br> MIN <?= $parametros['bloom']['rp_fin'] ?></th>
                    <th>VISC. <br> MIN. <?= $parametros['viscosidad']['rp_inicio'] ?>-<?= $parametros['viscosidad']['rp_fin'] ?> MAX.</th>
                    <th>PH FINAL <br> <?= $parametros['ph']['rp_inicio'] ?> - <?= $parametros['ph']['rp_fin'] ?></th>
                    <th>TRANS. <br> MIN <?= $parametros['trans']['rp_fin'] ?></th>
                    <th>%T (620) <br> <?= $parametros['por_t']['rp_fin'] ?>% MIN.</th>
                    <th>NTU <br> <?= $parametros['ntu']['rp_fin'] ?> MAX.</th>
                    <th>HUMEDAD <br> MIN. <?= $parametros['humedad']['rp_inicio'] ?>-<?= $parametros['humedad']['rp_fin'] ?>% MAX</th>
                    <th>CENIZAS <br> <?= $parametros['cenizas']['rp_fin'] ?>% MAX</th>
                    <th>CONDUCT. <br>
                        < <?= $parametros['ce']['rp_fin'] ?>mS </th>
                    <th>REDOX <br> <?= $parametros['redox']['rp_fin'] ?> PPM MAX</th>
                    <th>COLOR <br><?= $parametros['color']['rp_fin'] ?> MAX.</th>
                    <th>MALLA #30 <<?= $parametros['malla_30']['rp_fin'] ?>% </th>
                    <th>GRANO MALLA #45 <?= $parametros['malla_45']['rp_inicio'] ?>% MIN</th>
                    <!-- <th>FINO <br>
                        <= <?= $parametros['fino']['rp_fin'] ?>%</th> -->
                    <th>OLOR <br> SIN OLOR EXTRAÑO</th>
                    <th>P.E EN 1 KG <br> MAX <?= $parametros['pe_1kg']['rp_fin'] ?> PART-</th>
                    <th>PART. EXTRAÑAS <br> <?= $parametros['par_extr']['rp_inicio'] ?> - <?= $parametros['par_extr']['rp_fin'] ?> MAX.</th>
                    <th>PART. IND. 6,66% <br> MAXIMO <?= $parametros['par_ind']['rp_fin'] ?> GRANOS</th>
                    <th>HIDRATACIÓN <br> MAL-BIEN</th>
                    <th>ACEPTADO</th>
                    <th>RECHAZADO</th>
                    <th>CALIDAD</th>
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
        </table>
    </div>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>TABLA DE COLOR/OLOR</th>
                        <th>CAL./COLOR</th>
                        <th>CAL./OLOR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>EXCELENTE/SIN OLOR</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>MUY BIEN/CARÁCTERISTICO</td>
                        <td>1</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>BIEN/LIGERO</td>
                        <td>2</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>ACEPTABLE/ACENTUADO</td>
                        <td>3</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>MAL/MUY ACENTUADO</td>
                        <td>4</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>MUY MAL/INTENSO</td>
                        <td>5</td>
                        <td>5</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4" style="text-align:center;">
            <table class="table table-bordered" style="vertical-align:bottom">
                <tr style="height: 50px;">
                    <th>______________________________________</th>
                    <th>______________________________________</th>
                </tr>
                <tr>
                    <th>REVISO</th>
                    <th>AUTORIZO</th>
                </tr>
                <tr>
                    <th colspan="2">OBSERVACIONES</th>
                </tr>
                <tr>
                    <td style="height: 110px;" colspan="2"></td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>

</html>
<style>
    table:not(#encabezado) {
        font-size: 10px;
    }

    .table thead {
        --bs-table-bg: #F9F6F6;
    }

    img {
        width: 20%;
    }
</style>