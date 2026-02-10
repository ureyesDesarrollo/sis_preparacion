<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiqueta de Revoltura</title>
    <style>
        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .label-container {
            width: 16.5cm;
            height: 10cm;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .label {
            font-size: 14px;
            line-height: 1.5;
        }

        .label-title {
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
            margin-left: 20px;
        }

        .qr-container canvas {
            margin-top: 10px;
        }

        .btn-imprimir {
            margin-top: 10px;
            padding: 10px;
            font-size: 14px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-imprimir:hover {
            background-color: #45a049;
        }
        
        /* Aseguramos que el logo se quede pegado a la izquierda en la impresión */
        .logo-container {
            width: 100%; /* Utiliza el ancho completo disponible */
            text-align: left; /* Alineación a la izquierda */
            margin-bottom: 10px; /* Un pequeño espacio debajo del logo */
            padding-left: 0; /* Eliminar margen izquierdo si es necesario */
        }

        .logo-container img {
            width: 50%; /* Ajusta el tamaño del logo según sea necesario */
            max-width: 100px; /* Tamaño máximo del logo */
            display: inline-block; /* Se asegura que el logo no esté en bloque */
            margin: 0; /* Elimina el margen alrededor del logo */
            padding-left: 0; /* Elimina el padding izquierdo */
        }
        
        /* Estilo para la etiqueta de impresión */
        .label-container {
            width: 16.5cm; /* Aseguramos que mantenga el tamaño de impresión correcto */
            height: 10cm;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start; /* Alineación vertical */
            box-sizing: border-box;
            font-size: 12px; /* Ajuste el tamaño para la impresión */
        }

        /* Estilos para la impresión */
        @media print {
            .btn-imprimir {
                display: none; /* Ocultar el botón de impresión en la impresión */
            }

            .label-container {
            width: 16.5cm;
            height: 10cm;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .label {
            font-size: 14px;
            line-height: 1.5;
        }

        .label-title {
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
            margin-left: 20px;
        }

        .qr-container canvas {
            margin-top: 10px;
        }
        
        /* Aseguramos que el logo se quede pegado a la izquierda en la impresión */
        .logo-container {
            width: 100%; /* Utiliza el ancho completo disponible */
            text-align: left; /* Alineación a la izquierda */
            margin-bottom: 10px; /* Un pequeño espacio debajo del logo */
            padding-left: 0; /* Eliminar margen izquierdo si es necesario */
        }

        .logo-container img {
            width: 50%; /* Ajusta el tamaño del logo según sea necesario */
            max-width: 100px; /* Tamaño máximo del logo */
            display: inline-block; /* Se asegura que el logo no esté en bloque */
            margin: 0; /* Elimina el margen alrededor del logo */
            padding-left: 0; /* Elimina el padding izquierdo */
        }
        
        /* Estilo para la etiqueta de impresión */
        .label-container {
            width: 16.5cm; /* Aseguramos que mantenga el tamaño de impresión correcto */
            height: 10cm;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start; /* Alineación vertical */
            box-sizing: border-box;
            font-size: 12px; /* Ajuste el tamaño para la impresión */
        }
        }
    </style>
</head>

<body>
    <?php
    /* Desarrollado por: CCA Consultores TI */
    /* Contacto: contacto@ccaconsultoresti.com */
    /* Actualizado: Agosto-2024 */

    include "../../conexion/conexion.php";
    include "../../assets/barcode/barcode.php";
    $generator = new barcode_generator();
    $rev_id = $_GET['rev_id'];

    $cnx =  Conectarse();
    try {
        // Listado de revolturas con presentaciones
        $listado_revolturas = mysqli_query(
            $cnx,
            "SELECT e.*, p.*,r.cal_id,r.rev_folio,r.rev_fecha,c.cal_descripcion
            FROM rev_revolturas_pt e
            INNER JOIN rev_revolturas r ON e.rev_id = r.rev_id
            INNER JOIN rev_presentacion p ON e.pres_id = p.pres_id
            INNER JOIN rev_calidad c ON c.cal_id = r.cal_id
            WHERE e.rev_id = '$rev_id'"
        );

        // Verificar si hay resultados
        if (mysqli_num_rows($listado_revolturas) > 0) {
            $presentaciones = []; // Array para almacenar las presentaciones únicas

            // Recorrer cada fila de resultados
            while ($datos_revolturas = mysqli_fetch_assoc($listado_revolturas)) {
                $lot = htmlspecialchars($datos_revolturas['rev_folio']);
                $tipo = htmlspecialchars($datos_revolturas['cal_descripcion']);
                $peso_neto = htmlspecialchars($datos_revolturas['pres_kg']);
                $fecha_elaboracion = htmlspecialchars($datos_revolturas['rev_fecha']);
                $pres_id = $datos_revolturas['pres_id']; // ID de la presentación

                $fecha_elaboracion_dt = new DateTime($fecha_elaboracion);
                // Añadir 5 años para obtener la fecha de caducidad
                $fecha_caducidad_dt = clone $fecha_elaboracion_dt; // Clonar para no modificar la original
                $fecha_caducidad_dt->modify('+5 years');

                // Formatear las fechas para mostrarlas
                $fecha_elaboracion_formateada = $fecha_elaboracion_dt->format('d M-Y');
                $fecha_caducidad_formateada = $fecha_caducidad_dt->format('d M-Y');
                // Generar URL para el QR
                $currentDir = dirname($_SERVER['REQUEST_URI']);
                $url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;
                $svg = $generator->render_svg('qr', $url, '');

                // Verificar si la presentación ya está en el array
                if (!in_array($peso_neto, $presentaciones)) {
                    $presentaciones[] = $peso_neto; // Almacenar la presentación única

                    // Imprimir el contenido de la etiqueta
                    echo '<div class="label-container" id="presentacion_' . $pres_id . '">
                        <div class="label">
                            <div class="logo-container">
        <img src="../../imagenes/logo_progel_v5.png" alt="Logo" class="logo">
    </div>
                            <p><span class="label-title">LOTE / BATCH:</span> ' . $lot . '</p>
                            <p><span class="label-title">TIPO / TYPE:</span> ' . $tipo . '</p>
                            <p><span class="label-title">PESO NETO / NET WEIGHT:</span> ' . $peso_neto . ' KG</p>
                            <p><span class="label-title">FECHA DE ELABORACION / MANUFACTURE DATE:</span> ' . $fecha_elaboracion_formateada . '</p>
                            <p><span class="label-title">FECHA DE CADUCIDAD / BEST BEFORE:</span> ' . $fecha_caducidad_formateada . '</p>
                        </div>
                        <div class="qr-container">
                            <p><span class="label-title">Código QR:</span></p>
                            <div>' . $svg . '</div>
                            <button class="btn-imprimir" onclick="imprimirEtiqueta(' . $pres_id . ')">Imprimir Etiqueta</button>
                        </div>
                    </div>';
                }
            }
        } else {
            echo '<p>No se encontraron registros.</p>';
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        mysqli_close($cnx);
    }
    ?>

<script>
    // Función para imprimir la etiqueta
    function imprimirEtiqueta(presId) {
        // Primero, marcamos todos los contenedores para que sean invisibles en la impresión
        const containers = document.querySelectorAll('.label-container');
        containers.forEach(container => {
            container.classList.add('ocultar-en-impr');
        });

        // Seleccionamos el contenedor que corresponde a la presentación a imprimir
        const containerToPrint = document.querySelector('#presentacion_' + presId);
        
        // Lo hacemos visible para la impresión
        containerToPrint.classList.remove('ocultar-en-impr');

        // Imprimir solo la parte visible
        window.print();

        // Restauramos la visibilidad de los contenedores después de la impresión
        containers.forEach(container => {
            container.classList.remove('ocultar-en-impr');
        });
    }
</script>

<style>
    /* Estilos de impresión */
    @media print {
        .ocultar-en-impr {
            display: none !important; /* Oculta los elementos innecesarios en la impresión */
        }
    }
</style>


</body>

</html>
