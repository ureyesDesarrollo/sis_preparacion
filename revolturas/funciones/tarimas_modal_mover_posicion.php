<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

extract($_POST);

try {
    $sql = mysqli_query($cnx, "SELECT * FROM rev_nivel_posicion WHERE niv_id = '$niv_id'");
    $registros = mysqli_fetch_assoc($sql);

    $sqlRack = mysqli_query($cnx, "SELECT * FROM rev_racks WHERE rac_id = '$rac_id'");
    $registroRack = mysqli_fetch_assoc($sqlRack);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    mysqli_close($cnx);
}

?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Mover tarima Proceso: <?= $pro_id ?> / Tarima: <?= $tar_folio ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_tarima_move" method="POST">
                <div class="row">
                    <input type="text" value="<?= $tar_id ?>" name="tar_id" id="tar_id" hidden>
                    <div class="col-md-6">
                        <label for="rac_id" class="form-label">Rack</label>
                        <select name="rac_id" id="rac_id_up" class="form-select" onchange="cargarNivelPosicion(this)">
                            <option value="<?= $registroRack['rac_id'] ?>"><?= $registroRack['rac_descripcion'] ?></option>
                            <option disabled>Seleccione</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nav_id" class="form-label">Nivel - Posición</label>
                        <input type="text" value="<?= $registros['niv_id'] ?>" name="niv_id_act" id="niv_id_act" hidden>
                        <select name="niv_id" id="niv_id_up" class="form-select">
                            <option value="<?= $registros['niv_id'] ?>"><?= $registros['niv_nivel'] ?> - <?= $registros['niv_posicion'] ?></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-tarima-move" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_tarima_move" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        cargarRacks();
        cargarNivelPosicionInicial();

        $("#form_tarima_move").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_mover.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        alertas_v5("#alerta-tarima-move", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableTarimas').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-tarima", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });


    function cargarRacks() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/racks_listado.php',
            success: function(data) {
                let racks = JSON.parse(data);
                let options = '';
                racks.forEach(function(rack) {
                    if (rack.rac_estatus === 'A') {
                        options += `<option value="${rack.rac_id}">${rack.rac_descripcion}</option>`;
                    }
                });
                $('#rac_id_up').append(options);
            },
            error: function() {
                alert('Error al cargar los racks.');
            }
        });
    }

    function cargarNivelPosicion(rac_id) {
        rac_id = rac_id.value;

        let dataForm = {
            'rac_id': rac_id
        };

        $('#niv_id_up').empty();
        $('#niv_id_up').append('<option disabled value="">Seleccione</option>');


        $.ajax({
            type: 'POST',
            url: 'catalogos/nivel_posicion_listado.php',
            data: dataForm,
            success: function(data) {
                let niveles = JSON.parse(data);
                let options = '';
                niveles.forEach(function(niv) {
                    if (niv.niv_ocupado !== '1') {
                        options += `<option value="${niv.niv_id}">${niv.niv_nivel} - ${niv.niv_posicion}</option>`;
                    }
                });
                $('#niv_id_up').append(options);
            },
            error: function() {
                alert('Error al cargar niveles.');
            }
        });
    }

    function cargarNivelPosicionInicial() {
        let rac_id = $('#rac_id_up').val();

        let dataForm = {
            'rac_id': rac_id
        };

        $.ajax({
            type: 'POST',
            url: 'catalogos/nivel_posicion_listado.php',
            data: dataForm,
            success: function(data) {
                let niveles = JSON.parse(data);
                let options = '<option value="">Seleccione</option>';
                niveles.forEach(function(niv) {
                    if (niv.niv_ocupado !== '1') {
                        options += `<option value="${niv.niv_id}">${niv.niv_nivel} - ${niv.niv_posicion}</option>`;
                    }
                });

                $('#niv_id_up').append(options);

                // Mantiene el valor actual de nivel-posicion
                let currentNivelId = '<?= isset($registros['niv_id']) ? $registros['niv_id'] : '' ?>';
                $('#niv_id_up').val(currentNivelId);
            },
            error: function() {
                alert('Error al cargar niveles.');
            }
        });
    }
</script>