<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../seguridad/user_seguridad.php";
$cnx =  Conectarse();

$cad_pal = mysqli_query($cnx, "SELECT *	FROM preparacion_paletos WHERE pp_id > 2 ") or die(mysql_error() . "Error: en consultar los paletos");
$reg_pal = mysqli_fetch_assoc($cad_pal);
$tot_pal = mysqli_num_rows($cad_pal);

$cad_palAB = mysqli_query($cnx, "SELECT * FROM preparacion_paletos WHERE pp_id <= 2 ") or die(mysql_error() . "Error: en consultar los paletos");
$reg_palAB = mysqli_fetch_assoc($cad_palAB);

//lavadores 
$cad_lav = mysqli_query($cnx, "SELECT *	FROM preparacion_lavadores WHERE pl_id >= 1 and pl_id <= 12 ") or die(mysql_error() . "Error: en consultar los lavadores");
$reg_lav = mysqli_fetch_assoc($cad_lav);
$tot_lav = mysqli_num_rows($cad_lav);

//lavadores pelambre
$cad_lav_p = mysqli_query($cnx, "SELECT * FROM preparacion_lavadores WHERE pl_id >= 13 ") or die(mysql_error() . "Error: en consultar los lavadores");
$reg_lav_p = mysqli_fetch_assoc($cad_lav_p);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Estatus</title>
  <link rel="stylesheet" href="../css/estilos_indicadores.css">

  <style>
    /*RESPONSIVO*/
  </style>
</head>

