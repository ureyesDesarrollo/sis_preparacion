<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//require_once('../../conexion/conexion.php');
$cnx =  Conectarse();

#Funcion para obtener los horas de inicio
function fnc_hora_de($intEtapa)
{
	$cadena = mysqli_query(Conectarse(), "SELECT pe_hr_ideal FROM preparacion_etapas WHERE pe_id = '$intEtapa' ") or die(mysql_error()."Error: en consultar la etapa");
	$registros = mysqli_fetch_assoc($cadena);
	
	return $registros['pe_hr_ideal'];
}

#Funcion para obtener las horas de fin
function fnc_hora_a($intEtapa)
{
	$cadena = mysqli_query(Conectarse(), "SELECT pe_hr_maxima FROM preparacion_etapas WHERE pe_id = '$intEtapa' ") or die(mysql_error()."Error: en consultar la etapa");
	$registros = mysqli_fetch_assoc($cadena);
	
	return $registros['pe_hr_maxima'];
}

#Funcion para obtener los rangos de inico de cada etapa
function fnc_rango_de($intEtapa)
{
	$cadena = mysqli_query(Conectarse(), "SELECT pe_inicio FROM preparacion_etapas WHERE pe_id = '$intEtapa' ") or die(mysql_error()."Error: en consultar la etapa");
	$registros = mysqli_fetch_assoc($cadena);
	
	return $registros['pe_inicio'];
}

#Funcion para obtener las horas de fin
function fnc_rango_a($intEtapa)
{
	$cadena = mysqli_query(Conectarse(), "SELECT pe_fin FROM preparacion_etapas WHERE pe_id = '$intEtapa' ") or die(mysql_error()."Error: en consultar la etapa");
	$registros = mysqli_fetch_assoc($cadena);
	
	return $registros['pe_fin'];
}

#Funcion para obtener el nombre del usuario
function fnc_nom_usu($intUsu)
{
	$cadena = mysqli_query(Conectarse(), "SELECT usu_usuario FROM usuarios WHERE usu_id = '$intUsu' ") or die(mysql_error()."Error: en consultar el usuario");
	$registros = mysqli_fetch_assoc($cadena);
	
	return $registros['usu_usuario'];
}

#Funci�n para validar el parametro de liberaci�n de CE
function fnc_parametro_max($intEtapa, $intTipo)
{
	$cadena = mysqli_query(Conectarse(), "SELECT pe_fin FROM preparacion_etapas WHERE pe_id = '$intEtapa' and pe_tipo = '$intTipo' ") or die(mysql_error()."Error: en consultar la etapa");
	$registros = mysqli_fetch_assoc($cadena);
	
	return $registros['pe_fin'];
}

#Funcion que valida si se puede mostrar o no un proceso
function fnc_valida_etapa($intPro, $intEta)
{ 
	$cadena = mysqli_query(Conectarse(), "SELECT * FROM procesos_liberacion WHERE pro_id = '$intPro' and pe_id = '$intEta' ");
	$registros = mysqli_fetch_array($cadena);
	
	if($registros['prol_id'] == '')// and $registros2['prol_id'] != ''
	{
		/*if($intEta == 1)
		{*/
			return "Si";
/*		}
		else
		{
				//Si la etapa es mayor o igual a la 2, verifica tambien la etapa antenrior
			$intEta2 = $intEta - 1;
			echo "SELECT * FROM procesos_liberacion WHERE pro_id = '$intPro' and pe_id = '$intEta2' ";
			$cadena2 = mysqli_query(Conectarse(), "SELECT * FROM procesos_liberacion WHERE pro_id = '$intPro' and pe_id = '$intEta2' ");
			$registros2 = mysqli_fetch_array($cadena2);
			if($registros2['prol_id'] == '')// and $registros2['prol_id'] != ''
			{
				return "Si";
			}
			else
			{
				return "No";
			}
		}*/
	}
	else
	{
		return "No";
	}
}

#Funcion que valida si se puede mostrar o no un proceso B
function fnc_valida_etapa_b($intPro, $intEta)
{ 
	$cadena = mysqli_query(Conectarse(), "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$intPro' and pe_id = '$intEta' ");
	$registros = mysqli_fetch_array($cadena);
	
	if($registros['prol_id'] == '')
	{
			return "Si";
	}
	else
	{
		return "No";
	}
}

#Funcion para obtener las horas desde el inicio del proceso
/*function fnc_horas($strFeIni, $strFeFin, $strHrIni, $strHrFin)
{ 
	$f1 = new DateTime($strHrIni);
    $f2 = new DateTime($strHrFin);
	
    $d = $f1->diff($f2);
	
	$date1 = new DateTime($strFeIni);
    $date2 = new DateTime($strFeFin);
    $diff = $date1->diff($date2);
	
	$hr_stot = $diff->days * 24;
	$hr_stot2 = $d->format('%H');//$d->hour;//$d->format('%H:%I:%S')
	$hr_stot3 = $d->format('%I')/60;
	
	$hr_tot = $hr_stot + $hr_stot2 + $hr_stot3;
	
    return number_format($hr_tot, 2, ".", ",");
}*/

function fnc_horas($strFeIni, $strFeFin, $strHrIni, $strHrFin)
{ 
	$date1 = new DateTime($strFeIni." ".$strHrIni);
    $date2 = new DateTime($strFeFin." ".$strHrFin);
    $diff = $date1->diff($date2);
	
	$hr_stot = $diff->days * 24;
	$hr_stot2 = $diff->format('%H');
	$hr_stot3 = $diff->format('%I')/60;
	
	$hr_tot = $hr_stot + $hr_stot2 + $hr_stot3;
	
    return number_format($hr_tot, 2, ".", ",");
}

#Funci�n que valida si existe el renglon autorizado para captura
function fnc_valida_renglon($intRen, $intPro, $intEtapa)
{
	$cadena = mysqli_query(Conectarse(), "SELECT * FROM procesos_renglones WHERE pro_id = '$intPro' and pe_id = '$intEtapa' and pr_ren = '$intRen' ");
	$registros = mysqli_fetch_array($cadena);
	
	if($registros['pr_id'] != '')
	{
		return "Si";
	}
	else
	{
		return "No";
	}
}
?>