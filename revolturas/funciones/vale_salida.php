<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

//include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();
extract($_GET);
$revoltura = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT rev_folio FROM rev_revolturas WHERE rev_id = '$rev_id'"));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale de Salida de Mercancía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .underline {
            border-bottom: 1px solid black;
            display: inline-block;
            width: 100%;
            height: 1.5em;
        }

        .bordered {
            border: 2px solid black;
            padding: 20px;
            margin: 20px 0;
        }

        .table td,
        .table th {
            vertical-align: middle;
            text-align: center;
        }

        .square-box {
            width: 20px;
            height: 20px;
            border: 1px solid black;
            display: inline-block;
            margin-right: 5px;
        }

        /* Ajustar ancho máximo del contenedor para evitar desbordamiento */
        .container {
            max-width: 60%;
        }

        /* Permitir desplazamiento horizontal si la tabla es demasiado ancha */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5 bordered ms-3">
        <div class="row align-items-center">
            <div class="col-md-3">
                <img src="../../imagenes/logo_progel_v5.png" alt="Logo Progel" class="img-fluid" style="max-height: 60px;">
            </div>
            <div class="col-md-5 text-center">
                <h3>PROGEL MEXICANA, S.A. DE C.V.</h3>
                <h4>Vale de Salida de Mercancía</h4>
            </div>
            <div class="col-md-3 text-end">
                <p>Folio No: <strong>1786</strong></p>
            </div>
        </div>
        <hr>

        <!-- Hacer la tabla responsiva -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kgs.</th>
                        <th>Lote</th>
                        <th colspan="5">Presentaciones</th>
                        <th colspan="8">Calidad</th>
                        <th>Cliente</th>
                        <th>Se lleva</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Caja 250 grs</th>
                        <th>Caja 1 kg</th>
                        <th>Costal saco</th>
                        <th>Super saco</th>
                        <th>CH</th>
                        <th>315</th>
                        <th>300</th>
                        <th>290</th>
                        <th>280</th>
                        <th>265</th>
                        <th>250</th>
                        <th>230</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="underline"></span></td>
                        <td><span class="underline"><?= $revoltura['rev_folio'] ?></span></td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td>
                            <div class="square-box"></div>
                        </td>
                        <td><span class="underline"></span></td>
                        <td><span class="underline"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Observaciones y Firmas -->
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="observaciones">Observaciones:</label>
                <div class="underline"></div>
            </div>
        </div>

        <div class="row mt-3 text-center">
            <div class="col-md-4">
                <p>Autorizó</p>
                <span class="underline"></span>
            </div>
            <div class="col-md-4">
                <p>Entregó</p>
                <span class="underline"></span>
            </div>
            <div class="col-md-4">
                <p>Recibió</p>
                <span class="underline"></span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>