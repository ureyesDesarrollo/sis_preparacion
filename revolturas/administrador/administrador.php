<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Sepetiembre-2024*/

include "../../seguridad/user_seguridad.php";
?>
<style>
    .card-link {
        text-decoration: none;
        color: inherit;
    }

    .card-option {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 10px;
        transition: box-shadow 0.3s ease;
    }

    .card-option:hover {
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }

    .card-title {
        font-size: 1.1rem;
    }

    .card-text {
        font-size: 0.9rem;
    }
</style>
</head>
<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Modulo Administrador</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container my-3">
    <div class="row">
        <!-- Tarjeta 1: Cerrar procesos -->
        <div class="col-md-4">
            <a href="#" class="card-link" onclick="abrir_modal_cerrar_procesos()">
                <div class="card card-option text-center">
                    <div class="card-body">
                        <h5 class="card-title">Cerrar procesos</h5>
                        <p class="card-text">Cierra procesos que quedaron abiertos accidentalmente.</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Tarjeta 2: Actualizar datos -->
        <div class="col-md-4">
            <a href="#" class="card-link" onclick="abrir_modal_actualizar_datos()">
                <div class="card card-option text-center">
                    <div class="card-body">
                        <h5 class="card-title">Actualizar datos</h5>
                        <p class="card-text">Cambiar el &#8470; de proceso o folio de tarima.</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Tarjeta 3: Activar rendimiento -->
        <div class="col-md-4">
            <a href="#" class="card-link" onclick="abrir_modal_activar_rendimiento()">
                <div class="card card-option text-center">
                    <div class="card-body">
                        <h5 class="card-title">Activar rendimiento</h5>
                        <p class="card-text">Recalcular el rendimiento del proceso.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="cerrar_procesos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="actualizar_datos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="activar_rendimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
    function abrir_modal_cerrar_procesos() {
        $.ajax({
            type: 'POST',
            url: 'administrador/cerrar_procesos_modal.php',
            success: function(result) {
                $('#cerrar_procesos').html(result);
                $('#cerrar_procesos').modal('show');
            }
        });
    }

    function abrir_modal_actualizar_datos() {
        $.ajax({
            type: 'POST',
            url: 'administrador/actualizar_datos_modal.php',
            success: function(result) {
                $('#actualizar_datos').html(result);
                $('#actualizar_datos').modal('show');
            }
        });
    }

    function abrir_modal_activar_rendimiento() {
        $.ajax({
            type: 'POST',
            url: 'administrador/activar_rendimiento_modal.php',
            success: function(result) {
                $('#activar_rendimiento').html(result);
                $('#activar_rendimiento').modal('show');
            }
        });
    }
</script>