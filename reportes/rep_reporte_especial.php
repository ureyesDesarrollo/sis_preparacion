<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

require '../conexion/conexion.php';

$cnx = Conectarse();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $action = $_POST['action'];

    switch ($action) {

        case 'inventario':
            $resultado = mysqli_query($cnx, "SELECT i.prv_id as 'clave prov', v.prv_nombre as 'proveedor', i.mat_id as 'clave mat', 
            m.mat_nombre as 'material', i.inv_fecha as 'fe. inventario', i.inv_calidad as 'calidad', 
            i.inv_calcios as 'calcios', i.inv_humedad as 'numedad', i.inv_alcalinidad as 'alcalinidad', 
            i.inv_extrac as 'extractibilidad', i.inv_kg_totales as 'kilos', i.prv_recibe as 'clave prov maq', 
            x.prv_nombre as 'proveedor maquila', p.pro_id as 'proceso', p.pro_molino1, p.pro_molino2, p.pro_fe_carga as 'fe. carga', p.pro_fe_termino as 'fe. termino',  
            p.hrs_totales_calculadas as 'hrs tot calculadas', p.hrs_totales_capturadas as 'hrs tot capturadas'
            from inventario as i
            inner JOIN proveedores as v on (i.prv_id = v.prv_id)
            INNER JOIN materiales as m on (i.mat_id = m.mat_id)
            left JOIN proveedores as x on (i.prv_recibe = x.prv_id)
            INNER JOIN procesos_materiales as r on (i.inv_id = r.inv_id)
            INNER JOIN procesos as p on (r.pro_id = p.pro_id)
            WHERE p.pro_fe_carga BETWEEN '$fecha_inicio' AND '$fecha_fin'");
            generarArchivo($resultado, 'resultado_inventario.csv');
            break;

        case 'procesos':
            $resultado = mysqli_query($cnx, "SELECT p.pro_id as 'proceso', t.pt_descripcion AS 'tipo preparacion', 
            e.pe_descripcion AS 'etapa sis', e.pe_nombre AS 'etapa', l.prol_hr_totales AS 'hrs totales', 
            l.extractibilidad AS 'extractibilidad', l.prol_adelgasamiento AS 'adelgasamiento', l.prol_ce AS 'CE', 
            l.prol_color AS 'color', l.prol_ph as 'ph', b.prol_cocido_ph1 AS 'cocido ph 1', b.prol_ce1 AS 'CE 1', b.prol_cocido_ph2 AS 'cocido ph 2', 
            b.prol_ce2 AS 'CE 2', b.prol_cocido_lib AS 'cocido lib', b.prol_color_caldo AS 'color caldo', b.prol_color AS 'color lib', 
            b.prol_solides AS 'solides', b.prol_por_extrac AS 'extractibilidad lib', b.prol_hr_totales as 'hrs totales' , c.prol_por_extrac as 'extractibilidad lib2' FROM procesos AS p 
            INNER JOIN preparacion_tipo AS t ON (p.pt_id = t.pt_id) INNER JOIN preparacion_tipo_etapas AS  m ON (t.pt_id = m.pt_id) 
            INNER JOIN preparacion_etapas AS e ON (m.pe_id = e.pe_id) 
            LEFT OUTER JOIN procesos_liberacion AS l ON (p.pro_id = l.pro_id AND e.pe_id = l.pe_id) 
            LEFT OUTER JOIN procesos_liberacion_b AS b ON (p.pro_id = b.pro_id AND e.pe_id = b.pe_id) 
            LEFT OUTER JOIN procesos_liberacion_b_cocidos as c on(b.prol_id = c.prol_id)
            WHERE p.pro_fe_carga BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY p.pro_id asc");
            generarArchivo($resultado, 'resultado_procesos.csv');
            break;

        case 'revolturas':
            //Aqui va la consulta
            $resultado = mysqli_query($cnx, "SELECT p.pro_id AS 'proceso', t.*
            FROM procesos p
            INNER JOIN procesos_agrupados AS a ON p.pro_id = a.pro_id
            INNER JOIN lotes_anio AS l ON l.lote_id = a.lote_id
            INNER JOIN rev_tarimas AS t ON t.pro_id = a.pro_id
            WHERE p.pro_fe_carga BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY p.pro_id ASC");

            //Aqui se llama la función de generar archivo,
            //recibe el resultado de la consulta y el nombre del archivo
            generarArchivo($resultado, 'resultado_revolturas.csv');
            break;

        case 'quimicos':
            $resultado = mysqli_query($cnx, "SELECT p.pro_id as 'proceso', e.pe_descripcion as 'etapa sis', e.pe_nombre as 'etapa',q.quimico_descripcion as quimico, qe.quim_litros as 'litros', qe.quim_lote as 'lote'
            FROM procesos p
            INNER JOIN preparacion_tipo as t on (p.pt_id = t.pt_id)
            INNER JOIN preparacion_tipo_etapas as m on (t.pt_id = m.pt_id)
            INNER JOIN preparacion_etapas as e on (m.pe_id = e.pe_id)
            INNER JOIN quimicos_etapas as qe ON (p.pro_id = qe.pro_id and m.pe_id = qe.pe_id)
            INNER JOIN quimicos q ON q.quimico_id = qe.quimico_id
            WHERE p.pro_fe_carga BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY p.pro_id ASC");

            generarArchivo($resultado, 'resultado_quimicos.csv');
            break;
    }
}

function generarArchivo($resultado, $filename)
{
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment;filename={$filename}");
    $salida = fopen('php://output', 'w');

    // Obtener y escribir los nombres de las columnas como encabezados
    $encabezados = array();
    while ($campo = mysqli_fetch_field($resultado)) {
        $encabezados[] = $campo->name;
    }
    fputcsv($salida, $encabezados);

    // Escribir los datos de la consulta
    while ($fila = mysqli_fetch_assoc($resultado)) {
        fputcsv($salida, $fila);
    }

    fclose($salida);
}
