<?php
require_once('../../../conexion/conexion.php');
include('../../../seguridad/user_seguridad.php');
include('../../../funciones/funciones.php');
require '../../../funciones/funciones_procesos.php';
$cnx =  Conectarse();

extract($_GET);
$listado_pelambre = mysqli_query($cnx, "SELECT 
ip.ip_id,
fnc_nombre_material(ip.inv_id) as material,
ip.ip_id,
e.ep_descripcion,
ip.ip_fecha_envio,
ip.ip_fecha_remojo,
ip.ip_hora_ini_remojo,
ip.ip_hora_ini_carga,
ip.ip_hora_fin_carga,
ip.usu_id,
ip.ip_fe_descarga,
ip.ip_kg_finales,
ip.ip_observaciones,
u.usu_nombre
FROM 
inventario_pelambre ip
INNER JOIN 
equipos_preparacion e ON ip.ep_id = e.ep_id
INNER JOIN 
usuarios u ON ip.usu_id = u.usu_id WHERE ip_id = '" . $_GET['ip_id'] . "'");

if (!$listado_pelambre) {
    die("Error en la consulta: " . mysqli_error($cnx));
}

$listado_pelambre = mysqli_fetch_assoc($listado_pelambre);

mysqli_close($cnx);

?>

<div class="container-fluid">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="../../../assets/fontawesome/fontawesome.js"></script>
    <link rel="stylesheet" href="../../../assets/css/indicadores.css">
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../../js/alerta.js"></script>

    <style>
        @page {
            size: A4;
        }

        .imagen-header1 {
            background-image: url("../../../imagenes/banner_progel2.png");
            width: 95%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0 auto;
            text-align: center;
            height: 90px;
            /* background-size: contain; */
            /* Hacer la imagen lo suficientemente pequeña como para ajustarse completamente dentro del div */
            color: #fff;
            background-position: -20px;
        }

        .text-header {
            padding-top: 1.5rem;
            font-size: 26px;
        }

        .container-fluid {
            padding-left: 50px;
            padding-right: 50px;
        }
    </style>
    <div class="imagen-header1">
        <p class="text-header">
            Bitacora pelambrado
        </p>
    </div>

    <?php include 'pelambrado_encabezado.php' ?>
    <?php include 'pelambrado_fases.php' ?>
</div>