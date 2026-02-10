<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema de Preparación Progel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>
    <div id="salir"></div>
    <?php

    include_once 'generales/menu.php'; ?>

    <body>

        <div class="col-md-12" style="background: -webkit-linear-gradient(#eee, #333);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;margin-bottom: 20px">

            <h2>¡ Buen día <?php echo $registros['usu_usuario'] ?> !
                <br><br><br>
                Bienvenido al sistema para el control de preparación
            </h2>

        </div>

        <?php 
        if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 13) { 
            $cadena = mysqli_query($cnx, "SELECT m.mat_nombre, i.inv_kg_totales, i.inv_no_ticket, i.inv_fecha, p.prv_nombre  
                                            FROM inventario as i INNER JOIN proveedores as p on(i.prv_id = p.prv_id) 
                                            INNER JOIN materiales as m on (i.mat_id = m.mat_id)
                                            WHERE inv_especial = 1") or die(mysqli_error($cnx) . "Error: en consultar");
            $registros = mysqli_fetch_assoc($cadena);  

            ?>
        <div>
            <table class="table table-hover table-sm table-dark" width="70%">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Kilos</th>
                    <th>Material</th>
                    <th>Proveedor</th>
                </tr>
                </thead>
                <tbody>
                <?php do{ ?>
                <tr>
                    <td><?php echo $registros['inv_no_ticket'];?></td>
                    <td><?php echo $registros['inv_kg_totales'];?></td>
                    <td><?php echo $registros['mat_nombre'];?></td>
                    <td><?php echo $registros['prv_nombre'];?></td>
                </tr>
                <?php }while($registros = mysqli_fetch_assoc($cadena)); ?>
                </tbody>
            </table>
        </div>
        <?php } ?>

        <?php include "generales/pie_pagina.php"; ?>

        <div class="modal right" id="modal_ver_historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
        </div>

    </body>

</html>