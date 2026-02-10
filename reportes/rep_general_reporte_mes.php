<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include('../generales/menu.php');
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include('../seguridad/user_seguridad.php');
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
	

/*Abrir Modal Editar*/
function AbreModalEditar(id, tipo)
{ 
	$.ajax({
		type : 'post',
		url : 'bitacora_editar.php', 
		data : {"pro_id":id, "hdd_tipo": tipo}, //Pass $id
		success : function(result){
			$("#modalEditar").html(result);
			$('#modalEditar').modal('show')
		}
	});
	return false;
};

function refresh()
{
	location.reload();
}



/*Abrir Modal info parametros*/
function AbreModalInfo(id, tipo)
{ 
	$.ajax({
		type : 'post',
		url : 'desglose_parametros.php', 
		data : {"pro_id":id, "hdd_tipo": tipo}, //Pass $id
		success : function(result){
			$("#modalInfoParam").html(result);
			$('#modalInfoParam').modal('show')
		}
	});
	return false;
};


function AbreModalInfopal(id, tipo)
{ 
	$.ajax({
		type : 'post',
		url : 'desglose_parametros_pal.php', 
		data : {"prop_id":id, "hdd_tipo": tipo}, //Pass $id
		success : function(result){
			$("#modalInfoParampal").html(result);
			$('#modalInfoParampal').modal('show')
		}
	});
	return false;
};

/*Para cambiar el estatus a B*/
function fnc_cerrar(id){
	var respuesta = confirm("¿Deseas cerrar este proceso?");
	if (respuesta == true){
		$.ajax({
			url: 'bitacora_cerrar.php',
			data: 'id=' + id,
			type: 'post',
			success: function(result){
				data = JSON.parse(result);
				setTimeout("location.reload()", 1000)
			}
		});
		return false;
	}
}

/*Para cambiar eliminar el proceso*/
function fnc_quitar(id){
	var respuesta = confirm("¿Deseas eliminar este proceso?");
	if (respuesta == true){
		$.ajax({
			url: 'bitacora_eliminar.php',
			data: 'id=' + id,
			type: 'post',
			success: function(result){
				data = JSON.parse(result);
				setTimeout("location.reload()", 2000)
			}
		});
		return false;
	}
}
//AGREGADO CC
 
 //Filtro en tiempo real
  function buscar() {
    var textoBusqueda = $("#txtLote").val();
     var mes = $("#mes").val();

    if (textoBusqueda != "") {
      $.post("extras/buscar_lote.php", {txtLote: textoBusqueda, mes:mes}, function(mensaje) {
        $("#listado_promedio").html(mensaje);
      }); 
    } else { 
      $("#listado_promedio").html('<p>&nbsp;&nbsp;&nbsp;&nbsp;Consulta Vacia</p>');
      setTimeout(location.reload(), 3000);
    };
  };

  

//Exportar a excel
function exportar(){
		var mes = document.getElementById('mes').value;
		window.open('exportar/listado_promedio_exp.php?mes='+ encodeURIComponent(mes));
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>


<style>
  @media print{
    .ocultar{
      display: none !important;
    }
    @page {
      size: A4 landscape; 
    }


    .rol{
    overflow:hidden

  }

  table{
  	font-size: 2px
  }
}
</style>

<div class="container-fluid ocultar">
	<div class="diviconos">
		<div class="col-sm-1 col-md-7" >
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#listado_promedio">Reporte promedio</a></li>
				<!--<li><a data-toggle="tab" href="#">Reporte</a></li>-->
			</ul>
		</div>
		<div class="col-sm-2 col-md-2">
			<label for="">Lote</label>
			<input style="margin-top: " type="text" id="txtLote" name="txtLote" class="form-control input-sm" onKeyUp="buscar();" placeholder="Buscar...">			
		</div>
		<div class="col-sm-1 col-md-1">
			<a class="iconos"   href="formatos/listado_promedio.php?mes=<?php echo $_GET['mes']; ?>;" 
			 target="_blank"><img src="../iconos/printer.png" alt="">
			Imprimir</a>

		</div>
		<div class="col-sm-1 col-md-1">
			<input type="hidden" id="mes" value="<?php echo $_GET['mes'] ?>">
			<a class="iconos"  href="#" onclick="exportar();"><img src="../iconos/excel.png" alt="">
			Exp.excel</a>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="tab-content">
		<div id="listado_promedio" class="tab-pane fade in active">
			<?php include "listado_promedio.php";?>
		</div>
	</div>
</div>
<?php include "../generales/pie_pagina.php";?>