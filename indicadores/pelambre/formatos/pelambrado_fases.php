<?php
$cad_fase1 = mysqli_query($cnx, "SELECT * FROM inventario_pelambre_etapas_1
WHERE ipe_etapa = '1'") or die(mysqli_error($cnx) . "Error: en consultar equipos");
$reg_fase1 = mysqli_fetch_assoc($cad_fase1);
$tot1 = mysqli_num_rows($cad_fase1);

$cad_fase2 = mysqli_query($cnx, "SELECT * FROM inventario_pelambre_etapas_1
WHERE ipe_etapa = '2' LIMIT 0,7") or die(mysqli_error($cnx) . "Error: en consultar equipos2");
$reg_fase2 = mysqli_fetch_assoc($cad_fase2);
$tot2 = mysqli_num_rows($cad_fase2);


$cad_fase4 = mysqli_query($cnx, "SELECT * FROM inventario_pelambre_etapas_2
WHERE ipe_etapa = '4' order by ipe_ren") or die(mysqli_error($cnx) . "Error: en consultar equipos");
$reg_fase4 = mysqli_fetch_assoc($cad_fase4);
$tot4 = mysqli_num_rows($cad_fase4);

$cad_fase5 = mysqli_query($cnx, "SELECT * FROM inventario_pelambre_etapas_2
WHERE ipe_etapa = '5' order by ipe_ren") or die(mysqli_error($cnx) . "Error: en consultar equipos");
$reg_fase5 = mysqli_fetch_assoc($cad_fase5);
$tot5 = mysqli_num_rows($cad_fase5);
?>
<!-- -------------------------------------------------------------- -->
<style>
    #etiqueta_niveles {
        font-size: 12px;
        font-weight: bold;
        background-color: yellow;
    }
</style>
<nav aria-label="breadcrumb" style="font-weight:bolder">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">ETAPA REMOJO</li>
    </ol>
</nav>
<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">%</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Cantidad</td>
            <td style="background-color: #E4E4E5; font-weight: bold;"></td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Material</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Horas</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Minutos</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha/hora inicio</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha/hora final</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Obs</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($tot1 > 0) {
            do {
                $cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos
                WHERE quimico_id = " . $reg_fase1['quim_id'] . "") or die(mysqli_error($cnx) . "Error: en consultar equipos 3");
                $quim_id = mysqli_fetch_assoc($cad_quim);
        ?>
                <tr>
                    <td><?php echo $reg_fase1['ipe_porcentaje'] ?></td>
                    <td><?php echo $reg_fase1['ipe_cantidad'] ?></td>
                    <td>Litros</td>
                    <td><?php echo $quim_id['quimico_descripcion'] ?></td>
                    <td><?php echo $reg_fase1['ipe_horas'] ?></td>
                    <td><?php echo $reg_fase1['ipe_minutos'] ?></td>
                    <td><?php echo $reg_fase1['ipe_fe_hr_inicio'] ?></td>
                    <td><?php echo $reg_fase1['ipe_fe_hr_fin'] ?></td>
                    <td><?php echo $reg_fase1['ipe_observaciones'] ?></td>
                </tr>
        <?php
            } while ($reg_fase1 = mysqli_fetch_assoc($cad_fase1));
        } else {
            echo '<tr>
            <td colspan="10" style="text-align:center">No hay registros</td></tr>';
        }
        ?>
    </tbody>
</table>

<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">Hora termina remojo</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $reg_pelambre['ip_fe_hr_ter_remojo'] ?></td>
        </tr>
    </tbody>
</table>

<!-- -------------------------------------------------------------- -->
<nav aria-label="breadcrumb" style="font-weight:bolder">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">ETAPA PELAMBRE</li>
    </ol>
</nav>

<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <th style="background-color: #E4E4E5; font-weight: bold;">%</th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Cantidad</th>
            <th style="background-color: #E4E4E5; font-weight: bold;"></th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Material</th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Horas</th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Minutos</th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Fecha/hora inicio</th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Fecha/hora final</th>
            <th style="background-color: #E4E4E5; font-weight: bold;">Obs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($tot2 > 0) {
            do {
                $cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos
                WHERE quimico_id = " . $reg_fase2['quim_id'] . " ") or die(mysqli_error($cnx) . "Error: en consultar equipos 3");
                $quim_id = mysqli_fetch_assoc($cad_quim);
        ?>
                <tr>
                    <td><?php echo $reg_fase2['ipe_porcentaje'] ?></td>
                    <td><?php echo $reg_fase2['ipe_cantidad'] ?></td>
                    <td>Litros</td>
                    <td><?php echo $quim_id['quimico_descripcion'] ?></td>
                    <td><?php echo $reg_fase2['ipe_horas'] ?></td>
                    <td><?php echo $reg_fase2['ipe_minutos'] ?></td>
                    <td><?php echo $reg_fase2['ipe_fe_hr_inicio'] ?></td>
                    <td><?php echo $reg_fase2['ipe_fe_hr_fin'] ?></td>
                    <td><?php echo $reg_fase2['ipe_observaciones'] ?></td>
                </tr>
        <?php
            } while ($reg_fase2 = mysqli_fetch_assoc($cad_fase2));
        } else {
            echo '<tr>
            <td colspan="10" style="text-align:center">No hay registros</td></tr>';
        }
        ?>
        <tr>
            <td colspan="9">
                <div class="row g-3 align-items-center">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <label for="formFile" class="form-label" id="etiqueta_niveles">CHECAR LIMPIEZA DE PELO</label>
                    </div>
                </div>
            </td>
        </tr>
        <?php
        $cad_fase2_c = mysqli_query($cnx, "SELECT * FROM inventario_pelambre_etapas_1 WHERE ipe_etapa = '2' LIMIT 7,1;") or die(mysqli_error($cnx) . "Error: en consultar equipos2_c");
        $reg_fase2_c = mysqli_fetch_assoc($cad_fase2_c);
        $tot2_c = mysqli_num_rows($cad_fase2_c);
        ?>
        <tr>
            <td><?php echo $reg_fase2_c['ipe_porcentaje'] ?></td>
            <td><?php echo $reg_fase2_c['ipe_cantidad'] ?></td>
            <td>Litros</td>
            <td><?php echo $quim_id['quimico_descripcion'] ?></td>
            <td><?php echo $reg_fase2_c['ipe_horas'] ?></td>
            <td><?php echo $reg_fase2_c['ipe_minutos'] ?></td>
            <td><?php echo $reg_fase2_c['ipe_fe_hr_inicio'] ?></td>
            <td><?php echo $reg_fase2_c['ipe_fe_hr_fin'] ?></td>
            <td><?php echo $reg_fase2_c['ipe_observaciones'] ?></td>
        </tr>
    </tbody>
</table>
<div class="row renglones" style="margin-top: 2rem;">
    <div class="col-md-5">
        <label for="formFile" class="form-label" style="font-weight: bold;">ENCALADO</label>
    </div>
    <div class="col-md-4">
        <label for="formFile" class="form-label" id="etiqueta_niveles">ADICIONAR AGUA HASTA CUBRIR LOS CUEROS</label>
    </div>
</div>
<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha/hora termina encalado</td>
            <td style="background-color: #E4E4E5; font-weight: bold;"></td>
            <td style="background-color: #E4E4E5; font-weight: bold;">PH</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Lavado</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $reg_pelambre['ip_fe_hr_ter_encalado'] ?></td>
            <td>10 Horas</td>
            <td><?php echo $reg_pelambre['ip_ph_encalado'] ?></td>
            <td><?php echo $reg_pelambre['ip_lavado_encalado'] ?></td>
        </tr>
    </tbody>
</table>
<!-- -------------------------------------------------------------- -->
<nav aria-label="breadcrumb" style="font-weight:bolder">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">ETAPA DE LAVADOS</li>
    </ol>
</nav>

<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha inicio</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Hora inicio</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Hora termino</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">PH del Agua</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">CE del Agua</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($tot4 > 0) {
            do {
        ?>
                <tr>
                    <td><?php echo $reg_fase4['ipe_fe_inicio'] ?></td>
                    <td><?php echo $reg_fase4['ipe_hr_inicio'] ?></td>
                    <td><?php echo $reg_fase4['ipe_hr_fin'] ?></td>
                    <td><?php echo $reg_fase4['ipe_ph'] ?></td>
                    <td><?php echo $reg_fase4['ipe_ce'] ?></td>
                </tr>
        <?php
            } while ($reg_fase4 = mysqli_fetch_assoc($cad_fase4));
        } else {
            echo '<tr>
            <td colspan="10" style="text-align:center">No hay registros</td></tr>';
        }
        ?>
    </tbody>
</table>
<!-- -------------------------------------------------------------- -->
<nav aria-label="breadcrumb" style="font-weight:bolder">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">ETAPA DE PREBLANQUEO</li>
    </ol>
</nav>
<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5; font-weight: bold;">Fecha inicio</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Hora inicio</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Hora termino</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">PH</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">REDOX</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">CE</td>
            <td style="background-color: #E4E4E5; font-weight: bold;">Horas totales proceso</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($tot5 > 0) {
        ?>
            <tr>
                <td><?php echo $reg_fase5['ipe_fe_inicio'] ?></td>
                <td><?php echo $reg_fase5['ipe_hr_inicio'] ?></td>
                <td><?php echo $reg_fase5['ipe_hr_fin'] ?></td>
                <td><?php echo $reg_fase5['ipe_ph'] ?></td>
                <td><?php echo $reg_fase5['ipe_redox'] ?></td>
                <td><?php echo $reg_fase5['ipe_ce'] ?></td>
                <td><?php echo $reg_pelambre['ip_hrs_totales'] ?></td>
            </tr>
        <?php
        } else {
            echo '<tr>
            <td colspan="10" style="text-align:center">No hay registros</td></tr>';
        }
        ?>
    </tbody>
</table>
<table style="border: 1px solid black;" class="table table-bordered">
    <thead>
        <tr>
            <td style="background-color: #E4E4E5;">Fecha en que se descargo en patio</td>
            <td style="background-color: #E4E4E5;">Kilos finales</td>
            <!--<td style="background-color: #E4E4E5;">Cajón</td>-->
            <td style="background-color: #E4E4E5;">Observaciones</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $reg_pelambre['ip_fe_descarga']; ?></td>
            <td><?php echo $reg_pelambre['ip_kg_finales']; ?></td>
            <!--<td><?php //echo $inventario['ac_descripcion']; ?></td>-->
            <td><?php echo $reg_pelambre['ip_observaciones']; ?></td>

        </tr>
    </tbody>
</table>