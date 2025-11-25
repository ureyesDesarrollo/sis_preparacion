<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
	
	require '../reportes/conexion2.php';
	include('../seguridad/user_seguridad.php');


	/* Nombre de La Tabla */
	$sTabla = "lotes";
	
	/* Array que contiene los nombres de las columnas de la tabla*/
	$aColumnas = array('lote_folio', 'lote_fecha', 'lote_hora', 'lote_mes', 'lote_turno','usu_id' );
	
	/* columna indexada */
	$sIndexColumn = "lote_id";
	$mes = date("m");
	// Paginacion
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	}
	
	
	//Ordenacion
	if ( isset( $_GET['iSortCol_1'] ) )
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
	}/*else{
		$sWhere = "WHERE lote_mes = '$mes'";
	}*/
	
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
	SELECT SQL_CALC_FOUND_ROWS  lote_id, lote_folio, lote_fecha, lote_hora, lote_mes, lote_turno, usu_id
	FROM  lotes 
	$sWhere  
	ORDER BY lote_id desc
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

				if($i != 5)
				{
					$row[] = $aRow[ $aColumnas[$i] ];
				}

				else if($i == 5)
				{
					$sQuerys = "SELECT usu_usuario FROM usuarios WHERE usu_id = ".$aRow[$aColumnas[5]];
					$rResults = $mysqli->query($sQuerys);
					$aResultFilterTotals = $rResults->fetch_array();
					$row[] = $aResultFilterTotals[0];//"material";
				}

				
			}
		}
		

		//if(fnc_permiso($_SESSION['privilegio'], 18, 'upe_editar' ) == 1)
		if($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 10)
		{
		
		$row[] = "<td><a href='#' onClick='javascript:AbreModalEditar(".$aRow['lote_id']." , ".$aRow['lote_folio'].", ".$aRow['lote_mes'].")'><span class='glyphicon glyphicon-pencil'></span></a></td>";
	
		}
		else
		{
			$row[] = "<td></td>";
		}
					
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>