<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
extract($_POST);

?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar Nº factura</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_factura" method="POST">
            <input type="text" name="rev_id" id="rev_id" class="d-none" value="<?= $rev_id ?>">
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="rev_factura">Factura</label>
                        <input type="text" class="form-control" id="rev_factura" name="rev_factura" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-factura" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_factura" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#form_factura').submit(function(e){
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'funciones/revolturas_factura_insertar.php',
                data: dataForm,
                success: function(response){
                    let res = JSON.parse(response);
                    if (res.success) {
                        alertas_v5("#alerta-factura", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableRevolturas').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-factura", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            })
        });
    });
</script>
