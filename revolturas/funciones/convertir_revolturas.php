<?php
include "../../conexion/conexion.php";
include "../../seguridad/user_seguridad.php";

$conexion = Conectarse();

$rev_id = $_POST['rev_id'];
$usu_id = $_SESSION['idUsu'];

mysqli_begin_transaction($conexion);

try {


  /*
    ==========================================
    1️⃣ OBTENER REVOLTURA
    ==========================================
    */

  $sql_rev = "SELECT * FROM rev_revolturas WHERE rev_id = $rev_id";
  $res_rev = mysqli_query($conexion, $sql_rev);

  if (mysqli_num_rows($res_rev) == 0) {
    throw new Exception("Revoltura no encontrada");
    exit;
  }

  $rev = mysqli_fetch_assoc($res_rev);

  if ($rev['rev_count_etiquetado'] == 0) {
    throw new Exception("Esta revoltura ya fue convertida anteriormente.");
  }

  /*
    ==========================================
    2️⃣ OBTENER POSICIONES
    ==========================================
    */

  $sql_pos = "SELECT *
                FROM rev_nivel_posicion_detalle
                WHERE rev_id = $rev_id";

  $res_pos = mysqli_query($conexion, $sql_pos);

  if (mysqli_num_rows($res_pos) == 0) {
    throw new Exception("No hay posiciones asignadas");
    exit;
  }

  /*
    ==========================================
    3️⃣ FECHA 1° ENERO DEL AÑO ACTUAL
    ==========================================
    */

  $anio_actual = date("Y");
  $fecha_tarima = $anio_actual . "-01-01";

  /*
    ==========================================
    4️⃣ CREAR TARIMAS
    ==========================================
    */

  $contador = 1;

  while ($pos = mysqli_fetch_assoc($res_pos)) {

    $tar_folio = $rev['rev_folio'] . "-" . $contador;
    $niv_id = $pos['niv_id'];
    $cantidad = 1000;

    $sql_insert = "
        INSERT INTO rev_tarimas (
            pro_id, tar_folio, niv_id, usu_id, tar_fecha,
            tar_color, tar_redox, tar_ph, tar_trans,
            tar_porcentaje_t, tar_bloom, tar_viscosidad, cal_id,
            tar_rendimiento, tar_olor, tar_ntu,
            tar_humedad, tar_cenizas, tar_ce, tar_fino,
            tar_pe_1kg, tar_par_extr, tar_par_ind,
            tar_hidratacion, tar_malla_30, tar_malla_45,
            tar_coliformes, tar_ecoli, tar_salmonella,
            tar_saereus, tar_fe_param, tar_rechazado,
            tar_estatus, tar_kilos, tar_count_etiquetado
        )
        VALUES (
            0,
            '$tar_folio',
            '$niv_id',
            '$usu_id',
            '$fecha_tarima',

            '{$rev['rev_color']}',
            '{$rev['rev_redox']}',
            '{$rev['rev_ph']}',
            '{$rev['rev_trans']}',
            '{$rev['rev_porcentaje_t']}',
            '{$rev['rev_bloom']}',
            '{$rev['rev_viscosidad']}',
            '{$rev['cal_id']}',

            0,
            '{$rev['rev_olor']}',
            '{$rev['rev_ntu']}',
            '{$rev['rev_humedad']}',
            '{$rev['rev_cenizas']}',
            '{$rev['rev_ce']}',
            '{$rev['rev_fino']}',

            '{$rev['rev_pe_1kg']}',
            '{$rev['rev_par_extr']}',
            '{$rev['rev_par_ind']}',
            '{$rev['rev_hidratacion']}',
            '{$rev['rev_malla_30']}',
            '{$rev['rev_malla_45']}',

            0,0,0,0,
            '$fecha_tarima',
            'C',
            1,
            '$cantidad',
            1
        )
        ";

    if (!mysqli_query($conexion, $sql_insert)) {
      throw new Exception("Error al insertar tarima: " . mysqli_error($conexion));
      exit;
    }

    $tar_id = mysqli_insert_id($conexion);

    /*
        ==========================================
        5️⃣ ACTUALIZAR POSICIÓN
        ==========================================
        */

    $sql_update_pos = "
        UPDATE rev_nivel_posicion_detalle
        SET
            tipo = 'tarima',
            tar_id = $tar_id,
            rev_id = NULL
        WHERE nvd_id = {$pos['nvd_id']}
        ";

    if (!mysqli_query($conexion, $sql_update_pos)) {
      throw new Exception("Error al actualizar posición");
    }

    $contador++;
  }

  /*
    ==========================================
    6️⃣ ACTUALIZAR REVOLTURA
    ==========================================
    */

  $sql_update_rev = "
    UPDATE rev_revolturas
    SET
        rev_count_etiquetado = 0
    WHERE rev_id = $rev_id
    ";

  if (!mysqli_query($conexion, $sql_update_rev)) {
    throw new Exception("Error al actualizar revoltura");
  }

  mysqli_commit($conexion);

  echo json_encode([
    "status" => "success",
    "message" => "Revoltura convertida correctamente"
  ]);
} catch (Exception $e) {

  mysqli_rollback($conexion);

  echo json_encode([
    "status" => "error",
    "message" => $e->getMessage()
  ]);
}
