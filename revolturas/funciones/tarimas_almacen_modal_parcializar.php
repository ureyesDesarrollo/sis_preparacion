<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();
extract($_POST);

$query = mysqli_query($cnx,"SELECT pro_id, tar_folio,tar_kilos FROM rev_tarimas WHERE tar_id = '$tar_id'");
$res = mysqli_fetch_assoc($query);
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kilos a tomar, Procesos: <?= $res['pro_id']?>(<?= $res['tar_folio']?>)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_parcializar" method="POST">
                <div class="row">
                    <input type="text" class="d-none" value="<?= $tar_id ?>" name="tar_id" id="tar_id">
                    <div class="col-md-6">
                        <label for="" class="form-label">Kilos originales </label>
                        <input type="text" class="form-control" value="<?= $res['tar_kilos']?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="kilos_parcializar" class="form-label">Kilos a tomar</label>
                        <input type="text" class="form-control" name="kilos_parcializar" onkeypress="return isNumberKey(event, this);" required maxlength="7">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-parcializar" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_parcializar" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#form_parcializar').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_almacen_parcializar.php',
                data: dataForm,
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alertas_v5("#alerta-parcializar", 'Listo!', res.success, 1, true, 5000);
                        $('#dataTableTarimasAlmacen').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-parcializar", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });
</script>