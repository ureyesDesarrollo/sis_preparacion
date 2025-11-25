<?php
	/*
		* Script:    Tablas de multiples datos del lado del servidor para PHP y MySQL
		* Copyright: 2016 - Marko Robles
		* License:   GPL v2 or BSD (3-point)
	*/
	
	require 'conexion2.php';
	include('../seguridad/user_seguridad.php');
	
	/* Nombre de La Tabla */
	$sTabla = "bitacora_alertas";
	
	/* Array que contiene los nombres de las columnas de la tabla*/
	$aColumnas = array( 'ba_id', 'usu_id', 'ba_fecha', 'pep_tipo', 'ba_valor', 'pro_id', 'pe_id', 'ba_id' );
	
	/* columna indexada */
	$sIndexColumn = "ba_id";
	
	// Paginacion
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	}
	
	
	//Ordenacion
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumnas[ intval( $_GET['iSortCol_'.$i] ) ]."
				".$_GET['sSortDir_'.$i] .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	//$sOrder == "ORDER BY ba_id DESC";
	
	//Filtracion
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumnas) ; $i++ )
		{
			$sWhere .= $aColumnas[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	// Filtrado de columna individual 
	for ( $i=0 ; $i<count($aColumnas) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumnas[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
		}
	}
	
	
	//Obtener datos para mostrar SQL queries
	/*$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnas))."
	FROM   $sTabla
	$sWhere
	$sOrder
	$sLimit
	";
	$rResult = $mysqli->query($sQuery);*/


		$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS  ba_id, usu_id, ba_fecha, pep_tipo, ba_valor, pro_id, pe_id, ba_id  
	FROM  bitacora_alertas 
	$sWhere
	$sOrder
	$sLimit
	";
	$rResult = $mysqli->query($sQuery);
	
	/* Data set length after filtering */
	$sQuery = "
	SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = $mysqli->query($sQuery);
	$aResultFilterTotal = $rResultFilterTotal->fetch_array();
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
	SELECT COUNT(".$sIndexColumn.")
	FROM   $sTabla
	";
	$rResultTotal = $mysqli->query($sQuery);
	$aResultTotal = $rResultTotal->fetch_array();
	$iTotal = $aResultTotal[0];
	
	/*
		* Output
	*/
	$output = array(
	"sEcho" => intval($_GET['sEcho']),
	"iTotalRecords" => $iTotal,
	"iTotalDisplayRecords" => $iFilteredTotal,
	"aaData" => array()
	);
	
	while ( $aRow = $rResult->fetch_array())
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumnas) ; $i++ )
		{
			if ( $aColumnas[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumnas[$i] ]=="0") ? '-' : $aRow[ $aColumnas[$i] ];
			}
			else if ( $aColumnas[$i] != ' ' )
			{
				/* General output */
				//$row[] = $aRow[ $aColumnas[$i] ];
				if($i != 1 and $i != 6 and $i != 7)
				{
					$row[] = $aRow[ $aColumnas[$i] ];
				}
				if($i == 1 )
				{
					$sQuerys = "SELECT usu_usuario FROM usuarios WHERE usu_id = ".$aRow[$aColumnas[1]];
					$rResults = $mysqli->query($sQuerys);
					$aResultFilterTotals = $rResults->fetch_array();
					$row[] = $aResultFilterTotals[0];//"usuario";
				}
				else if($i == 6)
				{
					$sQuerys = "SELECT pep_nombre FROM preparacion_etapas_param WHERE pe_id = ".$aRow[$aColumnas[6]];
					$rResults = $mysqli->query($sQuerys);
					$aResultFilterTotals = $rResults->fetch_array();
					$row[] = $aResultFilterTotals[0];//"etapa";
				}
				
				else if($i == 7)
				{
					if($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 4 )
					{
			
					//$row[] = "<td><a href='bitacora_editar.php?pro_id=".$aRow['pro_id']."' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a></td>";
					$row[] = "<td><a href='#' onClick='javascript:AbreModalEditar(".$aRow['ba_id'].");'><span class='glyphicon glyphicon-pencil'></span></a></td>";
					}
				}
		
				
			}
		}
		
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>