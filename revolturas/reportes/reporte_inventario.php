<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto terminado (sin empacar)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../../js/jquery.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                /* Especificar tamaño de la hoja */
                margin: 15mm 10mm 25mm 10mm;
                /* Márgenes: superior, derecho, inferior, izquierdo */

                @top-right {
                    content: "Página " counter(page) " de " counter(pages);
                    font-size: 11px;
                }
            }

            body {
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
                counter-reset: page;
                margin: 0;
            }

            .container {
                max-width: 210mm;
                margin: auto;
                padding: 10px;
            }

            h3 {
                font-size: 11px;
                text-align: center;
                margin-bottom: 3px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8px;
                max-width: 190mm;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 0px;
                text-align: center;
                word-wrap: break-word;
                white-space: normal;
                line-height: 1.2;
                max-width: 82px;
            }

            th {
                background-color: #f2f2f2;
                font-size: 8px;
                white-space: normal;
            }

            .table-container {
                transform: scale(0.95);
                /* Escala la tabla al 95% de su tamaño */
                transform-origin: top left;
                /* Asegura que la escala comience desde la esquina superior izquierda */
                width: 100%;
            }

            thead {
                display: table-header-group;
                /* Asegura que los encabezados se repitan si la tabla se corta */
            }


            tbody {
                display: table-row-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            tfoot {
                display: table-row-group;
            }

            #export {
                display: none;
            }
        }
    </style>


</head>

<body>
    <div class="container-fluid">
        <div class="row align-items-center p-3">
            <div class="col-md-2 text-center">
                <img src="../../imagenes/logo_progel_v3.png" alt="Logo Progel" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-md-10 text-center">
                <h2 class="fw-bold m-0">Producto terminado (sin empacar)</h2>
                <span id="fecha"></span>
            </div>

            <div class="col-md-4 mt-2">
                <button id="export" class="btn btn-success btn-sm" onclick="exportTablesToExcel()"><i class="fa-solid fa-file-excel"></i> Exportar a Excel</button>
                <button id="export" class="btn btn-success btn-sm" onclick="exportTarimasDisponibles()"><i class="fa-solid fa-file-excel"></i> Exportar Tarimas disponibles</button>
                <form id="exportForm" method="POST" action="reporte_inventario.controller.php" style="display: none;">
                    <input type="hidden" name="action" value="tarimas_disponibles_excel">
                </form>
            </div>

        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Pesada del día</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-pesada-dia">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="15" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="2" id="total-kilos-pesada-dia" class="fw-bold" style="font-size: 20px">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class=" container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Tarimas en proceso de analisis</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-proceso-analisis">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="15" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="2" class="fw-bold" style="font-size: 20px" id="total-proceso-analisis">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class=" container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Tarimas pendientes de enviar almacen</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-pendiente-enviar-almacen">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="15" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="2" class="fw-bold" style="font-size: 20px" id="total-pendiente-enviar-almacen">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Revolturas terminadas</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="revolturas-terminadas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Revoltura</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Bloom teorico</th>
                                <th>Visc teorica</th>
                                <th>Calidad teorica</th>
                                <th>Cliente teorico</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="17" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="2" class="fw-bold" style="font-size: 20px" id="total-kilos-revolturas"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">En revolvedora</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-revolvedora">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                                <th>Revoltura</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="16" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="2" class="fw-bold" style="font-size: 20px" id="total-kilos-revolvedora">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Revolturas del Día</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="revolturas-dia">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Revoltura</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Bloom teorico</th>
                                <th>Visc teorica</th>
                                <th>Calidad teorica</th>
                                <th>Cliente teorico</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="17" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="2" class="fw-bold" style="font-size: 20px" id="total-kilos-revolturas-dia">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Tarimas disponibles</h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-disponibles">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="14" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                <td colspan="3" class="fw-bold" style="font-size: 20px" id="total-kilos-disponibles">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <h2 style="color: #007bff;">Total sin empacar: <span id="total-kilos"></span></h3>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="../../assets/fontawesome/fontawesome.js"></script>
