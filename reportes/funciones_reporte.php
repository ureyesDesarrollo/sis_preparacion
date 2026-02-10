<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*04 - Mayo - 2020*/

#Descripción de función
/*
Se obtiene del promedio de los renglones de varias etapas, la columna que abría que promediar es "Temp Agua" se encuentra en las etapas 1, 1b, 3, 4c, 4d, 5, 5,b, 6b,6c, 6d, 7, 8, 8b y 8c, algo que habría que considerar es que no tome para el promedio los ceros en algunos casos no pueden checar la temperatura del agua y para fines de poder guardar el renglón colocan 0 pero si se promedia nos daría un dato incorrecto.
*/

require_once('../conexion/conexion.php');

//$reg_cad[prop_id] = '';

function fnc_temp_agua($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
	#Obtiene los prop_id
	$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
									FROM lotes_procesos AS l
									WHERE l.lote_id = '$intLote' ");
	$reg_cad = mysqli_fetch_assoc($str_cad);
	$reg_cad['prop_id'] = fnc_sproceso($intLote);

	do
	{
			
		#Obtiene los pro_id
		$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
									FROM procesos_paletos_d AS d
									WHERE prop_id = '$reg_cad[prop_id]' ");
		$reg_cadp = mysqli_fetch_assoc($str_cadp);
		$tot_cadp = mysqli_num_rows($str_cadp);
		
		#VA POR LOS REGISTROS DE CADA ETAPA
		$total_sum_val = 0;
		$total_ren_val = 0;
		
		if($tot_cadp > 0)
		{	
			do
			{ 
			
				//if($reg_cadp[prop_id] == ''){$reg_cadp[prop_id] = 1;}#	Quitar
			
				#Para las fases 1 y 1b
				$str_fa1 = 	mysqli_query($cnx, "select sum(d.pfd1_temp) as r1, count(d.pfd1_temp) as r2
												from procesos_fase_1_d as d
												INNER join procesos_fase_1_g as g on(d.pfg1_id = g.pfg1_id)
												where d.pfd1_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa1 = mysqli_fetch_assoc($str_fa1);
				
				#Para las fases 3
				$str_fa3 = 	mysqli_query($cnx, "select sum(d.pfd3_temp) as r1, count(d.pfd3_temp) as r2
												from procesos_fase_3_d as d
												where d.pfd3_temp <> 0 and d.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa3 = mysqli_fetch_assoc($str_fa3);
				
				#Para las fases 4c y 4d
				$str_fa4 = 	mysqli_query($cnx, "select sum(d.pfd4_temp) as r1, count(d.pfd4_temp) as r2
												from procesos_fase_4b_d as d
												INNER join procesos_fase_4b_g as g on(d.pfg4_id = g.pfg4_id)
												where d.pfd4_temp <> 0 and g.pro_id = '$reg_cadp[prop_id]' ");
				$reg_fa4 = mysqli_fetch_assoc($str_fa4);
				
				#Para las fases 5 y 5b
				$str_fa5 = 	mysqli_query($cnx, "select sum(d.pfd5_temp) as r1, count(d.pfd5_temp) as r2
												from procesos_fase_5_d as d
												INNER join procesos_fase_5_g as g on(d.pfg5_id = g.pfg5_id)
												where d.pfd5_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa5 = mysqli_fetch_assoc($str_fa5);
				
				#Para las fases 6b, 6c, 6d
				$str_fa6 = 	mysqli_query($cnx, "select sum(d.pfd6_temp) as r1, count(d.pfd6_temp) as r2
												from procesos_fase_6b_d as d
												INNER join procesos_fase_6b_g as g on(d.pfg6_id = g.pfg6_id)
												where d.pfd6_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa6 = mysqli_fetch_assoc($str_fa6);
				
				#Para las fases 7
				$str_fa7 = 	mysqli_query($cnx, "select sum(d.pfd7_temp) as r1, count(d.pfd7_temp) as r2
												from procesos_fase_7_d as d
												INNER join procesos_fase_7_g as g on(d.pfg7_id = g.pfg7_id)
												where d.pfd7_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa7 = mysqli_fetch_assoc($str_fa7);
				
				#Para las fases 8, 8b, 8c
				$str_fa8 = 	mysqli_query($cnx, "select sum(d.pfd8_temp) as r1, count(d.pfd8_temp) as r2
												from procesos_fase_8_d as d
												INNER join procesos_fase_8_g as g on(d.pfg8_id = g.pfg8_id)
												where d.pfd8_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa8 = mysqli_fetch_assoc($str_fa8);
				
				$total_sum_val = $reg_fa1['r1'] + $reg_fa3['r1'] + $reg_fa4['r1'] + $reg_fa5['r1'] + $reg_fa6['r1'] + $reg_fa7['r1'] + $reg_fa8['r1'];
				$total_ren_val = $reg_fa1['r2'] + $reg_fa3['r2'] + $reg_fa4['r2'] + $reg_fa5['r2'] + $reg_fa6['r2'] + $reg_fa7['r2'] + $reg_fa8['r2'];
				
				if($total_ren_val > 0)
				{
					return number_format(($total_sum_val / $total_ren_val),2);
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
		}
		
	}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_lav_ini($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
						
			do
			{ 		
				#Para las fases 1 y 1b
				$str_fa1 = 	mysqli_query($cnx, "select sum(prol_ce) as r1
												from procesos_liberacion
												where pe_id in (1,22) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa1 = mysqli_fetch_assoc($str_fa1);
				
				return number_format($reg_fa1['r1'],2);
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_hr_hidrolisis($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad[prop_id] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
					
			do
			{ 	
				#Para las fases 2b
				$str_fa = 	mysqli_query($cnx, "select sum(pfg2_enzima) as r1
												from procesos_fase_2b_g
												where pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa = mysqli_fetch_assoc($str_fa);
				
				return number_format($reg_fa['r1'],2);
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_ph_enzima($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
	#Obtiene los prop_id
	/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
									FROM lotes_procesos AS l
									WHERE l.lote_id = '$intLote' ");
	$reg_cad = mysqli_fetch_assoc($str_cad);*/
	$reg_cad['prop_id'] = fnc_sproceso($intLote);

	do
	{
			
		#Obtiene los pro_id
		$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
									FROM procesos_paletos_d AS d
									WHERE prop_id = '$reg_cad[prop_id]' ");
		$reg_cadp = mysqli_fetch_assoc($str_cadp);
		
		#VA POR LOS REGISTROS DE CADA ETAPA
		$total_sum_val = 0;
		$total_ren_val = 0;
			
		do
		{ 
		
			#Para las fases 2
			$str_fa2 = 	mysqli_query($cnx, "select sum(d.pfd2_ph) as r1, count(d.pfd2_ph) as r2
											from procesos_fase_2b_d as d
											INNER join procesos_fase_2b_g as g on(d.pfg2_id = g.pfg2_id)
											where d.pfd2_ph <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
			$reg_fa2 = mysqli_fetch_assoc($str_fa2);
			
			#Para las fases 2
			$str_fa2b = 	mysqli_query($cnx, "select sum(d.pfd22_ph) as r1, count(d.pfd22_ph) as r2
											from procesos_fase_2b_d2 as d
											INNER join procesos_fase_2b_g as g on(d.pfg2_id = g.pfg2_id)
											where d.pfd22_ph <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
			$reg_fa2b = mysqli_fetch_assoc($str_fa2b);
			
			$total_sum_val = $reg_fa2['r1'] + $reg_fa2b['r1'];
			$total_ren_val = $reg_fa2['r2'] + $reg_fa2b['r2'];
			
			if($total_sum_val != 0)
			{
				return number_format($total_sum_val / $total_ren_val,2);
			}
			else
			{
				return '';
			}
			
		}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
		
	}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_temp_enzima($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 2
				$str_fa2b = 	mysqli_query($cnx, "select sum(d.pfd22_temp) as r1, count(d.pfd22_temp) as r2
												from procesos_fase_2b_d2 as d
												INNER join procesos_fase_2b_g as g on(d.pfg2_id = g.pfg2_id)
												where d.pfd22_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2b = mysqli_fetch_assoc($str_fa2b);
				
				$total_sum_val = $reg_fa2b['r1'];
				$total_ren_val = $reg_fa2b['r2'];
				
				if($total_sum_val != 0)
				{
					return  number_format($total_sum_val / $total_ren_val,2);
				}
				else
				{
					return '';
				}
					
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_normalidad_sosa($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3
				$str_fa3 = 	mysqli_query($cnx, "select sum(d.pfd3_norm) as r1, count(d.pfd3_norm) as r2
												from procesos_fase_3b_d as d
												INNER join procesos_fase_3b_g as g on(d.pfg3_id = g.pfg3_id)
												where d.pfd3_norm <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa3 = mysqli_fetch_assoc($str_fa3);
				
				$total_sum_val = $reg_fa3['r1'];
				$total_ren_val = $reg_fa3['r2'];
				
				if($total_sum_val != 0)
				{
					return number_format($total_sum_val / $total_ren_val,2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_temp_sosa($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3
				$str_fa3 = 	mysqli_query($cnx, "select sum(d.pfd3_temp) as r1, count(d.pfd3_temp) as r2
												from procesos_fase_3b_d as d
												INNER join procesos_fase_3b_g as g on(d.pfg3_id = g.pfg3_id)
												where d.pfd3_temp <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa3 = mysqli_fetch_assoc($str_fa3);
				
				$total_sum_val = $reg_fa3['r1'];
				$total_ren_val = $reg_fa3['r2'];
				
				if($total_sum_val != 0)
				{
					return number_format($total_sum_val / $total_ren_val,2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_color_blanqueo($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
				
			do
			{ 
				#Para las fases 2 y 2c
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_color) as r1
												from procesos_liberacion
												where pe_id in (2,4) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);

				return number_format($reg_fa2["r1"],2);
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_l_peroxido($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3
				$str_fa2 = 	mysqli_query($cnx, "select sum(pfg2_peroxido + pfd2_peroxido) as r1
												from procesos_fase_2_d as d
												INNER join procesos_fase_2_g as g on(d.pfg2_id = g.pfg2_id)
												where g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);

				return number_format($reg_fa2["r1"],2);
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_c_lav_blanqueo($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_ce) as r1, count(prol_id) as r2
												from procesos_liberacion
												where pe_id in (5,9,23) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);				
				
				if($total_sum_val != 0)
				{
					return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_ppm_lav_blanqueo($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3
				$str_fa3 = 	mysqli_query($cnx, "select sum(pfd3_ppm) as r1, count(pfd3_ppm) as r2
												from procesos_fase_3_d 
												where pro_id = '$reg_cadp[pro_id]' ORDER BY pfd3_id desc ");
				$reg_fa3 = mysqli_fetch_assoc($str_fa3);
				
				#Para las fases 4d
				$str_fa4 = 	mysqli_query($cnx, "select sum(pfd4_ppm) as r1, count(pfd4_ppm ) as r2
												from procesos_fase_4_d as d
												INNER join procesos_fase_4_g as g on(d.pfg4_id = g.pfg4_id)
												where g.pro_id = '$reg_cadp[pro_id]' ORDER BY pfd4_id desc ");
				$reg_fa4 = mysqli_fetch_assoc($str_fa4);

				$total_sum_val = $reg_fa3["r1"] + $reg_fa4["r1"];
				$total_ren_val = $reg_fa3['r2'] + $reg_fa4["r2"];
				
				if($total_sum_val != 0)
				{
					return number_format($total_sum_val / $total_ren_val,2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_adel_1er_ac($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_adelgasamiento) as r1
												from procesos_liberacion
												where pe_id in (7,8,12,13,24) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);

				return number_format($reg_fa2["r1"],2);
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_ph_1er_ac($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_ph) as r1, count(prol_ph) as r2
												from procesos_liberacion
												where pe_id in (7,8,12,13,24) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);
						
				if($total_sum_val != 0)
				{
					return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_ce_1er_ac($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_ph) as r1, count(prol_ph) as r2
												from procesos_liberacion
												where pe_id in (10,11,15,16,25) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);
				
				if($total_sum_val != 0)
				{
					return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

function fnc_normalidad_2ac($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 6
				$str_fa6 = 	mysqli_query($cnx, "select sum(d.pfd6_norm) as r1, count(d.pfd6_norm) as r2
												from procesos_fase_6_d2 as d
												INNER join procesos_fase_6_g as g on(d.pfg6_id = g.pfg6_id)
												where d.pfd6_norm <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa6 = mysqli_fetch_assoc($str_fa6);
				
				#Para las fases 7b y 7c
				$str_fa7 = 	mysqli_query($cnx, "select sum(d.pfd7_norm) as r1, count(d.pfd7_norm) as r2
												from procesos_fase_7b_d as d
												INNER join procesos_fase_7b_g as g on(d.pfg7_id = g.pfg7_id)
												where d.pfd7_norm <> 0 and g.pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa7 = mysqli_fetch_assoc($str_fa7);

				
				$total_sum_val = $reg_fa6['r1'] + $reg_fa7['r1'];
				$total_ren_val = $reg_fa6['r2'] + $reg_fa7['r2'];
				
				if($total_sum_val != 0)
				{
					return number_format($total_sum_val / $total_ren_val,2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

#Obtiene el paleto.
function fnc_paleto($intLote)
{
	$cnx = Conectarse();
	$id = fnc_sproceso($intLote);

	$str_cad = 	mysqli_query($cnx, "SELECT prop_directo, pt_id 
										FROM procesos_paletos 
										WHERE prop_id = '$id' ");
	$reg_cad = mysqli_fetch_assoc($str_cad);
	
	if($reg_cad['prop_directo'] != 1 or $reg_cad['pt_id'] == '' )
	{
		$str_cad2 = 	mysqli_query($cnx, "SELECT pp_descripcion 
										FROM procesos_paletos_hist AS h
										INNER JOIN preparacion_paletos As p ON (h.pp_id = p.pp_id)
										WHERE h.prop_id = '$id' ");
		$reg_cad2 = mysqli_fetch_assoc($str_cad2);
		
		return $reg_cad2['pp_descripcion'];
	}
	else
	{
		return "NA";
	}	
	
	//echo "SELECT prop_directo FROM procesos_paletos WHERE prop_id = '$id' ";
}

#Obtiene el proceso
function fnc_sproceso($intLote)
{
	$cnx = Conectarse();
	
	$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
	$reg_cad = mysqli_fetch_assoc($str_cad);
	
	return $reg_cad['prop_id'];
}

#Obtiene el lavador.
function fnc_lavador($intLote)
{
	$cnx = Conectarse();
	$id = fnc_sproceso($intLote);
	
	$str_cad = 	mysqli_query($cnx, "SELECT prop_directo 
										FROM procesos_paletos 
										WHERE prop_id = '$id' ");
	$reg_cad = mysqli_fetch_assoc($str_cad);
	
	if($reg_cad['prop_directo'] != 1)
	{
		$str_cad2 = 	mysqli_query($cnx, "SELECT pl_descripcion 
										FROM procesos_paletos_d AS d
										INNER JOIN procesos as p ON (d.pro_id = p.pro_id)
										INNER JOIN preparacion_lavadores As l ON (p.pl_id = l.pl_id)
										WHERE d.prop_id = '$id' ");
		$reg_cad2 = mysqli_fetch_assoc($str_cad2);
		
		return $reg_cad2['pl_descripcion'];
	}
	else
	{
		return "NA";
	}	
}

#Obtiene el procesos
function fnc_procesos($intLote)
{
	$cnx = Conectarse();
	$id = fnc_sproceso($intLote);
	
	$str_cad = 	mysqli_query($cnx, "SELECT prop_directo 
										FROM procesos_paletos 
										WHERE prop_id = '$id' ");
	$reg_cad = mysqli_fetch_assoc($str_cad);
	
	if($reg_cad['prop_directo'] != 1)
	{
		$str_cad2 = 	mysqli_query($cnx, "SELECT pro_id
										FROM procesos_paletos_d AS d
										WHERE d.prop_id = '$id' ");
		$reg_cad2 = mysqli_fetch_assoc($str_cad2);
		
		return $reg_cad2['pro_id'];
	}
	else
	{
		return "NA";
	}	
}

#Obtiene el tipo de preparación
function fnc_tipo($intLote)
{
	$cnx = Conectarse();
	$id = fnc_sproceso($intLote);
	
	$str_cad2 = 	mysqli_query($cnx, "SELECT p.pt_id, t.pt_descripcion 
										FROM procesos_paletos as p
										LEFT JOIN preparacion_tipo as t ON (p.pt_id = t.pt_id)
										WHERE p.prop_id = '$id' ");
	$reg_cad2 = mysqli_fetch_assoc($str_cad2);
	
	if($reg_cad2['pt_id'] == 1 or $reg_cad2['pt_id'] == 3 or $reg_cad2['pt_id'] == 7)
	{	
		return "A";
	}
	if($reg_cad2['pt_id'] == 4 or $reg_cad2['pt_id'] == 5)
	{	
		return "E";
	}
	if($reg_cad2['pt_id'] == 2)
	{	
		return "S";
	}
	if($reg_cad2['pt_id'] == '' or $reg_cad2['pt_id'] == '6')
	{	
		return "X";
	}

}

#Obtiene parametros de liberación
function fnc_lib_b_ce($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$lot = fnc_sproceso($intLote);
	
		do
		{
		
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$lot' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_ce1) as r1, count(prol_ce1) as r2
												from procesos_liberacion_b
												where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);
				
				
				if($reg_fa2["r2"] != 0)
				{
					return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
				}
				else
				{
					return '';
				}
				//return number_format($reg_fa2["r1"] ,2);
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

#Obtiene parametros de liberación cocido ph
function fnc_lib_b_ph2($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_cocido_ph2) as r1, count(prol_cocido_ph2) as r2
												from procesos_liberacion_b
												where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);
				
				if($reg_fa2["r2"] != 0)
				{
					return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

#Obtiene parametros de liberación solidos
function fnc_lib_b_sol($intLote)
{	
	$cnx = Conectarse();

	if($intLote != '')
	{
		#Obtiene los prop_id
		/*$str_cad = 	mysqli_query($cnx, "SELECT prop_id 
										FROM lotes_procesos AS l
										WHERE l.lote_id = '$intLote' ");
		$reg_cad = mysqli_fetch_assoc($str_cad);*/
		$reg_cad['prop_id'] = fnc_sproceso($intLote);
	
		do
		{
				
			#Obtiene los pro_id
			$str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
										FROM procesos_paletos_d AS d
										WHERE prop_id = '$reg_cad[prop_id]' ");
			$reg_cadp = mysqli_fetch_assoc($str_cadp);
			
			#VA POR LOS REGISTROS DE CADA ETAPA
			$total_sum_val = 0;
			$total_ren_val = 0;
				
			do
			{ 
				
				#Para las fases 3,4c,4d
				$str_fa2 = 	mysqli_query($cnx, "select sum(prol_solides) as r1, count(prol_solides) as r2
												from procesos_liberacion_b
												where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
				$reg_fa2 = mysqli_fetch_assoc($str_fa2);
				
				if($reg_fa2["r2"] != 0)
				{
					return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
				}
				else
				{
					return '';
				}
				
			}while($reg_cadp = mysqli_fetch_assoc($str_cadp));
			
		}while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	}
}

#Obtiene parametros de %ext
function fnc_lib_b_ext($id_pro)
{	
	$cnx = Conectarse();
	
    do
    {

        #Obtiene los pro_id
        $str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
                                    FROM procesos_paletos_d AS d
                                    WHERE prop_id = '$id_pro' ");
        $reg_cadp = mysqli_fetch_assoc($str_cadp);

        #VA POR LOS REGISTROS DE CADA ETAPA
        $total_sum_val = 0;
        $total_ren_val = 0;

        do
        { 

            #Para las fases 3,4c,4d
            $str_fa2 = 	mysqli_query($cnx, "select sum(prol_cocido_lib) as r1, count(prol_cocido_lib) as r2
                                            from procesos_liberacion_b
                                            where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
            $reg_fa2 = mysqli_fetch_assoc($str_fa2);

            if($reg_fa2["r2"] != 0)
            {
                return number_format($reg_fa2["r1"] / $reg_fa2["r2"],2);
            }
            else
            {
                return '';
            }

        }while($reg_cadp = mysqli_fetch_assoc($str_cadp));

    }while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	
}

#Obtiene parametros de color
function fnc_lib_b_color($id_pro)
{	
	$cnx = Conectarse();
	
    do
    {

        #Obtiene los pro_id
        $str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
                                    FROM procesos_paletos_d AS d
                                    WHERE prop_id = '$id_pro' ");
        $reg_cadp = mysqli_fetch_assoc($str_cadp);

        #VA POR LOS REGISTROS DE CADA ETAPA
        $total_sum_val = 0;
        $total_ren_val = 0;

        do
        { 

            #Para las fases 3,4c,4d
            $str_fa2 = 	mysqli_query($cnx, "select prol_color as r1
                                            from procesos_liberacion_b
                                            where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
            $reg_fa2 = mysqli_fetch_assoc($str_fa2);

            return $reg_fa2["r1"];

        }while($reg_cadp = mysqli_fetch_assoc($str_cadp));

    }while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	
}

function fnc_lib_b_fecha($id_pro)
{	
	$cnx = Conectarse();
	
    do
    {

        #Obtiene los pro_id
        $str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
                                    FROM procesos_paletos_d AS d
                                    WHERE prop_id = '$id_pro' ");
        $reg_cadp = mysqli_fetch_assoc($str_cadp);

        #VA POR LOS REGISTROS DE CADA ETAPA
        $total_sum_val = 0;
        $total_ren_val = 0;

        do
        { 

            #Para las fases 3,4c,4d
            $str_fa2 = 	mysqli_query($cnx, "select prol_fecha as r1
                                            from procesos_liberacion_b
                                            where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
            $reg_fa2 = mysqli_fetch_assoc($str_fa2);

            return $reg_fa2["r1"];

        }while($reg_cadp = mysqli_fetch_assoc($str_cadp));

    }while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	
}

function fnc_lib_b_hora($id_pro)
{	
	$cnx = Conectarse();
	
    do
    {

        #Obtiene los pro_id
        $str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
                                    FROM procesos_paletos_d AS d
                                    WHERE prop_id = '$id_pro' ");
        $reg_cadp = mysqli_fetch_assoc($str_cadp);

        #VA POR LOS REGISTROS DE CADA ETAPA
        $total_sum_val = 0;
        $total_ren_val = 0;

        do
        { 

            #Para las fases 3,4c,4d
            $str_fa2 = 	mysqli_query($cnx, "select prol_hora as r1
                                            from procesos_liberacion_b
                                            where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
            $reg_fa2 = mysqli_fetch_assoc($str_fa2);

            return $reg_fa2["r1"];

        }while($reg_cadp = mysqli_fetch_assoc($str_cadp));

    }while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	
}

function fnc_lib_b_user($id_pro)
{	
	$cnx = Conectarse();
	
    do
    {

        #Obtiene los pro_id
        $str_cadp = 	mysqli_query($cnx, "SELECT pro_id 
                                    FROM procesos_paletos_d AS d
                                    WHERE prop_id = '$id_pro' ");
        $reg_cadp = mysqli_fetch_assoc($str_cadp);

        #VA POR LOS REGISTROS DE CADA ETAPA
        $total_sum_val = 0;
        $total_ren_val = 0;

        do
        { 

            #Para las fases 3,4c,4d
            $str_fa2 = 	mysqli_query($cnx, "select usu_id as r1
                                            from procesos_liberacion_b
                                            where pe_id in (17,20,21,26) and pro_id = '$reg_cadp[pro_id]' ");
            $reg_fa2 = mysqli_fetch_assoc($str_fa2);

            return $reg_fa2["r1"];

        }while($reg_cadp = mysqli_fetch_assoc($str_cadp));

    }while($reg_cad = mysqli_fetch_assoc($str_cad));
	
	
}
?>