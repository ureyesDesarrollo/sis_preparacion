<style>
  .rack-box {
    width: 85px;
    height: 85px;
    background: #0d6efd;
    color: white;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-size: 13px;
    text-align: center;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    transition: 0.2s;
  }

  .rack-box:hover {
    transform: scale(1.05);
    background: #084298;
  }
</style>


<div class="modal-dialog modal-xl modal-dialog-centered">
  <div class="modal-content border-0 shadow">

    <!-- Header -->
    <div class="modal-header">
      <div>
        <h5 class="modal-title mb-0">
          Conversión Total de Revoltura
        </h5>
        <small class="opacity-75">
          Visualización de posiciones asignadas
        </small>
      </div>
      <button type="button" class="btn-close btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">

      <input class="d-none" type="text" id="rev_id" value="<?= $_POST['rev_id'] ?>">
      <!-- Resumen -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="p-3 bg-light rounded">
            <small class="text-muted">Folio Revoltura</small>
            <h4 class="fw-bold mb-0" id="rev_folio">--</h4>
          </div>
        </div>

        <div class="col-md-6">
          <div class="p-3 bg-light rounded">
            <small class="text-muted">Kilos Totales</small>
            <h4 class="fw-bold text-primary mb-0" id="rev_kilos">0 kg</h4>
          </div>
        </div>
      </div>

      <!-- Vista Visual Tipo Mapa -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h6 class="mb-0 fw-bold">Mapa Visual de Racks</h6>
        </div>

        <div class="card-body">

          <div id="contenedorRacks" class="row g-4">

          </div>

        </div>
      </div>

      <!-- Advertencia -->
      <div class="alert alert-warning mt-4">
        Se convertirá el total de las posiciones mostradas en tarimas.
      </div>

    </div>

    <div class="modal-footer bg-light">
      <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Cancelar
      </button>
      <button class="btn btn-primary" id="btnConvertir">
        Confirmar Conversión Total
      </button>
    </div>

  </div>
</div>


<script>
  $(document).ready(function() {

    let rev_id = $("#rev_id").val();

    if (!rev_id) return;

    $.ajax({
      url: "funciones/revolturas_consultar_posiciones.php",
      type: "POST",
      data: {
        rev_id: rev_id
      },
      success: function(response) {

        if (!response || response.length === 0) {
          $("#contenedorRacks").html(
            "<div class='col-12 text-center text-muted'>Sin posiciones asignadas</div>"
          );
          return;
        }

        // Cargar resumen
        $("#rev_folio").text(response[0].rev_folio);
        $("#rev_kilos").text(parseFloat(response[0].rev_kilos).toFixed(2) + " kg");

        // Limpiar contenedor
        $("#contenedorRacks").empty();

        // Agrupar por rack
        let racks = {};

        response.forEach(item => {
          if (!racks[item.rac_descripcion]) {
            racks[item.rac_descripcion] = [];
          }

          racks[item.rac_descripcion].push(item.niv_codigo);
        });

        // Construir visual
        $.each(racks, function(rack, niveles) {

          let rackHtml = `
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <h6 class="fw-bold text-center mb-3">${rack}</h6>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                `;

          niveles.forEach(nivel => {
            rackHtml += `
                        <div class="rack-box">
                            <small>${nivel}</small>
                        </div>
                    `;
          });

          rackHtml += `
                            </div>
                        </div>
                    </div>
                `;

          $("#contenedorRacks").append(rackHtml);
        });

      },
      error: function() {
        $("#contenedorRacks").html(
          "<div class='col-12 text-danger text-center'>Error al cargar datos</div>"
        );
      }
    });


    $('#btnConvertir').click(function() {


      swal.fire({
        title: "¿Confirmar conversión total?",
        text: "Se convertirán todas las posiciones asignadas a tarimas.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, convertir",
        cancelButtonText: "No, cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "funciones/convertir_revolturas.php",
            type: "POST",
            data: {
              rev_id: rev_id
            },
            success: function(response) {
              let res = JSON.parse(response);
              if (res.status === 'success') {
                swal.fire({
                  title: "¡Conversión exitosa!",
                  text: res.message,
                  icon: "success"
                });
                $('#modal_convertir_revolturas').modal('hide');
                $('#dataTableRevolturas').DataTable().ajax.reload();
              } else if (res.status) {
                swal.fire({
                  title: "Error",
                  text: res.message,
                  icon: "error"
                });
              } else {
                swal.fire({
                  title: "Error",
                  text: "Ocurrió un error inesperado.",
                  icon: "error"
                });
              }
            },
            error: function() {
              alert("Error en la solicitud");
            }
          });
        }
      });

    })
  });
</script>
