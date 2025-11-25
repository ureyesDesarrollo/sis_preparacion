<?php
	/*
		* Script:    Tablas de multiples datos del lado del servidor para PHP y MySQL
		* Copyright: 2016 - Marko Robles
		* License:   GPL v2 or BSD (3-point)
	*/
		
		require 'conexion2.php';
		include('../seguridad/user_seguridad.php');


		/* Nombre de La Tabla */
		$sTabla = "procesos";
		
		/* Array que contiene los nombres de las columnas de la tabla*/
		$aColumnas = array( 'pro_id', 'pl_id', 'pt_id', 'pro_total_kg', 'pro_fe_carga', 'pro_hr_inicio', 'pro_hr_fin', 'pro_estatus' );
		
		/* columna indexada */
		$sIndexColumn = "pro_id";
		
	// Paginacion
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
		}
		$sOrder = '';
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
	";*/
	$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS pro_id, pl_id, pt_id, pro_total_kg, pro_fe_carga, pro_hr_inicio, pro_hr_fin, pro_estatus
	FROM  procesos
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
					
					if($i != 2 and $i != 7)
					{
						$row[] = $aRow[ $aColumnas[$i] ];
					}


					else if($i == 2)
					{
						$sQuerys = "SELECT DISTINCT  mat_nombre FROM procesos_materiales As p INNER JOIN materiales m ON(p.mat_id = m.mat_id) WHERE pro_id = ".$aRow[ $aColumnas[0] ];
					/*$rResults = $mysqli->query($sQuerys);
					$aResultFilterTotals = $rResults->fetch_array();
					$row[] = $aResultFilterTotals[0];//"material";*/



					$rResults = $mysqli->query($sQuerys);
					$aResultFilterTotals_nom = $rResults->fetch_array();
					$aResultFilterTotals_num =  mysqli_num_rows($rResults);
					if($aResultFilterTotals_num > 1){
						"<link rel='stylesheet' href='css/bootstrap.min.css'>";
					 //$row[] = "<a href='formatos/consultar_materiales.php?id=".$aRow[$aColumnas[0]]."' target='_blank'>Mezcla</a>";
						$row[] = "<b>Mezcla</b>";
					}else{
						$row[] = $aResultFilterTotals_nom[0];} 
					}
					else if($i == 7)
					{
						if($aRow[ $aColumnas[7] ] == 1){ $row[] = "En proceso";}else{$row[] = "Terminado";} 
					//$row[] = "estatus";
					}
				}
			}
			

			$row[] = "<td><a href='../procesos/formatos/bitacora_consulta.php?idx_pro=".$aRow['pro_id']."' target='_blank'><span class='glyphicon glyphicon-print'></span></a></td>";
			$row[] = "<td><a href='../procesos/formatos/bitacora_exportar.php?idx_pro=".$aRow['pro_id']."' target='_blank'><span class='glyphicon glyphicon-circle-arrow-down'></span></a></td>";
			
		//$row[] = "<td><a href='desglose_parametros.php?pro_id=".$aRow['pro_id']."&hdd_tipo=1' data-toggle='modal' data-target='#Info'><span class='glyphicon glyphicon-alert'></span></a></td>";
			$row[] = "<td><a href='#' onClick='javascript:AbreModalInfo(".$aRow['pro_id'].", ". $aRow['pt_id'].")'><span class='glyphicon glyphicon-alert'></span></a></td>";
			
		//if(fnc_permiso($_SESSION['privilegio'], 17, 'upe_editar' ) == 1)
			if($_SESSION['privilegio'] == 1)
			{
				
		//$row[] = "<td><a href='bitacora_editar.php?pro_id=".$aRow['pro_id']."' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a></td>";
				$row[] = "<td><a href='#' onClick='javascript:AbreModalEditar(".$aRow['pro_id'].", ". $aRow['pt_id'].");'><span class='glyphicon glyphicon-pencil'></span></a></td>";
				
		//$row[] = "<td><a href='#' data-href='bitacora_cerrar.php?id=".$aRow['pro_id']."' data-toggle='modal' data-target='#confirm-delete'><span class='glyphicon glyphicon-remove-sign'></span></a></td>";
				if($aRow[ $aColumnas[7] ] == 1)
				{
					$row[] = "<td><a href='#' onClick='javascript:fnc_cerrar(".$aRow['pro_id'].");'>xxx<span class='glyphicon glyphicon-remove-sign'></span></a></td>";
				}
				else
				{
					$row[] = "<td></td>";
				}
				
		//$row[] = "<td><a href='#' data-href='bitacora_eliminar.php?id=".$aRow['pro_id']."' data-toggle='modal' data-target='#confirm-delete'><span class='glyphicon glyphicon-trash'></span></a></td>";
				$row[] = "<td><a href='#' onClick='javascript:fnc_quitar(".$aRow['pro_id'].");'><span class='glyphicon glyphicon-trash'></span></a><?php }?></td>";
				
			}
			else
			{
				$row[] = "<td></td>";
				$row[] = "<td></td>";
				$row[] = "<td></td>";
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
