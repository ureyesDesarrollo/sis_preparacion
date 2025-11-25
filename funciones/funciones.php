<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

//Poner en el menu_inicio

#Funcion para insertar el login de a la bitácora
if (!function_exists('ins_bit_login')) {
    function ins_bit_login($usu_id, $str_ip)
    {
        //include "../conexion/conexion.php";
        $cnx =  Conectarse();

        mysqli_query($cnx, "INSERT INTO bitacora_login (usu_id, bl_fecha, bl_ip) VALUES($usu_id, '" . date("Y-m-d H:i:s") . "', '$str_ip') ") or die(mysqli_error($cnx) . "Error: en insertar a la bitacora login");
    }
}

#Funcion para obtener la ip de la PC

if (!function_exists('getRealIP')) {
    function getRealIP()
    {

        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }
}
#Funcion para insertar las acciones del sistema
if (!function_exists('ins_bit_acciones')) {
    function ins_bit_acciones($usu_id, $str_accion, $int_valor, $int_modulo)
    {
        $cn =  Conectarse();

        $cad = "INSERT INTO bitacora_acciones (usu_id, ba_fecha, ba_accion, ba_valor, bm_id) VALUES($usu_id, '" . date("Y-m-d H:i:s") . "', '$str_accion', '$int_valor','$int_modulo') ";
        //return $cad;
        mysqli_query($cn, $cad) or die(mysqli_error($cn) . "Error en insertar a la bitacora de acciones. " . $cad);
    }
}

#Funcion para obtener el nombre del d�a
if (!function_exists('fnc_nom_dia')) {
    function fnc_nom_dia($no)
    {
        switch ($no) {
            case 0:
                $strNombre = 'Domingo';
                break;
            case 1:
                $strNombre = 'Lunes';
                break;
            case 2:
                $strNombre = 'Martes';
                break;
            case 3:
                $strNombre = 'Miercoles';
                break;
            case 4:
                $strNombre = 'Jueves';
                break;
            case 5:
                $strNombre = 'Viernes';
                break;
            case 6:
                $strNombre = 'Sabado';
                break;
            case 7:
                $strNombre = '';
                break;
        }

        return $strNombre;
    }
}

#Funci�n para obtener la imagen del paleto
if (!function_exists('fnc_imagen_paleto')) {
    function fnc_imagen_paleto($no)
    {
        switch ($no) {
            case 1:
                $strImg = 'paleto1.png';
                break;
            case 2:
                $strImg = 'libre.png';
                break;
            case 3:
                $strImg = 'descompuesto.png';
                break;
            case 4:
                $strImg = 'reparacion.png';
                break;
        }

        return $strImg;
    }
}

#Funci�n para obtener la imagen del paleto
if (!function_exists('fnc_imagen_lavador')) {
    function fnc_imagen_lavador($no)
    {
        switch ($no) {
            case 5:
                $strImg = 'lavador1.png';
                break;
            case 6:
                $strImg = 'libre.png';
                break;
            case 7:
                $strImg = 'descompuesto.png';
                break;
            case 8:
                $strImg = 'reparacion.png';
                break;
        }

        return $strImg;
    }
}

#Funci�n para obtener la imagen del paleto
if (!function_exists('fnc_color_paleto')) {
    function fnc_color_paleto($no)
    {
        switch ($no) {
            case 1:
                $strCol = '#415266';
                break;
            case 2:
                $strCol = '#FFFFFF';
                break;
            case 3:
                $strCol = '#FF0000';
                break;
            case 4:
                $strCol = '#0099FF';
                break;
        }

        return $strCol;
    }
}

#Funci�n para obtener la imagen del paleto
if (!function_exists('ins_bit_login')) {
    function fnc_color_lavador($no)
    {
        switch ($no) {
            case 5:
                $strCol = '#D46923';
                break;
            case 6:
                $strCol = '#FFFFFF';
                break;
            case 7:
                $strCol = '#FF0000';
                break;
            case 8:
                $strCol = '#0099FF';
                break;
        }

        return $strCol;
    }
}

#Funcion para obtener el nombre del usuario
if (!function_exists('fnc_nom_usuario')) {
    function fnc_nom_usuario($no)
    {
        $cnx =  Conectarse();

        $cadena =  mysqli_query($cnx, "SELECT usu_usuario FROM usuarios WHERE usu_id = '$no' ") or die(mysqli_error($cnx) . "Error: al consultar");
        $registros = mysqli_fetch_assoc($cadena);

        return $registros['usu_usuario'];
    }
}