<script>
    function exportTablesToExcel() {
        const printAreas = document.querySelectorAll('.print-area');
        const workbook = XLSX.utils.book_new();

        printAreas.forEach((printArea) => {
            const title = printArea.querySelector('h3').innerText;
            const table = printArea.querySelector('table');
            const clonedTable = table.cloneNode(true);

            const titleRow = document.createElement('tr');
            const titleCell = document.createElement('th');
            titleCell.colSpan = table.querySelectorAll('th').length;
            titleCell.innerText = title;
            titleRow.appendChild(titleCell);

            clonedTable.querySelector('thead').insertBefore(titleRow, clonedTable.querySelector('thead').firstChild);

            const worksheet = XLSX.utils.table_to_sheet(clonedTable);
            XLSX.utils.book_append_sheet(workbook, worksheet, `Hoja ${workbook.SheetNames.length + 1}`);
        });

        XLSX.writeFile(workbook, 'Reporte.xlsx');
    }

    const formatter = new Intl.NumberFormat('en-US');


    $(document).ready(function() {
        const fecha = new Date();
        $('#fecha').text(fecha.toLocaleDateString('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }));

        let totalGlobalKilos = 0;

        Promise.all([
            cargarTarimasPesadaDia(),
            cargarTarimasProcesoAnalisis(),
            cargarTarimasPendienteAlmacen(),
            cargarRevolturasTerminadas(),
            cargarTarimasRevolvedora(),
            cargarRevolturasDia(),
            cargarTarimasDisponibles(),
        ]).then(totales => {
            totalGlobalKilos = totales.reduce((acc, total) => acc + total, 0);
            $('#total-kilos').text(formatter.format(totalGlobalKilos));
        }).catch(error => {
            console.error('Error al cargar los datos:', error);
        });
    });

    function cargarTarimasPesadaDia() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#tarimas-pesada-dia').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'tarimas_pesada_dia'
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((tarima, index) => {
                        let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;

                        // Definir el color de fondo según la condición
                        let finos = (tarima.tar_fino === 'F') ? true : false;

                        $tbody.append(`
                    <tr">
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${index + 1}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >P${pro_id}T${tarima.tar_folio}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_fecha}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_bloom ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_viscosidad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_ph ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_humedad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_cenizas ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_trans ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_color ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_30 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_45 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_pe_1kg ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_par_extr ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_redox ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.cal_descripcion ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);

                        totalKilos += Number(tarima.tar_kilos);
                    });

                    $('#total-kilos-pesada-dia').text(formatter.format(totalKilos));
                    resolve(totalKilos);
                },
                error: function() {
                    alert('Error al cargar las tarimas.');
                    reject('Error al cargar las tarimas.');
                }
            });
        });
    }

    function cargarTarimasProcesoAnalisis() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#tarimas-proceso-analisis').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'tarimas_proceso_analisis'
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((tarima, index) => {
                        let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;

                        // Definir el color de fondo según la condición
                        let finos = (tarima.tar_fino === 'F') ? true : false;
                        console.log(finos);
                        $tbody.append(`
                    <tr">
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${index + 1}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >P${pro_id}T${tarima.tar_folio}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_fecha}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_bloom ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_viscosidad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_ph ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_humedad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_cenizas ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_trans ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_color ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_30 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_45 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_pe_1kg ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_par_extr ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_redox ?? ''}</td>
                        <td style="background-color:${tarima.cal_color  ?? ''}; color: #FFFFFF">${tarima.cal_descripcion ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);

                        totalKilos += Number(tarima.tar_kilos);
                    });
                    resolve(totalKilos);
                    //tarimas-pendiente-enviar-almacen
                    $('#total-proceso-analisis').text(formatter.format(totalKilos));
                },
                error: function() {
                    alert('Error al cargar las tarimas.');
                    reject('Error al cargar las tarimas.');
                }
            });
        });
    }

    function cargarTarimasPendienteAlmacen() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#tarimas-pendiente-enviar-almacen').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'tarimas_pendiente_enviar_almacen'
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((tarima, index) => {
                        let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;

                        // Definir el color de fondo según la condición
                        let finos = (tarima.tar_fino === 'F') ? true : false;
                        console.log(finos);
                        $tbody.append(`
                    <tr">
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${index + 1}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >P${pro_id}T${tarima.tar_folio}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_fecha}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_bloom ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_viscosidad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_ph ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_humedad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_cenizas ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_trans ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_color ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_30 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_45 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_pe_1kg ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_par_extr ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_redox ?? ''}</td>
                        <td style="background-color:${tarima.cal_color  ?? ''}; color: #FFFFFF">${tarima.cal_descripcion ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);

                        totalKilos += Number(tarima.tar_kilos);
                    });
                    resolve(totalKilos);
                    //tarimas-pendiente-enviar-almacen
                    $('#total-pendiente-enviar-almacen').text(formatter.format(totalKilos));
                },
                error: function() {
                    alert('Error al cargar las tarimas.');
                    reject('Error al cargar las tarimas.');
                }
            });
        });
    }

    function cargarRevolturasTerminadas() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#revolturas-terminadas').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'revolturas_terminadas'
                },
                success: function(response) {
                    const data = JSON.parse(response);

                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((revoltura, index) => {
                        $tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${revoltura.rev_folio}</td>
                            <td>${revoltura.rev_fecha}</td>
                            <td>${revoltura.rev_bloom ?? ''}</td>
                            <td>${revoltura.rev_viscosidad  ?? ''}</td>
                            <td>${revoltura.rev_ph  ?? ''}</td>
                            <td>${revoltura.rev_humedad  ?? ''}</td>
                            <td>${revoltura.rev_cenizas  ?? ''}</td>
                            <td>${revoltura.rev_trans  ?? ''}</td>
                            <td>${revoltura.rev_color  ?? ''}</td>
                            <td>${revoltura.rev_malla_30  ?? ''}</td>
                            <td>${revoltura.rev_malla_45  ?? ''}</td>
                            <td>${revoltura.rev_redox  ?? ''}</td>
                            <td style="background-color:${revoltura.cal_color  ?? ''}; color: #FFFFFF">${revoltura.cal_descripcion  ?? ''}</td>
                            <td>${revoltura.rev_teo_bloom  ?? ''}</td>
                            <td>${revoltura.rev_teo_viscosidad  ?? ''}</td>
                            <td style="background-color:${revoltura.rev_teo_color_cal  ?? ''}; color: #FFFFFF">${revoltura.rev_teo_calidad  ?? ''}</td>
                            <td>${revoltura.rev_teo_cliente  ?? ''}</td>
                            <td>${revoltura.rev_kilos  ?? ''}</td>
                        </tr>
                    `);
                        totalKilos += Number(revoltura.rev_kilos);
                    });

                    $('#total-kilos-revolturas').text(formatter.format(totalKilos));
                    resolve(totalKilos);
                },
                error: function() {
                    alert('Error al cargar las revolturas.');
                    reject('Error al cargar las revolturas.');
                }
            });
        });
    }

    function cargarTarimasRevolvedora() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#tarimas-revolvedora').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'tarimas_revolvedora'
                },
                success: function(response) {
                    const data = JSON.parse(response);

                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((tarima, index) => {
                        let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                        let finos = (tarima.tar_fino === 'F') ? true : false;
                        $tbody.append(`
                        <tr>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${index + 1}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">P${pro_id}T${tarima.tar_folio}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_fecha}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_bloom  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_viscosidad  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_ph  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_humedad  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_cenizas  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_trans  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_color  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_malla_30  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_malla_45  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_pe_1kg  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_par_extr  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_redox  ?? ''}</td>
                            <td style="background-color:${tarima.cal_color  ?? ''}; color: #FFFFFF">${tarima.cal_descripcion  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_kilos  ?? ''}</td>
                            <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.rev_folio  ?? ''}</td>
                        </tr>
                    `);
                        totalKilos += Number(tarima.tar_kilos);
                    });

                    $('#total-kilos-revolvedora').text(formatter.format(totalKilos));
                    resolve(totalKilos);
                },
                error: function() {
                    alert('Error al cargar las tarimas.');
                    reject('Error al cargar las tarimas.');
                }
            });
        });
    }

    function cargarRevolturasDia() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#revolturas-dia').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'revolturas_dia'
                },
                success: function(response) {
                    const data = JSON.parse(response);

                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((revoltura, index) => {
                        $tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${revoltura.rev_folio}</td>
                            <td>${revoltura.rev_fecha}</td>
                            <td>${revoltura.rev_bloom  ?? ''}</td>
                            <td>${revoltura.rev_viscosidad  ?? ''}</td>
                            <td>${revoltura.rev_ph  ?? ''}</td>
                            <td>${revoltura.rev_humedad  ?? ''}</td>
                            <td>${revoltura.rev_cenizas  ?? ''}</td>
                            <td>${revoltura.rev_trans  ?? ''}</td>
                            <td>${revoltura.rev_color  ?? ''}</td>
                            <td>${revoltura.rev_malla_30  ?? ''}</td>
                            <td>${revoltura.rev_malla_45  ?? ''}</td>
                            <td>${revoltura.rev_redox  ?? ''}</td>
                            <td style="background-color:${revoltura.cal_color  ?? ''}; color: #FFFFFF">${revoltura.cal_descripcion  ?? ''}</td>
                            <td>${revoltura.rev_teo_bloom  ?? ''}</td>
                            <td>${revoltura.rev_teo_viscosidad  ?? ''}</td>
                            <td style="background-color:${revoltura.rev_teo_color_cal  ?? ''}; color: #FFFFFF">${revoltura.rev_teo_calidad  ?? ''}</td>
                            <td>${revoltura.rev_teo_cliente  ?? ''}</td>
                            <td>${revoltura.rev_kilos  ?? ''}</td>
                        </tr>
                    `);
                        totalKilos += Number(revoltura.rev_kilos);
                    });

                    $('#total-kilos-revolturas-dia').text(formatter.format(totalKilos));
                    resolve(totalKilos);
                },
                error: function() {
                    alert('Error al cargar las revolturas.');
                    reject('Error al cargar las revolturas.');
                }
            });
        });
    }

    function cargarTarimasDisponibles() {
        return new Promise((resolve, reject) => {
            const $tbody = $('#tarimas-disponibles').find('tbody');
            let totalKilos = 0;

            $.ajax({
                type: 'POST',
                url: 'reporte_inventario.controller.php',
                data: {
                    action: 'tarimas_disponibles'
                },
                success: function(response) {
                    const data = JSON.parse(response);

                    if (data.error) {
                        alert('Error: ' + data.message);
                        reject(data.message);
                        return;
                    }

                    $tbody.empty();
                    data.forEach((tarima, index) => {
                        let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;

                        // Definir el color de fondo según la condición
                        let finos = (tarima.tar_fino === 'F') ? true : false;
                        console.log(finos);
                        $tbody.append(`
                    <tr">
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${index + 1}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >P${pro_id}T${tarima.tar_folio}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_fecha}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_bloom ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_viscosidad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_ph ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_humedad ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_cenizas ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_trans ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_color ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_30 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_malla_45 ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_pe_1kg ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_par_extr ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};" >${tarima.tar_redox ?? ''}</td>
                        <td style="background-color:${tarima.cal_color ?? ''}; color: #FFFFFF">${tarima.cal_descripcion ?? ''}</td>
                        <td style="background-color: ${finos ? '#FFFCDA' : ''};">${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);

                        totalKilos += Number(tarima.tar_kilos);
                    });
                    $('#total-kilos-disponibles').text(formatter.format(totalKilos));
                    resolve(totalKilos);
                },
                error: function() {
                    alert('Error al cargar las tarimas.');
                    reject('Error al cargar las tarimas.');
                }
            });
        });
    }

    function exportTarimasDisponibles() {
        const form = document.getElementById('exportForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                })
                .then(response => {
                    if (response.ok) {
                        return response.blob(); // Manejar la respuesta como un archivo
                    } else {
                        return response.json(); // Manejar la respuesta como JSON en caso de error
                    }
                })
                .then(data => {
                    if (data instanceof Blob) {
                        // Crear un enlace temporal para descargar el archivo
                        const url = window.URL.createObjectURL(data);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'Tarimas_disponibles.csv';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    } else {
                        // Mostrar el mensaje de error
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al descargar el archivo.');
                });
        });

        form.submit(); // Enviar el formulario
    }
</script>

</html>