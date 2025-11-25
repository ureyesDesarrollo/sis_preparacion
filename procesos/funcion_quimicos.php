<script>
	function quimicos(proceso, etapa){
		var datos={
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type:'post',
			url: 'quimicos/modal_quimicos.php',
			data: datos,
			success: function(result){
				$("#m_modal_quimicos").html(result);
				$('#m_modal_quimicos').modal('show')
			}	
		});
		return false;
	}
</script>

<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
