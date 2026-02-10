<?php
/*
		* Script:    Tablas de multiples datos del lado del servidor para PHP y MySQL
		* Copyright: 2016 - Marko Robles
		* License:   GPL v2 or BSD (3-point)
	*/

require 'conexion2.php';

/* Nombre de La Tabla */
$sTabla = "procesos_paletos";

/* Array que contiene los nombres de las columnas de la tabla*/
/*numero de proceso id, paleto, preparacion, directo, estatus*/
$aColumnas = array('prop_id', 'pp_id',  'pp_id', 'pp_id', 'pt_id', 'pt_id', 'prop_directo', 'prop_estatus');

/* columna indexada */
$sIndexColumn = "prop_id";

// Paginacion
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
	$sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
}
$sOrder = '';

//Ordenacion
if (isset($_GET['iSortCol_0'])) {
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
	";*/
$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS prop_id, pp_id, pt_id, prop_directo, prop_estatus
	FROM  procesos_paletos
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

			if ($i != 1 and $i != 2 and $i != 3 and $i != 4 and $i != 5 and $i != 6 and $i != 7) {
				$row[] = $aRow[$aColumnas[$i]];
			} else if ($i == 1) {

				$sQuerys1b = "SELECT pp_descripcion FROM preparacion_paletos WHERE  pp_id = " . $aRow[$aColumnas[1]];
				$rResults1b = $mysqli->query($sQuerys1b);
				$aResultFilterTotals1b = $rResults1b->fetch_array();
				//$row[] = $aResultFilterTotals1b[0];

				if ($aRow[$aColumnas[1]] != '') {
					$sQuerys1 = "SELECT a.pp_descripcion FROM procesos_paletos_hist as h inner join preparacion_paletos as a on (h.pp_id = a.pp_id) WHERE  h.prop_id = " . $aRow[$aColumnas[0]];
					$rResults1 = $mysqli->query($sQuerys1);
					$aResultFilterTotals1 = $rResults1->fetch_array();
					$row[] = $aResultFilterTotals1b[0] . " " . $aResultFilterTotals1[0];
				} else {
					$row[] = $aResultFilterTotals1b[0];
				}
			} else if ($i == 2) {
				if ($aRow[$aColumnas[2]] != '') {

					$sQuerys2 = "SELECT pl_id FROM procesos as p inner join procesos_paletos_d as ppd 
						WHERE p.pro_id = ppd.pro_id and ppd.prop_id = " . $aRow[$aColumnas[0]];
					$rResults2 = $mysqli->query($sQuerys2);
					$aResultFilterTotals2 = $rResults2->fetch_array();
					$row[] = $aResultFilterTotals2[0];
				} else {
					$row[] = "-";
				}
			} else if ($i == 3) {
				if ($aRow[$aColumnas[3]] != '') {

					$sQuerys3 = "SELECT DISTINCT m.mat_nombre FROM procesos_materiales as pm inner join procesos_paletos_d as ppd on(pm.pro_id = ppd.pro_id) inner join materiales as m on(m.mat_id=pm.mat_id) where ppd.prop_id = " . $aRow[$aColumnas[0]];
					/*$rResults3 = $mysqli->query($sQuerys3);
						$aResultFilterTotals3 = $rResults3->fetch_array();
						$row[] = $aResultFilterTotals3[0];*/

					$rResults3 = $mysqli->query($sQuerys3);
					$aResultFilterTotals_nom3 = $rResults3->fetch_array();
					$aResultFilterTotals_num3 =  mysqli_num_rows($rResults3);
					if ($aResultFilterTotals_num3 > 1) {
						$row[] = "<b>Mezcla</b>";
					} else {
						$row[] = $aResultFilterTotals_nom3[0];
					}
				} else {
					$row[] = "-";
				}
			} else if ($i == 4) {
				if ($aRow[$aColumnas[4]] != '') {

					$sQuerys4 = "SELECT p.pro_total_kg FROM procesos as p inner join procesos_paletos_d as ppd WHERE p.pro_id = ppd.pro_id and ppd.prop_id = " . $aRow[$aColumnas[0]];
					$rResults4 = $mysqli->query($sQuerys4);
					$aResultFilterTotals4 = $rResults4->fetch_array();
					$row[] = $aResultFilterTotals4[0];
				} else {
					$row[] = "-";
				}
			} else if ($i == 5) {
				if ($aRow[$aColumnas[5]] != '') {

					$sQuerys5 = "SELECT pt_descripcion FROM preparacion_tipo WHERE pt_id = " . $aRow[$aColumnas[2]];
					$rResults5 = $mysqli->query($sQuerys5);
					$aResultFilterTotals5 = $rResults5->fetch_array();
					$row[] = $aResultFilterTotals5[0];
				} else {
					$row[] = "-";
				}
			} else if ($i == 6) {
				if ($aRow[$aColumnas[6]] == 1) {
					$row[] = "Si";
				} else {
					$row[] = "No";
				}
			} else if ($i == 7) {
				if ($aRow[$aColumnas[7]] == 1) {
					$row[] = "En proceso";
				} else {
					$row[] = "Terminado";
				}
			}
		}
	}


	$row[] = "<td><a href='../procesos/formatos/bitacora_paleto_consulta.php?idx_prop=" . $aRow['prop_id'] . "' target='_blank'><span class='glyphicon glyphicon-print'></span></a></td>";
	$row[] = "<td><a href='../procesos/formatos/bitacora_paleto_exporta.php?idx_prop=" . $aRow['prop_id'] . "' target='_blank'><span class='glyphicon glyphicon-circle-arrow-down'></span></a></td>";
	$row[] = "<td><a href='#' onClick='javascript:AbreModalInfopal(" . $aRow['prop_id'] . ", " . $aRow['pt_id'] . ")'><span class='glyphicon glyphicon-alert'></span></a></td>";


	$output['aaData'][] = $row;
}

echo json_encode($output);
