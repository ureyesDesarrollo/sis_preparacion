// JavaScript Document

$(document).ready(function()
{
	/* $("#formFase1").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_1_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase1Ope", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase1').each (function(){this.reset();});  
				setTimeout("location.reload()", 2000);	 
			}
		});
		return false;
		
	}); */
	
	/* $("#formFase1b").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_1b_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase1bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase1b').each (function(){this.reset();});  
				setTimeout("location.reload()", 2000);	 
			}
		});
		return false;
		
	}); */
	

	/* $("#formFase2").submit(function(){

	var formData = $(this).serialize();
	$.ajax({
		url: "../procesos/fase_2_insertar.php",
		type: 'POST',
		data: formData,
		success: function(result) {

			data = JSON.parse(result);
			alertas("#alerta-errorFase2Ope", 'Listo!', data["mensaje"], 1, true, 5000);
			$('#formFase2').each (function(){this.reset();}); 
			setTimeout("location.reload()", 2000); 
		}
	});
	return confirmEnviarFase2();
	return false;
	}); */

	//Bloquear boton al agregar material
		/* function confirmEnviarFase2() {

			formFase2.btn.disabled = true; 
			formFase2.btn.value = "Enviando...";

			setTimeout(function(){
				formFase2.btn.disabled = true;
				formFase2.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		} */
	
/* 	$("#formFase2c").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_2c_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase2cOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase2c').each (function(){this.reset();}); 
				setTimeout("location.reload()", 2000); 
			}
		});
		return confirmEnviarFase2c();
		return false;
		
	});


	//Bloquear boton al agregar material
		function confirmEnviarFase2c() {

			formFase2c.btn.disabled = true; 
			formFase2c.btn.value = "Enviando...";

			setTimeout(function(){
				formFase2c.btn.disabled = true;
				formFase2c.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		} */
	
/* 	$("#formFase2b").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_2b_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase2bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase2b').each (function(){this.reset();}); 
				setTimeout("location.reload()", 2000); 
			}
		});
		return confirmEnviarFase2b();
		return false;

	});

	
	//Bloquear boton al agregar material
		function confirmEnviarFase2b() {

			formFase2b.btn.disabled = true; 
			formFase2b.btn.value = "Enviando...";

			setTimeout(function(){
				formFase2b.btn.disabled = true;
				formFase2b.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}
	 */

	/* $("#formFase4b").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_4b_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase4bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase4b').each (function(){this.reset();}); 
				setTimeout("location.reload()", 2000); 
			}
		});
		return false;

	}); */
	/* 
	$("#formFase4c").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_4c_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase4cOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase4c').each (function(){this.reset();}); 
				setTimeout("location.reload()", 2000); 
			}
		});
		return false;

	});
	 */
/* 	$("#formFase4d").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_4d_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase4dOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase4d').each (function(){this.reset();}); 
				setTimeout("location.reload()", 2000); 
			}
		});
		return false;

	}); */
	
/*	$("#formFase3").submit(function(){

	var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_3_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {
				data = JSON.parse(result);
				alertas("#alerta-errorFase3Ope", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase3').each (function(){this.reset();});  
				//setTimeout("location.reload()", 2000);	 
			}
		});
	return false;
	});*/
	
	/*$("#formFase3b").submit(function(){

		var formData = $(this).serialize();
		$.ajax({
			url: "../procesos/fase_3b_insertar.php",
			type: 'POST',
			data: formData,
			success: function(result) {

				data = JSON.parse(result);
				alertas("#alerta-errorFase3bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
				$('#formFase3b').each (function(){this.reset();}); 
				//setTimeout("location.reload()", 2000); 

			}
		});
		return false;
	});*/
	
});