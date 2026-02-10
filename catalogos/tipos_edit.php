<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
?>
<div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edición de equipos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="form_equipos">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descripción equipo:</label>
                                <input onchange="valida_nombre_equipo()" name="txt_descripcion" type="text" class="form-control" id="txt_descripcion" maxlength="60" required placeholder=" Descripción equipo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo equipo:</label>
                                <select name="cbx_tipo" type="email" class="form-control" id="cbx_tipo" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    $query =  mysqli_query($cnx, "SELECT * FROM equipos_tipos WHERE et_estatus = 'A' ORDER BY et_descripcion ");
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo mb_convert_encoding($row['et_tipo'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['et_descripcion'], "UTF-8");  ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Capacidad minima(Kg):</label>
                                <input onkeypress="return isNumberKey(event, this);" name="txt_capacidad_min" type="text" class="form-control" id="txt_capacidad_min" maxlength="8" required placeholder=" Capacidad minima(Kg)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Capacidad maxima(Kg):</label>
                                <input onkeypress="return isNumberKey(event, this);" name="txt_capacidad_max" type="text" class="form-control" id="txt_capacidad_max" maxlength="8" required placeholder="Capacidad maxima(Kg)">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <!--mensajes-->
                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-info" id="alerta-equipos" style="height: 40px;display:none;position:fixed">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div>


                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-danger" id="alerta-equipo_nombre_valida" style=" height: 40px;display:none;text-align:left">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                            </div>
                            <div class="col-sm-3 col-lg-2">
                                <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>