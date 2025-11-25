<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Creado: Enero-2024*/
include "../conexion/conexion.php";
$cnx =  Conectarse();
?>
<head>
    <meta charset="UTF-8">
    <title>Listado OEE'S <?php echo date("d-m-Y"); ?></title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <script src=../assets/datatable/jquery.dataTables.min.js></script>
    <script src=../assets/datatable/dataTables.bootstrap5.min.js></script>

    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Buttons -->
    <link rel="stylesheet" href="../assets/datatable/buttons.dataTables.min.css">
    <script src="../assets/fontawesome/fontawesome.js"></script>

    <script>
        function filtro() {
            var datos = {
                "fechaIni": $("#fechaInicio").val(),
            }

            var fechaIni = document.getElementById('fechaInicio').value;
            $.ajax({
                type: 'post',
                url: 'listado_oee_resultado.php',
                data: datos,
                success: function(d) {
                    $("#contenido").html(d);
                }
            });
        }
    </script>
    
</head>

<body>
    <div class="container-fluid">
        <div class="row ocultar" style="padding-top: 1rem;box-shadow: 10px 5px 5px #e6e6e6;padding-bottom:1rem;margin-bottom:1rem">
            <div class="col-md-2"> 
                <img src="../imagenes/logo_progel_v3.png" style="width:17%;">
            </div>
            <div class="col-md-4">
                <span style="font-weight:bold;font-size:16px">OEE'S PREPARACION</span>
                <br>
                <span style="font-weight:bold;font-size:12px; color:#FFCCCC;">Calidad - Disponibilidad - Eficiencia</span>
            </div>
            <div class="col-md-2">
                <span style="font-weight:bold"> De:</span>
                <input type="date" style="width: 150px;display:inline" class="form-control" id="fechaInicio" onchange="filtro()">
            </div>
            <div class="col-md-3">
                <span style="font-weight:bold"> Hoy es <?php echo date("Y-m-d h:i:s") ?></span>
                <br>
                <span style="font-weight:bold;font-size:12px; color:#FFCCCC;">Equipos cargados y Liberados en el día</span>
            </div>
            <div class="col-md-1">
                <a style="color: #000;" href="../indicadores/submenu_indicadores.php">
                <i class="fa-solid fa-circle-left"></i> Regresar</a>
            </div>

        </div>

        <div class="row" id="contenido" style="margin-top: 5rem;">
            &nbsp;&nbsp;&nbsp; Seleccione la fecha para ver los registros
        </div>
    </div>
</body>
<link rel="stylesheet" href="../css/estilos_footer.css">

<footer style="position: fixed;  bottom: 0;  width: 100%;  z-index: 100;">
    <div align="center">Fecha Impresion:<?php echo date("d-m-Y H:i:s"); ?></div>
    <br />
    Copyright 2018 by <b><a href="http://ccaconsultoresti.com/">CCA Consultores en TI</a> </b>. All Rights Reserved.
</footer>

</html>