#Funci�n para obtener el nombre de la accion
if (!function_exists('fnc_nom_accion')) {
    function fnc_nom_accion($val)
    {
        switch ($val) {
            case 'A':
                $strNombre = 'Agregar';
                break;
            case 'B':
                $strNombre = 'Baja';
                break;
            case 'E':
                $strNombre = 'Editar';
                break;
        }

        return $strNombre;
    }
}

#Funcion para obtener el nombre del material
if (!function_exists('fnc_nom_material')) {
    function fnc_nom_material($no)
    {
        $cnx =  Conectarse();

        $cadena =  mysqli_query($cnx, "SELECT mat_nombre FROM materiales WHERE mat_id = '$no' ") or die(mysqli_error($cnx) . "Error: al consultar");
        $registros = mysqli_fetch_assoc($cadena);

        return $registros['mat_nombre'];
    }
}

#Funcion para obtener el permiso del perfil
if (!function_exists('fnc_permiso')) {
    function fnc_permiso($intPerfil, $intModulo, $intOpcion)
    {
        $cnx =  Conectarse();

        $cadena =  mysqli_query($cnx, "SELECT $intOpcion as res FROM usuarios_permisos WHERE bm_id = '$intModulo' AND up_id = '$intPerfil' ") or die(mysqli_error($cnx) . "Error: al consultar");
        $registros = mysqli_fetch_assoc($cadena);

        if (isset($registros['res'])) {
            return $registros['res'];
        }
        //return "SELECT $intOpcion as res FROM usuarios_permisos WHERE bm_id = '$intModulo' AND up_id = '$intPerfil' ";
    }
}

#Funcion para obtener el nombre del estatus
if (!function_exists('fnc_nom_estatus')) {
    function fnc_nom_estatus($no)
    {
        $cnx =  Conectarse();

        $cadena =  mysqli_query($cnx, "SELECT le_estatus FROM listado_estatus WHERE le_id = '$no' ") or die(mysqli_error($cnx) . "Error: al consultar");
        $registros = mysqli_fetch_assoc($cadena);

        return $registros['le_estatus'];
    }
}

#Funci�n para formatear la fecha
if (!function_exists('fnc_formato_fecha')) {
    function fnc_formato_fecha($strFecha)
    { //Formato Y-m-d
        $dia = substr($strFecha, 8, 2);
        $mes = substr($strFecha, 5, 2);
        $year = substr($strFecha, 2, 2);

        return $dia . "-" . fnc_formato_mes($mes) . "-" . $year;
    }
}

#Funci�n para formatear la fecha
if (!function_exists('fnc_formato_fecha_hr')) {
    function fnc_formato_fecha_hr($strFecha)
    { //Formato Y-m-d
        $dia = substr($strFecha, 8, 2);
        $mes = substr($strFecha, 5, 2);
        $year = substr($strFecha, 2, 2);
        $hr = substr($strFecha, 11, 8);

        return $dia . "-" . fnc_formato_mes($mes) . "-" . $year . " " . $hr;
    }
}

#Funci�n para formatear el mes
if (!function_exists('fnc_formato_mes')) {
    function fnc_formato_mes($strMes)
    {
        switch ($strMes) {
            case 01:
                return "ene";
                break;
            case 02:
                return "feb";
                break;
            case 03:
                return "mar";
                break;
            case 04:
                return "abr";
                break;
            case 05:
                return "may";
                break;
            case 06:
                return "jun";
                break;
            case 07:
                return "jul";
                break;
            case '08':
                return "ago";
                break;
            case '09':
                return "sep";
                break;
            case 10:
                return "oct";
                break;
            case 11:
                return "nov";
                break;
            case 12:
                return "dic";
                break;
        }
    }
}

#Funci�n para formatear los valores
if (!function_exists('fnc_formato_val')) {
    function fnc_formato_val($strVal)
    {
        if ($strVal == 0) {
            return "N/A";
        } else {
            return $strVal;
        }
    }
}
if (!function_exists('fnc_formato_vacio')) {
    function fnc_formato_vacio($strVal)
    {
        if ($strVal == 0) {
            return "-";
        } else {
            return $strVal;
        }
    }
}

#Funcion para obtener el nombre de la etapa
if (!function_exists('fnc_nombre_etapa')) {
    function fnc_nombre_etapa($intId)
    {
        if ($intId == 1 or $intId == 22) {
            $strNombre = 'Lav. Iniciales';
        }

        if ($intId == 3) {
            $strNombre = 'Enzima';
        }

        if ($intId == 6) {
            $strNombre = 'Sosa';
        }

        if ($intId == 2 or $intId == 4) {
            $strNombre = 'Blanqueo';
        }

        if ($intId == 5 or $intId == 9 or $intId == 23) {
            $strNombre = 'Lav. Blanqueo';
        }

        if ($intId == 7 or $intId == 8 or $intId == 12 or $intId == 13 or $intId == 24) {
            $strNombre = '1er Acido';
        }

        if ($intId == 10 or $intId == 11 or $intId == 15 or $intId == 16 or $intId == 25) {
            $strNombre = 'Lav. 1er Acido';
        }

        if ($intId == 14 or $intId == 18 or $intId == 19) {
            $strNombre = '2do Acido';
        }

        if ($intId == 17 or $intId == 20 or $intId == 21 or $intId == 26) {
            $strNombre = 'Lav. Finales';
        }

        return $strNombre;
    }
}

