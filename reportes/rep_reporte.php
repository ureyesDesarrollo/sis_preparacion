<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include('../generales/menu.php');

?>
<script src="../js/jquery.min.js"></script>
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Reportes</li>
        </ol>
    </nav>

    <form method="POST" action="rep_reporte_especial.php" id="form_reporte">
        <div class="row">
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha de fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
            </div>
        </div>
    </form>

    <div class="col-md-12">
        <button form="form_reporte" type="submit" class="btn btn-primary" name="action" value="inventario">Inventario</button>
        <button form="form_reporte" type="submit" class="btn btn-primary" name="action" value="procesos">Procesos</button>
        <button form="form_reporte" type="submit" class="btn btn-primary" name="action" value="revolturas">Revolturas</button>
        <button form="form_reporte" type="submit" class="btn btn-primary" name="action" value="quimicos">Quimicos</button>
    </div>
</div>