<body>
  <div class="col-md-7">
    <h1 style="color: #546B85;text-align: right;">Tablero de Estatus </h1>
  </div>

  <div class="col-md-5" style="text-align: right; color: #546B85">
    <label style="font-family:Times New Roman, Albertus MT, Book Antique, Bookman old style; font-size: 20px;margin-top: 30px">Ultima actualización <?php echo date("d-m-Y h:i:s") ?></label>
  </div>

  <div class="col-md-12">
    <div class="stitulo">LAVADORES</div>
    <?php
    do {
      $strEstilo = fnc_estilo_lavador($reg_lav['le_id']);
    ?>
      <!-- <div class="lavadores" style="<?php echo $strEstilo; ?>" >-->
      <div class="lavadores" style="<?php echo $strEstilo; ?>;padding-top: 10px">
        <?php if ($reg_lav['le_id'] == 5 or $reg_lav['le_id'] == 6) { ?>
          <h1>
            <?php if ($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6) { ?>
              <a href="../procesos/bitacora.php?id=<?php echo $reg_lav['pl_id'] ?>" target="_blank" class="numero"><?php echo $reg_lav['pl_descripcion'] ?></a>
            <?php } else { ?>
              <a href="../procesos/formatos/bitacora_consulta.php?id=<?php echo $reg_lav['pl_id'] ?>" target="_blank" class="numero"><?php echo $reg_lav['pl_descripcion'] ?></a>
            <?php } ?>
          </h1>
        <?php } else { ?>
          <h1><a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo $reg_lav['pl_descripcion'] ?></a></h1>
        <?php } ?>
      </div>
    <?php } while ($reg_lav = mysqli_fetch_assoc($cad_lav)); ?>
  </div>

  <div class="col-md-12" style="margin-top: -30px">
    <div class="stitulo">LAVADORES SECCIÓN 2</div>
    <?php
    do {
      $strEstilo_p = fnc_estilo_lavador($reg_lav_p['le_id']);
    ?>
      <div class="lavadores" style="<?php echo $strEstilo_p; ?>;padding-top: 10px">
        <?php if ($reg_lav_p['le_id'] == 5 or $reg_lav_p['le_id'] == 6) { ?>
          <h1>
            <?php if ($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6) { ?>
              <a href="../procesos/bitacora.php?id=<?php echo $reg_lav_p['pl_id'] ?>" target="_blank" class="numero"><?php echo $reg_lav_p['pl_descripcion'] ?></a>
            <?php } else { ?>
              <a href="../procesos/formatos/bitacora_consulta.php?id=<?php echo $reg_lav_p['pl_id'] ?>" target="_blank" class="numero"><?php echo $reg_lav_p['pl_descripcion'] ?></a>
            <?php } ?>
          </h1>
        <?php } else { ?>
          <h1><a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo $reg_lav_p['pl_descripcion'] ?></a></h1>
        <?php } ?>
      </div>
    <?php } while ($reg_lav_p = mysqli_fetch_assoc($cad_lav_p)); ?>
  </div>


  <div class="col-md-12" style="margin-top: -30px">
    <div class="stitulo">PALETOS</div>
    <?php do {
      //	$strColor = fnc_color_paleto($reg_pal['le_id']);
      $strEstilo = fnc_estilo_paleto($reg_pal['le_id']);

    ?>
      <div class="lavadores" style="<?php echo $strEstilo; ?>
 ">
        <?php if ($reg_pal['le_id'] == 1 or $reg_pal['le_id'] == 2) { ?>
          <?php if ($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6) { ?>
            <h1><a href="../procesos/bitacora_paleto.php?id_p=<?php echo $reg_pal['pp_id'] ?>" target="_blank" class="numero"><?php echo "Paleto " . $reg_pal['pp_descripcion'] ?></a>
            <?php } else { ?>
              <a href="../procesos/formatos/bitacora_paleto_consulta.php?id=<?php echo $reg_pal['pp_id'] ?>" target="_blank" class="numero"><?php echo "Paleto " . $reg_pal['pp_descripcion'] ?></a>
            </h1>
          <?php } ?>
          <!--<h1><a href="http://localhost/sis_preparacion/procesos/bitacora_paleto.php" target="_blank" class="numero"><?php echo "Paleto " . $reg_pal['pp_descripcion'] ?></a></h1>-->
        <?php } else { ?>
          <h1><a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo "Paleto " . $reg_pal['pp_descripcion'] ?></a></h1>
        <?php } ?>
      </div>
    <?php } while ($reg_pal = mysqli_fetch_assoc($cad_pal)); ?>
  </div>

  <div class="col-md-12" style="margin-top: -30px">
    <div class="stitulo">RECEPTORES</div>

    <!--PALETO A-->
    <?php do { ?>
      <?php if ($reg_palAB['pp_id'] == 1) { ?>
        <?php if ($reg_palAB['le_id'] == 1) { ?>
          <div class="lavadores" style=" background-image: url('../iconos/paleto_ab.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;">
            <h1><a href="../procesos/bitacora_paleto.php?id_p=1" target="_blank" style="color: #fff;margin-left:15px" class="numero"><?php echo $reg_palAB['pp_descripcion'] ?></a></h1>
          </div>
        <?php } else { ?>
          <div class="lavadores" style=" background-image: url('../iconos/paleto_ab_libre.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px; ">
            <h1><a href="#" style="cursor: default;text-decoration: none;color: inherit;margin-left:15px" class="numero"><?php echo $reg_palAB['pp_descripcion'] ?></a></h1>
          </div>
        <?php } ?>
      <?php
      } ?>

      <!--PALETO B-->
      <?php if ($reg_palAB['pp_id'] == 2) { ?>
        <?php if ($reg_palAB['le_id'] == 1) { ?>
          <div class="lavadores" style="background-image: url('../iconos/paleto_ab.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;">
            <h1><a href="../procesos/bitacora_paleto.php?id_p=2" target="_blank" style="color: #fff;margin-left:15px" class="numero"><?php echo $reg_palAB['pp_descripcion'] ?></a></h1>
          </div>
        <?php } else { ?>
          <div class="lavadores" style="background-image: url('../iconos/paleto_ab_libre.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;">
            <h1><a href="#" style="cursor: default;text-decoration: none;color: inherit;margin-left:15px" class="numero"><?php echo $reg_palAB['pp_descripcion'] ?></a></h1>
          </div>
        <?php } ?>
      <?php
      } ?>
    <?php } while ($reg_palAB = mysqli_fetch_assoc($cad_palAB)); ?>
  </div>

  <table border="1" style="margin-bottom: 30px;width:100%; text-align: center;background: #F5F4F4;border: 1px solid#e6e6e6">
    <tr>
      <td colspan="12" align="center" class="sbtitulo">
        ESTASTUS
      </td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="sbtitulo"> LAVADORES</td>
      <!--<td  colspan="4" align="center" class="sbtitulo">  LAVADORES SECCIÓN 2</td>-->
      <td width="5%"></td>
      <td colspan="4" align="center" class="sbtitulo"> PALETOS</td>
      <td width="5%"></td>
      <td colspan="4" align="center" class="sbtitulo"> PALETOS AB</td>
    </tr>
    <tr style="text-align: center;">
      <td style="height: 50px">
        <div style="width: 80px;height: 30px; background-image: url('../iconos/lavador_ocupado.png');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Ocupado</div>
      </td>
      <td style="height: 50px">
        <div style="width: 80px;height: 30px; background-image: url('../iconos/lavador_libre.png');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Libre</div>
      </td>
      <td style="height: 50px">
        <div style="width: 100px;height: 30px; background-image: url('../iconos/lavador_descompuesto.png');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Descompuesto</div>
      </td>
      <td align="">
        <div style="width:80px;height: 30px; background-image: url('../iconos/lavador_reparacion.png');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Reparación</div>
      </td>

      <td width="5%"></td>
      <td style="height: 50px">
        <div style="width: 80px;height: 30px; background-image: url('../iconos/paletoocupado.gif');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Ocupado</div>
      </td>

      <td style="height: 50px">
        <div style="width: 100px;height: 30px; background-image: url('../iconos/paletolibre.gif');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Libre</div>
      </td>

      <td style="height: 50px">
        <div style="width: 80px;height: 30px; background-image: url('../iconos/paletodescompuesto.gif');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Descompuesto</div>
      </td>

      <td style="height: 50px">
        <div style="width: 80px;height: 30px; background-image: url('../iconos/paletoreparacion.gif');background-repeat: no-repeat;background-size: cover;background-position: 25px 0px;background-size:45px;"><br>Reparación</div>
      </td>


      <td width="5%"></td>
      <td style="height: 50px">
        <div style="width: 80px;height: 30px; background-image: url('../iconos/paleto_ab.png');background-repeat: no-repeat;background-size: cover;background-position: 25px -2px;background-size:33px;"><br>Ocupado</div>
      </td>

      <td style="height: 50px">
        <div style="width: 100px;height: 30px; background-image: url('../iconos/paleto_ab_libre.png');background-repeat: no-repeat;background-size: cover;background-position: 25px -2px;background-size:33px;"><br>Libre</div>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</body>