//Funcion para obtener el no. de lote anterior
if (!function_exists('fnc_lote')) {
    function fnc_lote($strMes)
    {
        $cnx =  Conectarse();
        $str_year = date("Y");
        $cadena =  mysqli_query($cnx, "SELECT COUNT(lote_id) AS res FROM lotes WHERE lote_mes = '$strMes' and lote_fecha like ('%$str_year%') ") or die(mysqli_error($cnx) . "Error: al consultar");
        $registros = mysqli_fetch_assoc($cadena);

        return $registros['res'] + 1;
    }
}
//Funcion para obtener el no. de lote anterior nuevoa
if (!function_exists('fnc_lote_anio')) {
    function fnc_lote_anio($strAnio)
    {
        $cnx =  Conectarse();
        $mes = date("m"); 
        $cadena =  mysqli_query($cnx, "SELECT LPAD((COUNT(lote_id)+1),3,'0') AS res FROM lotes_anio WHERE lote_anio = '$strAnio'") or die(mysqli_error($cnx) . "Error: al consultar");
        $registros = mysqli_fetch_assoc($cadena);

        #$consecutivo_anual = $registros['res'] + 1;

        return $strAnio .  $mes . $registros['res'];
    }
}


if (!function_exists('fnc_obtener_momento_dia')) {
    function fnc_obtener_momento_dia()
    {
        $hora_actual = date("H");

        // Definir el rango de horas para considerar como "de día"
        $hora_inicio_dia = 7;
        $hora_fin_dia = 19;

        // Verificar si la hora actual está dentro del rango de día
        if ($hora_actual >= $hora_inicio_dia && $hora_actual < $hora_fin_dia) {
            return "D"; // Es de día
        } else {
            return "N"; // Es de noche
        }
    }
}




//Funciones tableros
if (!function_exists('fnc_tipo_param')) {
    function fnc_tipo_param($strValor)
    {
        if ($strValor == 'H') {
            return "Hora";
        }

        if ($strValor == 'C') {
            return "Ce";
        }

        if ($strValor == 'P') {
            return "Ph";
        }
    }
}

if (!function_exists('fnc_tipo_campo')) {
    function fnc_tipo_campo($strValor)
    {
        if ($strValor == 'Hr') {
            return "prol_hr_totales";
        }

        if ($strValor == 'Ce') {
            return "prol_ce";
        }

        if ($strValor == 'pH') {
            return "prol_ph";
        }
    }
}

