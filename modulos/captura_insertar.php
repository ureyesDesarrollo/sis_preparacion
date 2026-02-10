<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 

for($i = 1; $i <= 25; $i++)
{ 
	$txtid = ${"txtid".$i};
	$valor = ${"valor".$i};
	$txtFecha = ${"txtFecha".$i};
	$txtLimParam = ${"txtLimParam".$i};
	$txtBloom =${"txtBloom".$i};
	$txtVisc = ${"txtVisc".$i};
	$txtPhFin =${"txtPhFin".$i};
	$txtTrans = ${"txtTrans".$i};
	$txtPorcenT = ${"txtPorcenT".$i};
	$txtNtu= ${"txtNtu".$i};
	$txtHumedad = ${"txtHumedad".$i};
	$txtCenizas = ${"txtCenizas".$i};
	$txtRedox = ${"txtRedox".$i};
	$txtColor = ${"txtColor".$i};
	$txtGrano = ${"txtGrano".$i};
	$txtOlor = ${"txtOlor".$i};
	$txtPartExt = ${"txtPartExt".$i};
	$txtPartInd = ${"txtPartInd".$i};
	$txtHidratacion = ${"txtHidratacion".$i};
	$txtAcepRech = ${"txtAcepRech".$i};
	
	if ($txtLimParam == ''){ $txtLimParam = 0; }       
	if ($txtBloom    == ''){ $txtBloom    = 0; }    
	if ($txtVisc     == ''){ $txtVisc     = 0; }   
	if ($txtPhFin    == ''){ $txtPhFin    = 0; }    
	if ($txtTrans    == ''){ $txtTrans    = 0; }    
	if ($txtPorcenT  == ''){ $txtPorcenT  = 0; }      
	if ($txtNtu      == ''){ $txtNtu      = 0; } 
	if ($txtHumedad  == ''){ $txtHumedad  = 0; }      
	if ($txtCenizas  == ''){ $txtCenizas  = 0; }      
	if ($txtRedox    == ''){ $txtRedox    = 0; }    
	if ($txtColor    == ''){ $txtColor    = 0; }    
	if ($txtGrano    == ''){ $txtGrano    = 0; }    
	if ($txtOlor     == ''){ $txtOlor     = 0; }   
	if ($txtPartExt  == ''){ $txtPartExt  = 0; }      
	if ($txtPartInd  == ''){ $txtPartInd  = 0; }      
	
	if ($txtLimParam != '' OR $txtBloom != '' OR $txtVisc != '' OR $txtPhFin != '' OR $txtTrans != '' OR $txtPorcenT != '' OR $txtNtu != '' OR $txtHumedad != '' OR $txtCenizas != '' OR $txtRedox != '' OR $txtColor != '' OR $txtGrano != '' OR $txtOlor != '' OR $txtPartExt != '' OR $txtPartInd) {
		
		if ($txtid == '0') {
		mysqli_query($cnx, "INSERT INTO tarimas(lote_id,tarima_fecha,tarima_lim_param,tarima_bloom,tarima_viscocidad,tarima_ph_final,tarima_transparencia,tarima_porcen_t,tarima_ntu,tarima_humedad,tarima_cenizas,tarima_redox,tarima_color,tarima_grano,tarima_olor,tarima_part_ext,tarima_part_ind,tarima_hidratacion,tarima_aceptado) VALUES ('$hdd_id', '$txtFecha','$txtLimParam','$txtBloom','$txtVisc','$txtPhFin','$txtTrans','$txtPorcenT','$txtNtu','$txtHumedad','$txtCenizas','$txtRedox','$txtColor','$txtGrano','$txtOlor','$txtPartExt','$txtPartInd','$txtHidratacion','$txtAcepRech')") or die(mysqli_error($cnx)." Error al insertar en consulta uno [".$i."]");
	}
	if ($txtid == '1') {
		mysqli_query($cnx, "UPDATE tarimas SET tarima_fecha='$txtFecha', tarima_lim_param = '$txtLimParam', tarima_bloom = '$txtBloom', tarima_viscocidad = '$txtVisc', tarima_ph_final = '$txtPhFin', tarima_transparencia = '$txtTrans',tarima_porcen_t = '$txtPorcenT', tarima_ntu = '$txtNtu',tarima_humedad = '$txtHumedad',tarima_cenizas = '$txtCenizas',tarima_redox = '$txtRedox',tarima_color = '$txtColor',tarima_grano = '$txtGrano',tarima_olor = '$txtOlor',tarima_part_ext = '$txtPartExt',tarima_part_ind = '$txtPartInd',tarima_hidratacion = '$txtHidratacion',tarima_aceptado = '$txtAcepRech' WHERE lote_id = '$hdd_id' AND tarima_id ='$valor'") or die(mysqli_error($cnx)." Error al actaulizar en consulta dos[".$i."]");

	}

	}
	
}

mysqli_query($cnx, "UPDATE lotes SET lote_lim_param = '$limite', lote_bloom = '$bloom', lote_viscocidad = '$viscosidad', lote_ph_final = '$phfinal', lote_transparencia = '$transparencia',lote_porcen_t = '$porcentaje', lote_ntu = '$ntu',lote_humedad = '$humedad',lote_cenizas = '$cenizas',lote_redox = '$redox',lote_color = '$color',lote_grano = '$grano',lote_olor = '$olor',lote_part_ext = '$partE',lote_part_ind = '$partI' WHERE lote_id = '$hdd_id'") or die(mysqli_error($cnx)." Error al actaulizar en lotes");

$respuesta = array('mensaje' => "Registro guardado ");
echo json_encode($respuesta);
?> 