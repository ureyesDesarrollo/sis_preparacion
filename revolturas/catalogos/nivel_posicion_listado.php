<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $sql = "SELECT n.*, r.rac_descripcion FROM rev_nivel_posicion n 
        INNER JOIN rev_racks r ON r.rac_id = n.rac_id";
    } else {
        if (isset($_POST['rac_id'])) {
            $rac_id = $_POST['rac_id'];
            $sql = "SELECT * FROM rev_nivel_posicion WHERE rac_id = '$rac_id'";
        }
    }

    $listado_nivel_posicion = mysqli_query($cnx, $sql);
    if (!$listado_nivel_posicion) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_nivel_posicion = array();

    while ($fila = mysqli_fetch_assoc($listado_nivel_posicion)) {
        $datos_nivel_posicion[] = $fila;
    }

    $json_nivel_posicion = json_encode($datos_nivel_posicion);

    echo $json_nivel_posicion;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
