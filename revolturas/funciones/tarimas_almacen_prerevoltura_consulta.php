<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

function construirConsulta($filtros) {
    $consultaBase = "SELECT t.tar_id, t.tar_folio, t.pro_id, t.pro_id_2, t.tar_kilos, 
    t.tar_bloom, t.tar_viscosidad, t.tar_trans, t.tar_malla_45, 
    DATE(t.tar_fecha) as tar_fecha, c.cal_descripcion 
    FROM rev_tarimas t INNER JOIN rev_calidad c  ON t.cal_id = c.cal_id WHERE ";
    $condiciones = [];

    foreach($filtros as $filtro){
        $columna = $filtro['Parametro'];
        $operador = $filtro['Comparacion'];
        $valor = $filtro['Valor'];

        //Construimos las condiciones
        $condiciones[] = "t.$columna $operador '$valor'";
    }

    //Unimos las condiciones
    $consultaBase .= implode(" AND ",$condiciones);
    $consultaBase .= " AND t.tar_estatus = 1 AND t.tar_count_etiquetado > 0 ORDER BY t.tar_fecha ASC";
    return $consultaBase;
}


try{
    $tarima = $_POST['tarima'];
    $paramteros = $_POST['parametros'];
    $sql = construirConsulta($paramteros);

    $listado_tarimas = mysqli_query($cnx,$sql);

    $tarimas = array();
    while($fila = mysqli_fetch_assoc($listado_tarimas)){
        $tarimas[] = $fila;
    }

    echo json_encode($tarimas);
}catch(Exception $e){
    echo $e -> getMessage();
}
?>