//Funci�n para las alertas
if (!function_exists('fnc_alertas')) {
    function fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo, $parametro2, $valor2)
    {
        $cnx = Conectarse();

        $cad_usu = mysqli_query($cnx, "SELECT usu_usuario 
					FROM usuarios WHERE usu_id = $usuario") or die(mysqli_error($cnx) . "Error: en consultar el usuario");
        $reg_usu = mysqli_fetch_assoc($cad_usu);

        $nombre_usuario = $reg_usu['usu_usuario'];

        $cad_lav = mysqli_query($cnx, "SELECT pl_descripcion FROM preparacion_lavadores WHERE pl_id = '$lavador' ") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
        $reg_lav = mysqli_fetch_assoc($cad_lav);

        $str_lavador = $reg_lav['pl_descripcion'];

        $cad_pal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos WHERE pp_id = '$paleto' ") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
        $reg_pal = mysqli_fetch_assoc($cad_pal);

        $str_paleto = $reg_pal['pp_descripcion'];

        //ALERTA 1

        if (($parametro == 'ppm' and $valor == 0) or $valor != 0) {

            $cad_etapas = mysqli_query($cnx, "select pep_id, pe_id, pep_nombre, pep_tipo, pep_inicio, pep_fin, pep_enviar_email, pep_descripcion  
											from preparacion_etapas_param 
											where pe_id = '$etapa' and pep_tipo = '$parametro' ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
            $reg_etapas = mysqli_fetch_assoc($cad_etapas);

            if ($valor < $reg_etapas['pep_inicio'] or $valor >= $reg_etapas['pep_fin']) {

                mysqli_query($cnx, "INSERT INTO bitacora_alertas (pep_id, pe_id, pep_tipo, pro_id, ba_valor, usu_id, pl_id, pp_id, ba_tipo) VALUES('$reg_etapas[pep_id]','$etapa', '$parametro', '$proceso', '$valor', '$usuario', '$lavador', '$paleto', '$tipo') ") or die(mysqli_error($cnx) . "Error: al inserta la bitacora");

                if ($reg_etapas['pep_enviar_email'] == 1) {

                    $reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(ba_id) as res from bitacora_alertas "));

                    $clave = $reg_ultimo_id['res'];

                    include "../../alertas/email_alerta.php";
                }
            }
        }

        //ALERTA 2
        //Cambia datos: parametro2, valor2, valores etapa, clave alerta

        if (($parametro2 == 'ppm' and $valor2 == 0) or $valor2 != 0) {

            $cad_etapas2 = mysqli_query($cnx, "select pep_id, pe_id, pep_nombre, pep_tipo, pep_inicio, pep_fin, pep_enviar_email, pep_descripcion 
											from preparacion_etapas_param 
											where pe_id = '$etapa' and pep_tipo = '$parametro2' ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
            $reg_etapas2 = mysqli_fetch_assoc($cad_etapas2);

            if ($valor2 < $reg_etapas2['pep_inicio'] or $valor2 >= $reg_etapas2['pep_fin']) {

                mysqli_query($cnx, "INSERT INTO bitacora_alertas (pep_id, pe_id, pep_tipo, pro_id, ba_valor, usu_id, pl_id, pp_id, ba_tipo) VALUES('$reg_etapas2[pep_id]','$etapa', '$parametro2', '$proceso', '$valor2', '$usuario', '$lavador', '$paleto', '$tipo') ") or die(mysqli_error($cnx) . "Error: al inserta la bitacora v2");

                if ($reg_etapas2['pep_enviar_email'] == 1) {

                    $reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(ba_id) as res from bitacora_alertas "));

                    $clave2 = $reg_ultimo_id['res'];
                    //echo "llego aqui";
                    include "../../alertas/email_alerta_v2.php";
                }
            }
        }
    }
}

if (!function_exists('fnc_folio_mensual')) {

    function fnc_folio_mensual()
    {

        $cnx = Conectarse();
        $str_fecha = date("Y-m-") . "01";
        /*  $cad = mysqli_query($cnx, "SELECT LPAD((COUNT(inv_folio_interno)+1),3,'0') as num FROM inventario WHERE inv_fecha >= '$str_fecha' "); */
        $cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
       inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'L'");
        $reg = mysqli_fetch_array($cad);

        $str_folio = date("ym") . $reg['num'];
        //$str_folio = $reg['num'];
        echo $str_folio;
    }
}

if (!function_exists('fnc_folio_anual')) {

    function fnc_folio_anual()
    {

        $cnx = Conectarse();

        $str_fecha = date("Y-") . "01-01";

        $cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
	    inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'E' and inv_id_key is null ");
        $reg = mysqli_fetch_array($cad);

        $str_folio = date("ym") . $reg['num'];
        //$str_folio = $reg['num'];
        echo $str_folio;
    }
}

#Funcion para encryptar el nombre del proveedor

if (!function_exists('generate_string')) {
    function generate_string($strength = 16) 
    {
        $input = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $input_length = strlen($input);
        
        $random_string = '';
        for($i = 0; $i < $strength; $i++) 
        {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        
        return $random_string;
    }
}

if (!function_exists('transformar')) {
    function transformar($palabra)
    {
        $letras = substr(strtoupper($palabra), 0, 3);
        $numLetras = strlen($letras);
        $permuta = array();
        $combina = 3; #nº de letras a combinar;
        $serie = 1;
    
        for ($i = 0; $i < $combina; $i++)
            $serie *= $numLetras;
    
        for ($i = 0; $i < $serie; $i++)
            $permuta[$i] = '';
    
        $subSerie = $serie / $numLetras;
    
        for ($i = 0; $i < $combina; $i++) 
        {
    
            $per = 1;
            $let = 0;
            for ($j = 0; $j < $serie; $j++) {
                $permuta[$j] .= $letras[$let];
                $per += 1;
                if ($per > $subSerie) {
                    $per = 1;
                    $let += 1;
                    if ($let > $numLetras - 1) $let = 0;
                }
            }
            $subSerie /= $numLetras;
        }
    
        $posicion = rand(0, 5);
    
        return generate_string(3).$permuta[$posicion];	
    }
}

if (!function_exists('xtransforma')) {
    function xtransforma($input, $strength = 16) 
    {
        //$input = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $input_length = strlen($input);
        
        $random_string = '';
        for($i = 0; $i < $strength; $i++) 
        {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        
        return $random_string;
    }
}
