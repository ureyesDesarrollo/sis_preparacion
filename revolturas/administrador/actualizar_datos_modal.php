<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

if (isset($_POST['action'])) {
    $cnx = Conectarse();
    // recuperamos la consulta de búsqueda y la acción de la solicitud AJAX
    $parametroBusqueda = $_POST['parametroBusqueda'];
    $action = $_POST['action'];
    // realizamos la consulta de búsqueda según la acción
    if ($action == 'obtener_procesos') {
        $query = "SELECT DISTINCT pro_id FROM rev_tarimas WHERE pro_id LIKE '%$parametroBusqueda%'";
    } else if ($action == 'obtener_tarimas') {
        $query = "SELECT tar_id,tar_folio FROM rev_tarimas WHERE pro_id LIKE '%$parametroBusqueda%'";
    } else if ($action  == 'obtener_procesos_con_tarimas') {
        $query = "SELECT l.lote_id, a.pro_id, l.lote_folio
        FROM lotes_anio AS l
        INNER JOIN procesos_agrupados AS a ON (l.lote_id = a.lote_id)
        WHERE l.lote_estatus IN (2, 3) 
        AND a.pro_id LIKE '%$parametroBusqueda%'";
    }

    $result = mysqli_query($cnx, $query);

    // Crear un array para almacenar los resultados
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if ($action == 'obtener_procesos') {
            $data[] = ['pro_id' => $row['pro_id']];
        } else if ($action == 'obtener_tarimas') {
            $data[] = ['tar_id' => $row['tar_id'], 'tar_folio' => $row['tar_folio']];
        } else if ($action  == 'obtener_procesos_con_tarimas') {
            $data[] = ['pro_id' => $row['pro_id']];
        }
    }

    echo json_encode($data);

    // cerramos la conexión a la base de datos
    mysqli_close($cnx);
    exit();
}
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="">Actualizar datos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_actualizar_datos_admin" method="POST">
                <div class="row">
                    <h6 class="text-center">Buscar tarima</h6>
                    <a class="btn" onclick="alert(' 1. Busca el proceso, esto filtrara las tarimas que pertenecen a dicho proceso. \n 2. Busca la tarima. \n 3.Selecciona el nuevo proceso.')"><i class="fa-solid fa-circle-info"></i> Instrucciones</a>
                    <div class="col-md-6">
                        <input id="input_field" type="text" placeholder="Escriba para buscar proceso" class="form-control" onkeypress="return isNumberKey(event, this);">
                        <label for="pro_id_act" class="form-label mt-2">Proceso</label>
                        <select id="pro_id_act" name="pro_id" class="form-select">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input id="input_field_tar" type="text" placeholder="Escriba para buscar tarima" class="form-control" onkeypress="return isNumberKey(event, this);">
                        <label for="tar_folio" class="form-label mt-2">Folio tarima</label>
                        <select id="tar_folio" name="tar_folio" class="form-select">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <input id="input_field_p" type="text" placeholder="Escriba para buscar proceso" class="form-control" onkeypress="return isNumberKey(event, this);">
                        <label for="pro_id_n" class="form-label mt-2">Proceso nuevo</label>
                        <select id="pro_id_n" name="pro_id_n" class="form-select">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta_actualizar_datos" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_actualizar_datos_admin" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let array_tarimas = [];
        let options_tarimas = '<option value="">Despliegue para ver las coincidencias</option>';
        $('#input_field').keyup(function() {
            let inputValue = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'administrador/actualizar_datos_modal.php',
                data: {
                    parametroBusqueda: inputValue,
                    action: 'obtener_procesos'
                },
                success: function(data) {
                    let procesos = JSON.parse(data);
                    let options = '<option value="">Despliegue para ver las coincidencias</option>';
                    if (inputValue.length > 0) {
                        procesos.forEach(function(pro) {
                            options += `<option value="${pro.pro_id}">${pro.pro_id}</option>`;
                        });
                        $('#pro_id_act').empty().append(options);
                    } else {
                        $('#pro_id_act').empty().append('<option value="">Seleccione</option>');
                    }
                },
                error: function() {
                    console.error('Error al obtener los datos.');
                }
            });
        });

        //Buscar las tarimas que estan en el proceso
        $('#pro_id_act').change(function() {
            let selectedProceso = $(this).val();
            $('#input_field_tar').val('');
            $.ajax({
                type: 'POST',
                url: 'administrador/actualizar_datos_modal.php',
                data: {
                    parametroBusqueda: selectedProceso,
                    action: 'obtener_tarimas'
                },
                success: function(data) {
                    let tarimas = JSON.parse(data);
                    let options = '<option value="">Despliegue para ver las coincidencias</option>';
                    if (selectedProceso.length > 0) {
                        tarimas.forEach(function(tar) {
                            array_tarimas.push({
                                tar_id: tar.tar_id,
                                tar_folio: tar.tar_folio
                            });
                            options += `<option value="${tar.tar_id}">${tar.tar_folio}</option>`;
                            options_tarimas += `<option value="${tar.tar_id}">${tar.tar_folio}</option>`;
                        });
                        $('#tar_folio').empty().append(options);
                    } else {
                        $('#tar_folio').empty().append('<option value="">Seleccione</option>');
                    }
                }
            });
        });

        $('#input_field_p').keyup(function() {
            let inputValue = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'administrador/actualizar_datos_modal.php',
                data: {
                    parametroBusqueda: inputValue,
                    action: 'obtener_procesos_con_tarimas'
                },
                success: function(data) {
                    let procesos = JSON.parse(data);
                    console.log(procesos);
                    let options = '<option value="">Despliegue para ver las coincidencias</option>';
                    if (inputValue.length > 0) {
                        procesos.forEach(function(pro) {
                            options += `<option value="${pro.pro_id}">${pro.pro_id}</option>`;
                        });
                        $('#pro_id_n').empty().append(options);
                    } else {
                        $('#pro_id_n').empty().append('<option value="">Seleccione</option>');
                    }
                },
                error: function() {
                    console.error('Error al obtener los datos.');
                }
            });
        });

        $('#input_field_tar').keyup(function() {
            let value = $(this).val();
            let array_filtrado = array_tarimas.filter(el => el.tar_folio.includes(value));
            let options = '<option value="">Despliegue para ver las coincidencias</option>';
            if (value > 0) {
                array_filtrado.forEach(function(tar) {
                    options += `<option value="${tar.tar_id}">${tar.tar_folio}</option>`
                });
                $('#tar_folio').empty().append(options);
            } else {
                $('#tar_folio').empty().append(options_tarimas);
            }
        });

        $('#form_actualizar_datos_admin').submit(function(e) {
            e.preventDefault();
            let selectedTarima = $('#tar_folio').val();
            let pro_id_n = $('#pro_id_n').val();
            let pro_id = $('#pro_id_act').val();
            $.ajax({
                type: 'POST',
                url: 'administrador/actualizar_datos.php',
                data: {
                    "tar_id": selectedTarima,
                    "pro_id_n": pro_id_n,
                    "pro_id": pro_id
                },
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        alertas_v5("#alerta_actualizar_datos", 'Listo!', res.success, 1, true, 5000);
                        $('#form_actualizar_datos_admin')[0].reset();
                    } else {
                        alertas_v5("#alerta_actualizar_datos", 'Error!', res.error, 1, true, 5000);
                    }
                }
            });
        });

    });
</script>