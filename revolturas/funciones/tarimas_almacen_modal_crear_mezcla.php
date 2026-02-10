<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
if (isset($_POST['action']) && $_POST['action'] == 'obtener_consecutivo') {

    $sql = "SELECT LPAD((COUNT(mez_id)+1), 5, 0) AS total FROM rev_mezcla";
    $result = mysqli_query($cnx, $sql);
    $registros = mysqli_fetch_assoc($result);

    // El siguiente consecutivo es el total más uno
    $consecutivo = $registros['total'];

    echo json_encode(['consecutivo' => $consecutivo]);
    exit;
}
$fechaActual = date("Y-m-d");

if (isset($_POST['action']) && $_POST['action'] == 'obtener_tarimas') {
    $listado_tarimas = mysqli_query($cnx, "SELECT tr.pro_id, tr.tar_folio, tr.tar_id, 
    c.cal_descripcion, tr.tar_kilos,tr.tar_bloom, tr.tar_viscosidad FROM rev_tarimas tr 
    JOIN rev_calidad c ON c.cal_id = tr.cal_id WHERE tr.tar_estatus = 4");
    try {
        $datos_tarimas = array();

        while ($fila = mysqli_fetch_assoc($listado_tarimas)) {
            $datos_tarimas[] = $fila;
        }

        $json_tarimas = json_encode($datos_tarimas);

        echo $json_tarimas;
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>


<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Crear Mezcla</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_mezclas_crear" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="mez_folio" class="form-label">No. Mezcla</label>
                                <input type="text" class="form-control" name="mez_folio" id="mez_folio" readonly required>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label">Fecha</label>
                                <input type="text" name="" id="" class="form-control" readonly required value="<?= $fechaActual ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Proceso</th>
                                    <th>Tarima</th>
                                    <th>Kilos</th>
                                    <th>Bloom</th>
                                    <th>Viscosidad</th>
                                    <th>Calidad</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                    <!-- <span id="total"></span> -->
                    <input type="text" id="total_kilos"  class="d-none" name="total_kilos">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-mezcla" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_mezclas_crear" type="submit" class="btn btn-primary ms-2" id="guardar">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        actualizarConsecutivo();
        obtenerTarimasTabla();
        $("#form_mezclas_crear").submit(function(e) {
            e.preventDefault();
            obtenerTarimas()
                .then(tarimas => {
                    let dataForm = {
                        'mez_folio': $('#mez_folio').val(),
                        'tarimas': tarimas,
                        'mez_kilos': $('#total_kilos').val(),
                    }

                    $.ajax({
                        type: 'POST',
                        url: 'funciones/mezclas_crear.php',
                        data: dataForm,
                        success: function(data) {
                            let res = JSON.parse(data);
                            if (res.success) {
                                alertas_v5("#alerta-mezcla", 'Listo!', res.success, 1, true, 5000);
                                console.log(res.success);
                                obtenerTarimasTabla();
                                actualizarConsecutivo();
                            } else {
                                alertas_v5("#alerta-mezcla", 'Error!', res.error, 3, true, 5000);
                                console.log(res.error);
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error("Error al obtener tarimas:", error);
                });
        });


    });

    function actualizarConsecutivo() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_crear_mezcla.php',
            data: {
                action: 'obtener_consecutivo'
            },
            success: function(data) {
                let res = JSON.parse(data);

                $('#mez_folio').val(res.consecutivo);
            },
            error: function() {
                alert('Error al obtener el consecutivo.');
            }
        });
    }

    async function obtenerTarimasTabla() {
    try {
        const response = await $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_crear_mezcla.php',
            data: { action: 'obtener_tarimas' }
        });

        let res = JSON.parse(response);
        let tbody = $('#table tbody');
        let tfoot = $('#table tfoot');
        let total = 0;
        let prom_bloom = 0;
        let prom_visc = 0;
        let calidad = '';
        tbody.empty(); // Limpia el contenido del tbody

        if (res.length > 0) {
            $.each(res, function(index, item) {
                var row = '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + item.pro_id + '</td>' +
                    '<td>' + item.tar_folio + '</td>' +
                    '<td>' + item.tar_kilos + '</td>' +
                    '<td>' + item.tar_bloom + '</td>' +
                    '<td>' + item.tar_viscosidad + '</td>' +
                    '<td>' + item.cal_descripcion + '</td>' +
                    '<td><a href="#" onclick="quitarTarima(\'' + item.tar_id + '\')">' +
                    '<i class="fas fa-times-circle text-danger"></i></a></td>' +
                    '</tr>';
                total += parseFloat(item.tar_kilos);
                prom_bloom += parseFloat(item.tar_bloom);
                prom_visc += parseFloat(item.tar_viscosidad);

                tbody.append(row);
            });

            prom_bloom = (prom_bloom / res.length);
            prom_visc = (prom_visc / res.length);

            // Llama a obtenerCalidad con await para que espere a obtener el resultado antes de continuar
            calidad = await obtenerCalidad(prom_bloom, prom_visc);

            let promedios = `<tr>
                                <td colspan="4" style='font-weight: bold;' class='text-success'>Promedios teóricos:</td>
                                <td style='font-weight: bold;' class='text-success'>${prom_bloom.toFixed(2)}</td>
                                <td style='font-weight: bold;' class='text-success'>${prom_visc.toFixed(2)}</td>
                                <td style='font-weight: bold;' class='text-success'>${calidad}</td>
                                <td colspan="2"></td>
                             </tr>`;

            let kilos = `<tr class="">
                            <td colspan="3">Total de kilos:</td>
                            <td>${total.toFixed(2)}</td>
                            <td colspan="4"></td>
                         </tr>`;

            tbody.append(promedios);
            tfoot.empty().append(kilos);
            $('#rev_kilos').val(total.toFixed(2));
            $('#rev_teo_bloom').val(prom_bloom);
            $('#rev_teo_viscosidad').val(prom_visc);
            $('#rev_teo_calidad').val(calidad);

            if (total <= 5000.00) {
                $('#guardar').show();
            } else {
                $('#guardar').hide();
            }

        } else {
            tbody.append('<tr><td colspan="8">No hay datos disponibles</td></tr>');
            tfoot.empty();
            total = 0;
            $('#rev_kilos').val(total.toFixed(2));
            $('#guardar').hide();
        }
    } catch (e) {
        console.error('Error al obtener las tarimas o al procesar JSON:', e);
    }
}


    function quitarTarima(id) {
        let dataForm = {
            'tar_id': id
        }

        console.log(dataForm);

        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_quitar.php',
            data: dataForm,
            success: function(result) {
                let res = JSON.parse(result);
                console.log(res);
                if (res.success) {
                    alertas_v5("#alerta-mezcla", 'Listo!', res.success, 1, true, 5000);
                    console.log(res.success);
                    obtenerTarimasTabla();
                    $('#dataTableTarimasAlmacen').DataTable().ajax.reload();
                } else {
                    alertas_v5("#alerta-mezcla", 'Error!', res.error, 3, true, 5000);
                    console.log(res.error);
                }
            }
        });
    }


    function obtenerTarimas() {
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
                        if (Array.isArray(res) && res.length > 0) {
                            let tarimasArray = res.map(item => item.tar_id);
                            resolve(tarimasArray);
                        } else {
                            resolve([]);
                        }
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        reject(e);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error al obtener tarimas.');
                    reject(error);
                }
            });
        });
    }


    async function obtenerCalidad(prom_bloom, prom_visc) {
    try {
        const calidad = await determinarCalidad(prom_bloom, prom_visc);
        console.log("Calidad obtenida:", calidad);

        return calidad;
    } catch (error) {
        console.error("Error al determinar la calidad:", error);
    }
}

function determinarCalidad(prom_bloom, prom_visc) {
    let dataForm = {
        'tar_bloom': prom_bloom,
        'tar_viscosidad': prom_visc
    };

    return new Promise((resolve, reject) => {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_determinar_calidad.php',
            data: dataForm,
            success: function(response) {
                let res = JSON.parse(response);
                resolve(res.calidad); // Resuelve la promesa con el valor de calidad
            },
            error: function(error) {
                reject(error); // Rechaza la promesa en caso de error
            }
        });
    });
}
</script>