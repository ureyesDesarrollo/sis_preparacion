<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../seguridad/user_seguridad.php";
?>

<script>
    function filtro() {
        var datos = {
            "fechaIni": $("#fechaInicio").val(),
            "fechaFin": $("#fechaFinal").val(),
        }

        var fechaIni = document.getElementById('fechaInicio').value;
        var fechaFin = document.getElementById('fechaFinal').value;
        $.ajax({
            type: 'post',
            url: 'inventario_filtro_nuevo.php',
            data: datos,
            //data: {nombre:n},
            success: function(d) {
                $("#tab2").html(d);
            }
        });
    }

    function exportar() {
        var fechaIni = document.getElementById('fechaInicio').value;
        var fechaFin = document.getElementById('fechaFinal').value;
        if (fechaIni != '' && fechaFin != '') {
            window.open('../exportar/inventario_exportar.php?fechaIni=' + encodeURIComponent(fechaIni) +
                '&fechaFin=' + encodeURIComponent(fechaFin));
        }
        if (fechaIni != '') {
            window.open('../exportar/inventario_exportar.php?fechaIni=' + encodeURIComponent(fechaIni) +
                '&fechaFin=' + encodeURIComponent(fechaFin));
        }

        if (fechaIni == '' && fechaFin == '') {
            window.open('../exportar/inventario_exportar.php');
        }

        /*    var fechaIni = document.getElementById('fechaInicio').value;
           var fechaFin = document.getElementById('fechaFinal').value;
           if (fechaIni != '' && fechaFin != '') {
               window.open('../exportar/inventario_exportar_rango_nuevo.php?fechaIni=' + encodeURIComponent(fechaIni) +
                   '&fechaFin=' + encodeURIComponent(fechaFin));
           }

           if (fechaIni != '') {
               window.open('../exportar/inventario_exportar_nuevo.php?fechaIni=' + encodeURIComponent(fechaIni) +
                   '&fechaFin=' + encodeURIComponent(fechaFin));
           }

           if (fechaIni == '' && fechaFin == '') {
               window.open('../exportar/inventario_exportar_dia_nuevo.php');
           } */
    }

    function reset() {
        location.href = "listado_inventario_nuevo.php";
    }
</script>

<style>
    @media print {
        .ocultar {
            display: none !important;
        }
    }


    @page {
        size: A4 landscape;
    }
</style>
<?php
include "../../funciones/funciones.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Listado de Inventario <?php echo date("d-m-Y"); ?></title>
    <link rel="stylesheet" href="../../css/estilos_formatos.css">
    <style type="text/css">
        td {
            border: 1px solid #000
        }
    </style>

    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../css/estilos_menu_general.css">
    <link rel="stylesheet" href="../../assets/css/estilos_generales.css">

    <link rel="icon" type="image/png" sizes="32x32" href="imagenes/favicon-32x32.png">
    <script src="../../assets/fontawesome/fontawesome.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row ocultar" style="padding-top: 4rem;box-shadow: 10px 5px 5px #e6e6e6;padding-bottom:1rem;margin-bottom:1rem">
            <div class="col-md-6"></div>
            <div class="col-md-2">
                <span style="font-weight:bold"> De:</span>
                <input type="date" style="width: 150px;display:inline" class="form-control" id="fechaInicio" onchange="filtro()">
            </div>
            <div class="col-md-2">
                a:
                <input type="date" style="width: 150px;display:inline" class="form-control" id="fechaFinal" onchange="filtro()">
            </div>
            <div class="col-md-1">
                <button onclick="reset()" type="button" style="height: 30px;border-radius: 5px;background: #2e6da4;border: 0px;color: #fff">Limpiar</button>
            </div>
            <div class="col-md-1">
                <button onclick="exportar()" type="button" style="height: 30px;border-radius: 5px;background: #2e6da4;border: 0px;color: #fff">Exportar </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <img src="../../imagenes/logo_progel_v3.png" style="width: 50%;">
                </div>
                <div class="col-md-5" style="text-align: center;">
                    <h2>ENTRADAS DE MATERIA PRIMA</h2>
                </div>
                <div class="col-md-2">
                    <h4 style="padding-top: 1.5rem;"> PRE F 001-REV.002</h4>
                </div>
                <div class="col-md-2">
                    <h4 style="padding-top: 1.5rem;">FECHA:<?php echo fnc_formato_fecha(date("Y-m-d")); ?></h4>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 2rem;">
            <div id="tab2" class="col-md-12">
                <?php include_once "inventario_filtro_nuevo.php"; ?>
            </div>
        </div>
    </div>
    <?php include "../../generales/pie_pagina_formato.php"; ?>
</body>

</html>

<script src="../../js/jquery.min.js"></script>
<script src="../../js/jspdf.js"></script>
<script src="../../js/pdfFromHTML.js"></script>