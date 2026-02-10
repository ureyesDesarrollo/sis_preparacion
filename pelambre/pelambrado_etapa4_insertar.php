<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: info@ccaconsultoresti.com */
/* Actualizado: Diciembre-2023 */

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();
extract($_POST);
$ban = '';
for ($i = 1; $i <= 4; $i++) {
    $hdd_id = ${"hdd_id" . $i};
    $renglon = ${"txt_renglon" . $i};
    $ipe_fe_inicio = valor_o_null(${"txt_fe_ini_lav" . $i});
    $ipe_hr_inicio  = valor_o_null(${"txt_hora_ini" . $i});
    $ipe_hr_fin = valor_o_null(${"txt_hora_fin" . $i});
    $ipe_ph = valor_o_null(${"txt_ph_ag" . $i});
    $ipe_ce = valor_o_null(${"txt_ce_ag" . $i});

    if ($ipe_fe_inicio != 'NULL' and $ipe_hr_inicio != 'NULL' and $ipe_hr_fin != 'NULL' and $ipe_ph != 'NULL' and $ipe_ce != 'NULL' and $hdd_id == '') {

        $query = "INSERT INTO inventario_pelambre_etapas_2 (
                ipe_ren, ip_id, ipe_etapa, ipe_fe_inicio, ipe_hr_inicio, ipe_hr_fin, 
                ipe_ph, ipe_ce, usu_id
            ) VALUES (
                $renglon, $hdd_id_pelambre, 4, $ipe_fe_inicio, $ipe_hr_inicio, $ipe_hr_fin, 
                $ipe_ph, $ipe_ce, " . $_SESSION['idUsu'] . ")";

        $res = mysqli_query($cnx, $query);
        $ban = 'ok';
    }
}
if ($ban == 'ok') {
    $respuesta = array('mensaje' => "Registro realizado");
} else {
    $respuesta = array('mensaje' => "Campos vacios, registre el renglon completo");
}
echo json_encode($respuesta);

function valor_o_null($variable)
{
    return ($variable == '') ? 'NULL' : "'$variable'";
}
