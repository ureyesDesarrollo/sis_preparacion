<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header,
        .content {
            border-bottom: 1px solid #000;
            padding: 5px;
        }

        .header .title {
            font-weight: bold;
        }

        .content .left {
            width: 50%;
        }

        .table-responsive {
            margin-top: 20px;
        }

        h3 {
            color: #007bff;
        }
    </style>
</head>
<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/
include "../../conexion/conexion.php";

try {
    $cnx = Conectarse();

    $fe_factura = '';

    if (isset($_POST['txt_filtro_Factura'])) {
        $fe_factura = $_POST['txt_filtro_Factura'];
    } else if (isset($_GET['fe_factura'])) {
        $fe_factura = $_GET['fe_factura'];
    }

    $query = "SELECT 
    rpf.fe_factura,
    rpf.fe_cartaporte,
    rpf.fe_cantidad,
    rpf.fe_tipo,
    c.cte_nombre,
    rpf.fe_fecha,
    rev.rev_folio,
    rev.rev_id,
    rp.pres_descrip,
    rp.pres_kg,
    COALESCE(rr.rr_id, rrc.rrc_id) AS referencia_id,
    COALESCE(rr.rr_ext_inicial, rrc.rrc_ext_inicial) AS rr_ext_inicial,
    COALESCE(rr.rr_ext_real, rrc.rrc_ext_real) AS rr_ext_real
FROM 
    rev_revolturas_pt_facturas rpf
INNER JOIN 
    rev_clientes c ON c.cte_id = rpf.cte_id
LEFT JOIN 
    rev_revolturas_pt rr ON rr.rr_id = rpf.rr_id  -- Facturas con rr_id
LEFT JOIN 
    rev_revolturas_pt_cliente rrc ON rrc.rrc_id = rpf.rrc_id  -- Facturas con rrc_id
LEFT JOIN 
    rev_presentacion rp ON rp.pres_id = COALESCE(rr.pres_id, rrc.pres_id)  -- Tomar la presentación correcta
LEFT JOIN 
    rev_revolturas rev ON rev.rev_id = COALESCE(rr.rev_id, rrc.rev_id)
        WHERE rpf.fe_factura = '$fe_factura'";

    $listado_facturas = mysqli_query($cnx, $query);


    $res = array();
    while ($fila = mysqli_fetch_assoc($listado_facturas)) {
        $res[] = $fila;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$currentDir = dirname($_SERVER['REQUEST_URI']);
?>

<body>
    <div class="header mb-4">
        <div class="row align-items-center">
            <div class="col-3">
                <img src="../../imagenes/logo_progel_v3.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-5">
                <div class="title">Detalle de <?= $res[0]['fe_tipo'] == 'A' ? 'Factura' : 'Remisión' ?>: <?= $fe_factura ?></div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Factura</th>
                    <th>Presentación</th>
                    <th>Existencia inicial</th>
                    <th>Existencia real</th>
                    <th>Cantidad facturada</th>
                    <th>Cliente</th>
                    <th>Revoltura</th>
                    <th>Ver Revoltura</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($res as $registro) { ?>
                    <tr>
                        <td><?= $registro['fe_fecha'] ?></td>
                        <td><?= $registro['fe_factura'] ?></td>
                        <td><?= $registro['pres_descrip'] ?></td>
                        <td><?= $registro['rr_ext_inicial'] ?></td>
                        <td><?= ($registro['rr_ext_real'] != '0.00') ? $registro['rr_ext_real'] : 'Sin existencias' ?></td>
                        <td><?= $registro['fe_cantidad'] ?></td>
                        <td><?= $registro['cte_nombre'] ?></td>
                        <td><?= $registro['rev_folio'] ?></td>
                        <td><a href="<?= $currentDir ?>/revolturas_detalle.php?rev_id=<?= $registro['rev_id'] ?>"
                                class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle tarima">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../../assets/fontawesome/fontawesome.js"></script>
</body>

</html>