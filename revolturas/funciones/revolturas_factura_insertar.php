<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        extract($_POST);
        $insertar_factura = "UPDATE rev_revolturas SET rev_factura = '$rev_factura' WHERE rev_id = '$rev_id'";
        if (!mysqli_query($cnx, $insertar_factura)) {
            throw new Exception("Error al insertar en rev_revolturas: " . $cnx->error);
        }
        $res = "Factura agregada correctamente";
            ins_bit_acciones($_SESSION['idUsu'], 'E', $rev_id, '46');
            echo json_encode(["success" => $res]);
    }catch(Exception $e){
        echo json_encode(["error" => $e->getMessage()]);
    }finally {
        mysqli_close($cnx);
    }
    
}