</html>
<?php
#Función para obtener el estilo del lavador
function fnc_estilo_lavador($no)
{
  switch ($no) {
    case 5:
      $strCol = "background-image: url('../iconos/lavador_ocupado.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
    case 6:
      $strCol = "background-image: url('../iconos/lavador_libre.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
    case 7:
      $strCol = "background-image: url('../iconos/lavador_descompuesto.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
    case 8:
      $strCol = "background-image: url('../iconos/lavador_reparacion.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
  }

  return $strCol;
}

/*function fnc_estilo_lavador_pelambre($no)
{
  switch ($no) 
  {
    case 5:
    $strCol = "background-image: url('../iconos/lavador_pelambre.gif');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
    break;
    case 6:
    $strCol = "background-image: url('../iconos/lavador_libre.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
    break;
    case 7:
    $strCol = "background-image: url('../iconos/lavador_descompuesto.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
    break;
    case 8:
    $strCol = "background-image: url('../iconos/lavador_reparacion.png');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
    break;
  }

  return $strCol;
}*/
?>

<?php
#Función para obtener el estilo del lavador
function fnc_estilo_paleto($no)
{
  switch ($no) {
    case 1:
      $strCol = "background-image: url('../iconos/paletoocupado.gif');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
    case 2:
      $strCol = "background-image: url('../iconos/paletolibre.gif');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px; ";
      break;
    case 3:
      $strCol = "background-image: url('../iconos/paletodescompuesto.gif');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
    case 4:
      $strCol = "background-image: url('../iconos/paletoreparacion.gif');background-repeat: no-repeat;background-size: cover;background-position: 0px 0px;background-size:85px;";
      break;
  }
  return $strCol;
}
?>