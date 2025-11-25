<?php
/*
		* Script:    Tablas de multiples datos del lado del servidor para PHP y MySQL
		* Copyright: 2016 - Marko Robles
		* License:   GPL v2 or BSD (3-point)
	*/

require 'conexion2.php';


/* Nombre de La Tabla */
$sTabla = "inventario_diario_materiales";

/* Array que contiene los nombres de las columnas de la tabla*/
$aColumnas = array('idm_id', 'usu_id', 'idm_fecha', 'idm_documento', 'mat_id', 'idm_cant_ing', 'idm_cant_ant', 'idm_cant_new');

/* columna indexada */
$sIndexColumn = "idm_id";

// Paginacion
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
	$sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
}

$sOrder = '';
//Ordenacion
if (isset($_GET['iSortCol_1'])) {
	$sOrder = "ORDER BY  ";
	for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
		if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
			$sOrder .= $aColumnas[intval($_GET['iSortCol_' . $i])] . "
				" . $_GET['sSortDir_' . $i] . ", ";
		}
	}

	$sOrder = substr_replace($sOrder, "", -2);
	if ($sOrder == "ORDER BY") {
		$sOrder = "";
	}
}

//Filtracion
$sWhere = "";
if ($_GET['sSearch'] != "") {
	$sWhere = "WHERE (";
	for ($i = 0; $i < count($aColumnas); $i++) {
		$sWhere .= $aColumnas[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
	}
	$sWhere = substr_replace($sWhere, "", -3);
	$sWhere .= ')';
}

// Filtrado de columna individual 
for ($i = 0; $i < count($aColumnas); $i++) {
	if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		$sWhere .= $aColumnas[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
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
	SELECT SQL_CALC_FOUND_ROWS  idm_id, usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new 
	FROM  inventario_diario_materiales
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
	SELECT COUNT(" . $sIndexColumn . ")
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

while ($aRow = $rResult->fetch_array()) {
	$row = array();
	for ($i = 0; $i < count($aColumnas); $i++) {
		if ($aColumnas[$i] == "version") {
			/* Special output formatting for 'version' column */
			$row[] = ($aRow[$aColumnas[$i]] == "0") ? '-' : $aRow[$aColumnas[$i]];
		} else if ($aColumnas[$i] != ' ') {
			/* General output */
			if ($i != 1 and $i != 4) {
				$row[] = $aRow[$aColumnas[$i]];
			} else if ($i == 1) {
				$sQuerys = "SELECT usu_usuario FROM usuarios WHERE usu_id = " . $aRow[$aColumnas[1]];
				$rResults = $mysqli->query($sQuerys);
				$aResultFilterTotals = $rResults->fetch_array();
				$row[] = $aResultFilterTotals[0]; //"material";
			} else if ($i == 4) {
				$sQuerys = "SELECT mat_nombre FROM materiales WHERE mat_id = " . $aRow[$aColumnas[4]];
				$rResults = $mysqli->query($sQuerys);
				$aResultFilterTotals = $rResults->fetch_array();
				$row[] = $aResultFilterTotals[0]; //"material";
			}
		}
	}


	$output['aaData'][] = $row;
}

echo json_encode($output);
