<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx =  Conectarse();

extract($_POST);
$query = mysqli_query(
    $cnx,
    "SELECT n.*, r.rac_descripcion FROM rev_nivel_posicion n 
        INNER JOIN rev_racks r ON r.rac_id = n.rac_id 
        WHERE niv_id = $id"
);
$registro = mysqli_fetch_assoc($query);

?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Actualizar Nivel - Posición</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_nivel_pos_act" method="POST">
                <div class="form-group row">
                    <div class="col-md-6 d-none">
                        <label for="niv_nivel">Clave</label>
                        <input type="text" class="form-control" name="niv_id" id="niv_id" required value="<?= $registro['niv_id'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="rac_id" class="form-label">Rack</label>
                        <select name="rac_id" id="rac_id_up" class="form-select" required>
                            <option value="<?= $registro['rac_id'] ?>"><?= $registro['rac_descripcion'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="niv_nivel" class="form-label">Nivel</label>
                        <input type="text" class="form-control" name="niv_nivel" id="niv_nivel" required value="<?= $registro['niv_nivel'] ?>" maxlength="1">
                    </div>
                    <div class="col-md-4">
                        <label for="niv_posicion" class="form-label">Posición</label>
                        <input type="text" class="form-control" id="niv_posicion" name="niv_posicion" required value="<?= $registro['niv_posicion'] ?>" onkeypress="return isNumberKey(event, this);" maxlength="1">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-nivel-pos" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_nivel_pos_act" type="submit" class="btn btn-primary ms-2">
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
        $("#form_nivel_pos_act").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'catalogos/nivel_posicion_actualizar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-nivel-pos", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableNivelPos').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-nivel-pos", 'Error!', res.error, 3, true, 5000);
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
</script>