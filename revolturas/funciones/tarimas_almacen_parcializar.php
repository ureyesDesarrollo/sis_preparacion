<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";


$cnx = Conectarse();
try {
    $tar_id = $_POST['tar_id'];
    $kilos_parcializar = $_POST['kilos_parcializar'];
    if ($kilos_parcializar != 0) {
        $query = "SELECT * FROM rev_tarimas WHERE tar_id = '$tar_id'";
        $resultado = mysqli_query($cnx, $query);
        if (mysqli_num_rows($resultado) > 0) {
            $tarima_original = mysqli_fetch_assoc($resultado);

            // Obtenemos el valor de tar_kilos
            $tar_kilos = $tarima_original['tar_kilos'];
            // Obtenemos el valor de kilos que se van a parcializar
            $kilos_parcializar = $_POST['kilos_parcializar'];

            if($kilos_parcializar > $tar_kilos){
                $res = "No puedes parcializar más de lo que tienes en la tarima";
                echo json_encode(["error" => $res]);
            }else{
                // Creamos una copia de la tarima con el valor de tar_kilos parcializado
            $query_copia = "INSERT INTO rev_tarimas (
                pro_id,
                tar_folio,
                niv_id,
                usu_id,
                tar_fecha,
                tar_color,
                tar_redox,
                tar_ph,
                tar_trans,
                tar_porcentaje_t,
                tar_bloom,
                tar_viscosidad,
                cal_id,
                tar_rendimiento,
                tar_olor,
                tar_ntu,
                tar_humedad,
                tar_cenizas,
                tar_ce,
                tar_fino,
                tar_pe_1kg,
                tar_par_extr,
                tar_par_ind,
                tar_hidratacion,
                tar_malla_30,
                tar_malla_45,
                tar_fe_param,
                tar_rechazado,
                tar_estatus,
                tar_kilos
            ) VALUES (
                '{$tarima_original['pro_id']}',
                '{$tarima_original['tar_folio']}',
                '{$tarima_original['niv_id']}',
                '{$tarima_original['usu_id']}',
                '{$tarima_original['tar_fecha']}',
                '{$tarima_original['tar_color']}',
                '{$tarima_original['tar_redox']}',
                '{$tarima_original['tar_ph']}',
                '{$tarima_original['tar_trans']}',
                '{$tarima_original['tar_porcentaje_t']}',
                '{$tarima_original['tar_bloom']}',
                '{$tarima_original['tar_viscosidad']}',
                '{$tarima_original['cal_id']}',
                '{$tarima_original['tar_rendimiento']}',
                '{$tarima_original['tar_olor']}',
                '{$tarima_original['tar_ntu']}',
                '{$tarima_original['tar_humedad']}',
                '{$tarima_original['tar_cenizas']}',
                '{$tarima_original['tar_ce']}',
                '{$tarima_original['tar_fino']}',
                '{$tarima_original['tar_pe_1kg']}',
                '{$tarima_original['tar_par_extr']}',
                '{$tarima_original['tar_par_ind']}',
                '{$tarima_original['tar_hidratacion']}',
                '{$tarima_original['tar_malla_30']}',
                '{$tarima_original['tar_malla_45']}',
                '{$tarima_original['tar_fe_param']}',
                '{$tarima_original['tar_rechazado']}',
                '{$tarima_original['tar_estatus']}',
                '$kilos_parcializar'
            )";
        
                    // Actualizamos los kilos restantes en la tarima original
                    $kilos_restantes = $tar_kilos - $kilos_parcializar;
                    $query_update = "UPDATE rev_tarimas SET tar_kilos = '$kilos_restantes' WHERE tar_id = '$tar_id'";
                    mysqli_query($cnx, $query_update);
                    mysqli_query($cnx, $query_copia);
                    $res = "Tarima parcializada correctamente";
                    ins_bit_acciones($_SESSION['idUsu'], 'E', $tar_id, '41');
                    echo json_encode(["success" => $res]);
            }
        } else {
            $res = "No se encontró la tarima con el folio" . $tarima_original['tar_folio'];
            echo json_encode(["error" => $res]);
        }
    } else {
        $res = "Los kilos deben ser mayor que 0.";
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
