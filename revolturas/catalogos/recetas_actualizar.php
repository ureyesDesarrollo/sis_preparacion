<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try{
    $rre_descripcion = $_POST['rre_descripcion'];
    $tarimas = $_POST['rrd_no_tarima'];
    $parametros = $_POST['rp_id'];
    $valores = $_POST['rp_valor'];
    $signos = $_POST['rrd_signo'];
    $rre_id = $_POST['id_receta'];

    $tarimas_validacion = array_unique($tarimas);

    if(count($tarimas_validacion) < 2){
        echo json_encode(["error" => "Debes agregar al menos dos números de tarima."]);
        exit;
    }

    
    //Actualizacion de datos de receta
    $update_receta = "UPDATE rev_receta SET rre_descripcion = '$rre_descripcion' WHERE rre_id = '$rre_id'";
    //Eliminacion de detalle por receta
    $eliminar_detalle = "DELETE FROM rev_receta_detalle WHERE rre_id = '$rre_id'";
    
    $cnx->begin_transaction();

    if(mysqli_query($cnx,$update_receta) && mysqli_query($cnx,$eliminar_detalle)){
        $res = 'Receta actualizada correctamente';
        // Inserción de los detalles en rev_receta_detalle
        foreach ($tarimas as $index => $tarima) {
            $rp_id = $parametros[$index] ?? null; // Parámetro correspondiente a esta tarima
            $rp_valor = $valores[$index] ?? null; // Valor correspondiente a este parámetro
            $rrd_signo = $signos[$index] ?? null; 

            // Verificar que existan los datos requeridos antes de continuar
            if ($rp_id !== null && $rp_valor !== null) {
                $sql_detalle = "INSERT INTO rev_receta_detalle (rrd_no_tarima, rp_id, rp_valor, rre_id,rrd_signo) 
                                VALUES ('$tarima', '$rp_id', '$rp_valor', '$rre_id','$rrd_signo')";
                mysqli_query($cnx, $sql_detalle);
            }
        }
        $cnx->commit();
        echo json_encode(["success" => $res]);
    }
    
}catch(Exception $e){
    echo json_encode(["error" => $e->getMessage()]);
    $cnx->rollback();
} finally {
    mysqli_close($cnx);
}