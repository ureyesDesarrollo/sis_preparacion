<?php
require '../../conexion/conexion.php';
require '../funciones_procesos.php';
header("Content-Type: application/json");
$cnx = Conectarse();
$pe_id = mysqli_real_escape_string($cnx, $_POST['pe_id']);
$pro_id = mysqli_real_escape_string($cnx, $_POST['pro_id']);

try {
  mysqli_autocommit($cnx, false); // Iniciar transacción

  for ($i = 1; $i <= 7; $i++) {
    $fecha   = mysqli_real_escape_string($cnx, $_POST["txt_fecha$i"] ?? '');
    $quimico = mysqli_real_escape_string($cnx, $_POST["cbx_quimico$i"] ?? '');
    $lote    = mysqli_real_escape_string($cnx, $_POST["txt_lote_quim$i"] ?? '');
    $litros  = mysqli_real_escape_string($cnx, $_POST["txt_litro_quim$i"] ?? '');
    $id_existente = mysqli_real_escape_string($cnx, $_POST["quimico_id_existente$i"] ?? '');

    // Si no hay datos, omitir esta fila
    if (empty($quimico) && empty($lote) && empty($litros)) continue;

    if (!empty($id_existente)) {
      // Actualizar químico ya registrado
      $query_update = "UPDATE quimicos_etapas 
                       SET quimico_id = '$quimico', 
                           quim_lote = '$lote', 
                           quim_litros = '$litros', 
                           quim_fecha = '$fecha' 
                       WHERE quim_id = '$id_existente'";
      if (!mysqli_query($cnx, $query_update)) {
        throw new Exception("Error al actualizar químico fila $i: " . mysqli_error($cnx));
      }
    } elseif (!empty($quimico)) {
      // Insertar nuevo químico
      $query_insert = "INSERT INTO quimicos_etapas (pro_id, pe_id, quimico_id, quim_lote, quim_litros, quim_fecha,usu_id) 
                       VALUES ('$pro_id', '$pe_id', '$quimico', '$lote', '$litros', '$fecha','{$_SESSION['idUsu']}')";
      if (!mysqli_query($cnx, $query_insert)) {
        throw new Exception("Error al insertar químico fila $i: " . mysqli_error($cnx));
      }
    }
  }

  mysqli_commit($cnx);
  echo json_encode(['success' => true, 'mensaje' => "¡Datos guardados correctamente!"]);
} catch (Exception $e) {
  mysqli_rollback($cnx);
  echo json_encode(['success' => false, 'mensaje' => "Error: " . $e->getMessage()]);
} finally {
  mysqli_close($cnx);
}
