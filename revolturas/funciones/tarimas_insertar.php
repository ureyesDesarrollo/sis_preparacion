<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        function proceso2Handler($cnx, $pro_id_2, $tar_id)
        {
            if($pro_id_2){
                $agregar_proceso_2 = "UPDATE rev_tarimas SET pro_id_2 = '$pro_id_2' WHERE tar_id = '$tar_id'";
                mysqli_query($cnx, $agregar_proceso_2);
            }
        }

        $pro_id = mysqli_real_escape_string($cnx, $_POST['pro_id']);
        $pro_id_2 = null;
        if (strpos($pro_id, '/') !== false) {
            $pro_id_2 = explode("/", $pro_id)[1];
        }

        $tar_folio = mysqli_real_escape_string($cnx, $_POST['tar_folio']);
        $tar_kilos = mysqli_real_escape_string($cnx, $_POST['tar_kilos']);
        $usu_id = mysqli_real_escape_string($cnx, $_SESSION['idUsu']);
        $pros_terminado = isset($_POST['chk_estatus']) ? 1 : 0;
        $tar_fino = isset($_POST['chk_fino']) ? 'F' : 'N';

        if ($tar_kilos > 0 && $tar_kilos <= 1000) {
            $sql = "INSERT INTO rev_tarimas (pro_id,tar_folio,usu_id,tar_fino,tar_kilos)
            VALUES ('$pro_id','$tar_folio','$usu_id','$tar_fino','$tar_kilos')";

            /* termina proceso */
            $sql_terminar_proceso = "UPDATE lotes_anio SET lote_estatus = '3' WHERE lote_id = (select lote_id from procesos_agrupados where pro_id = '$pro_id')";

            if (mysqli_query($cnx, $sql)) {
                $tar_id = $cnx->insert_id;
                if($pro_id == '1' || $pro_id == '2' || $pro_id == '3'){
                    $sql_enviar_venta = "UPDATE rev_tarimas SET tar_estatus = '8' WHERE tar_id = '$tar_id'";
                    mysqli_query($cnx, $sql_enviar_venta);
                }
                $res = "Nuevo registro creado exitosamente. ";
                ins_bit_acciones($_SESSION['idUsu'], 'A', $tar_id, '41');
                echo json_encode(["success" => $res, "tar_id" => $tar_id]);
                proceso2Handler($cnx, $pro_id_2, $tar_id, $pros_terminado);

                if ($pros_terminado === 1) {
                    // Si $pro_id_2 no está definido o está vacío
                    if (empty($pro_id_2)) {
                        // Ejecutar $sql_terminar_proceso
                        if (mysqli_query($cnx, $sql_terminar_proceso)) {
                            // Calcular rendimiento
                            $kilos_tarimas_result = mysqli_query($cnx, "SELECT SUM(tar_kilos) AS kilosTarimas FROM rev_tarimas WHERE pro_id = '$pro_id'");
                            $tot_kig_tar = mysqli_fetch_assoc($kilos_tarimas_result);

                            $resultProceso = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, m.mat_ingreso, inv.inv_kilos
                                                                FROM procesos_materiales AS pm
                                                                INNER JOIN inventario AS inv ON inv.inv_id = pm.inv_id 
                                                                INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
                                                                WHERE pm.pro_id = '$pro_id'");

                            $kgProceso = 0;

                            // Sumar kilos del proceso
                            while ($resProceso = mysqli_fetch_assoc($resultProceso)) {
                                $kgProceso += ($resProceso['mat_ingreso'] == 'N') ? (float)$resProceso['pma_kg'] : (float)$resProceso['inv_kilos'];
                            }

                            // Calcular rendimiento
                            $rendimiento = $kgProceso ? ($tot_kig_tar['kilosTarimas'] / $kgProceso) : 0;
                            $rendimiento = number_format($rendimiento, 4);

                            // Actualizar rendimiento en la base de datos
                            $sql_rendimiento = "UPDATE rev_tarimas SET tar_rendimiento = '$rendimiento' WHERE pro_id = '$pro_id'";
                            mysqli_query($cnx, $sql_rendimiento);
                        }
                    } else {
                        // Si $pro_id_2 está definido, cerrar el proceso normalmente
                        mysqli_query($cnx, $sql_terminar_proceso);
                    }
                } else {
                    exit();
                }

            } else {
                $res = $sql . "<br>" . mysqli_error($cnx);
                echo json_encode(["error" => $res]);
            }
        } else {
            echo json_encode(["error" => "La cantidad de kilos debe ser mayor que 0 y menor o igual a 1000."]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
