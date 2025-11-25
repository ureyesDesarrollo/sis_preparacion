<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

#include "../conexion/conexion.php";
#include "../funciones/funciones.php";
//include "../../../funciones/funciones_procesos.php";
#include "../seguridad/user_seguridad.php";
$cnx =  Conectarse();
extract($_GET);

if (isset($inv_id)) {

    #consulta de encabezado desde bitacora
    $cad_pelambre = mysqli_query($cnx, "SELECT * FROM inventario_pelambre
WHERE inv_id = '$inv_id'") or die(mysqli_error($cnx) . "Error: en consultar pelambre");
    $reg_pelambre = mysqli_fetch_assoc($cad_pelambre);

    $int_valor = $reg_pelambre['ip_id'];

    #Obtener el inventario y material del pelambre
    $inventario = mysqli_query($cnx, "SELECT inv_no_ticket, inv_kilos ,fnc_nombre_material (inv_id) as material FROM 
inventario WHERE inv_id ='" . $reg_pelambre['inv_id'] . "'");
    $inventario = mysqli_fetch_assoc($inventario);
    $cad_equipos = mysqli_query($cnx, "SELECT ep_descripcion FROM equipos_preparacion
WHERE  ep_id = " . $reg_pelambre['ep_id'] . "") or die(mysqli_error($cnx) . "Error: en consultar equipos");
    $reg_equipos = mysqli_fetch_assoc($cad_equipos);

    #En el encabezo usa el nombre listado pelambre, se reutilizo el nombre para usarlo en varias en las dos bitacoras
    $listado_pelambre = $reg_pelambre;
    $listado_pelambre['ip_fecha_envio'] = substr($listado_pelambre['ip_fecha_envio'], 0, 10);
    $listado_pelambre['ip_fecha_remojo'] = substr($listado_pelambre['ip_fecha_remojo'], 0, 10);
    $listado_pelambre['ep_descripcion'] = $reg_equipos['ep_descripcion'];
    $listado_pelambre['inv_no_ticket'] = $inventario['inv_no_ticket'];
    $listado_pelambre['inv_kilos'] = $inventario['inv_kilos'];
    $listado_pelambre['material'] = $inventario['material'];
}

?>
<html>

<table width="100%" style="margin:20px 0px 20px 0px; border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">Lavador</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Ticket</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Kilos</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Material</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $listado_pelambre['ep_descripcion'] ?></td>
            <td><?php echo $listado_pelambre['inv_no_ticket'] ?></td>
            <td><?php echo $listado_pelambre['inv_kilos'] ?></td>
            <td><?php echo $listado_pelambre['material'] ?></td>
            <td><?php echo $listado_pelambre['ip_fecha_envio'] ?></td>
        </tr>
    </tbody>
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">Inicio de carga</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Inico termina carga</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha de remojo</td>
            <td style="background-color: #E4E4E5; font-weight: bold;" colspan="2">Hora inicio remojo</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $listado_pelambre['ip_hora_ini_carga'] ?></td>
            <td><?php echo $listado_pelambre['ip_hora_fin_carga'] ?></td>
            <td><?php echo $listado_pelambre['ip_fecha_remojo'] ?></td>
            <td colspan="2"><?php echo $listado_pelambre['ip_hora_ini_remojo'] ?></td>
        </tr>
    </tbody>
</table>



</html>