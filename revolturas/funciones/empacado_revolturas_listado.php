<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

try {
    $listado_presenta = mysqli_query($cnx, "SELECT rp.*,p.pres_descrip,p.pres_kg FROM rev_revolturas_pt rp JOIN rev_presentacion p ON rp.pres_id = p.pres_id WHERE rp.rev_id ='" . $_POST['rev_id'] . "'");
    $datos_presenta = array();

    while ($fila = mysqli_fetch_assoc($listado_presenta)) {
        $datos_presenta[] = $fila;
    }

    $json_presenta = json_encode($datos_presenta);

    echo $json_presenta;
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
