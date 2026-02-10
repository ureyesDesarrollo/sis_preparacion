<?php
include "../conexion/conexion.php";
$cnx =  Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cargar_equipos') {
  $sql = "SELECT ep.ep_descripcion, p.pro_id FROM equipos_preparacion ep
    INNER JOIN equipos_tipos et  ON et.et_tipo = ep.ep_tipo
    INNER JOIN procesos_equipos pe ON pe.ep_id = ep.ep_id
    INNER JOIN procesos p ON p.pro_id = pe.pro_id
    WHERE ep.ep_tipo = 'r' AND p.pro_estatus = 1";
  $res = mysqli_query($cnx, $sql);
  $equipos = array();
  while ($row = mysqli_fetch_assoc($res)) {
    $equipos[] = $row;
  }

  echo json_encode($equipos);
  exit;
}
?>

<style>
  .card-selectable {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid;
  }

  .card-selectable:hover {
    border-color: #adb5bd;
  }

  .card-selectable input:checked+div {
    font-weight: 600;
  }

  .card-selectable input:checked {
    accent-color: #0d6efd;
  }

  .card-selectable:has(input:checked) {
    border-color: #0d6efd;
    background-color: #f8f9fa;
  }
</style>

<div class="modal-dialog modal-xl modal-dialog-scrollable">
  <div class="modal-content">

    <!-- Header -->
    <div class="modal-header">
      <h5 class="modal-title" id="modalHojaViajeraLabel">Crear hoja viajera</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>

    <!-- Body -->
    <div class="modal-body">

      <h6 class="mb-3">Seleccionar equipos</h6>

      <div class="row g-3" id="cards-equipos">
      </div>
    </div>

    <!-- Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="button" class="btn btn-primary" id="btnCrearHojaViajeraForm">Crear</button>
    </div>

  </div>
</div>

<script>
  $(document).ready(function() {
    cargar_equipos();
  });

  $(document)
    .off('click', '#btnCrearHojaViajeraForm')
    .on('click', '#btnCrearHojaViajeraForm', function() {
      crear_hoja_viajera();
    });


  function crear_hoja_viajera() {

    let btn = $('#btnCrearHojaViajeraForm');

    if (btn.prop('disabled')) return;

    btn.prop('disabled', true);

    let equiposSeleccionados = [];

    $('input[name="equipos[]"]:checked').each(function() {
      equiposSeleccionados.push($(this).val());
    });

    if (equiposSeleccionados.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'Seleccione al menos un equipo',
        confirmButtonText: 'Aceptar',
      });
      btn.prop('disabled', false);
      return;
    }

    let form = $('<form>', {
      action: 'crear_hoja_viajera.php',
      method: 'POST',
      target: '_blank'
    });


    equiposSeleccionados.forEach(id => {
      form.append($('<input>', {
        type: 'hidden',
        name: 'procesos[]',
        value: id
      }));
    });

    $('body').append(form);
    form.submit();
    $('.modal').modal('hide');
    btn.prop('disabled', false);
  }



  function cargar_equipos() {
    $.ajax({
      type: 'POST',
      url: 'modal_hoja_viajera.php',
      data: {
        action: 'cargar_equipos'
      },
      dataType: 'json',
      success: function(equipos) {
        let cardsContainer = $('#cards-equipos');
        cardsContainer.empty();

        equipos.forEach(function(equipo) {
          let card = `<div class="col-md-4">
            <label class="card h-100 shadow-sm card-selectable">
            <div class="card-body d-flex align-items-center gap-3">
              <input class="form-check-input mt-0" type="checkbox" name="equipos[]" value="${equipo.pro_id}">
              <div>
                <h6 class="mb-1">${equipo.ep_descripcion}</h6>
                <small class="text-muted">Proceso: ${equipo.pro_id}</small>
              </div>
            </div>
          </label>
        </div>`;
          cardsContainer.append(card);
        });

      }
    });
  }
</script>
