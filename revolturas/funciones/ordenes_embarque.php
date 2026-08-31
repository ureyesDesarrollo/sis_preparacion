<?php 
include "../../seguridad/user_seguridad.php";
?>

<style>
  .btn-accion {
    background: transparent;
    padding: 0.45rem 1.3rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    letter-spacing: 0.3px;
    backdrop-filter: blur(2px);
  }

  .btn-accion-primary {
    border: 1.5px solid #0a2472;
    color: #0a2472;
    box-shadow: 0 2px 8px rgba(10, 36, 114, 0.1);
  }

  .btn-accion-primary:hover {
    background: #0a2472;
    color: white;
    border-color: #0a2472;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(10, 36, 114, 0.25);
  }

  .btn-accion-danger {
    border: 1.5px solid #dc3545;
    color: #dc3545;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.12);
  }

  .btn-accion-danger:hover {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(220, 53, 69, 0.28);
  }

  .btn-accion-disabled {
    border: 1.5px solid #6c757d;
    color: #6c757d;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.12);
    cursor: not-allowed;
    opacity: 0.65;
  }

  .btn-accion-disabled:hover {
    border: 1.5px solid #6c757d;
    color: #6c757d;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.12);
    cursor: not-allowed;
    opacity: 0.65;
  }

</style>

<script>
  $(document).ready(function() {
    $('#dataTableOrdenEmbarque').DataTable({
      responsive: true,
      bDestroy: true,
      language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ )",
        search: "Buscar:",
        zeroRecords: "No se encontraron registros coincidentes",
        paginate: {
          next: "Siguiente",
          previous: "Anterior"
        },
      },
      order: [
        [0, 'desc']
      ],
      "sDom": "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 'B><'col-sm-12 col-md-4'f>r>t<'row'<'col-md-4'i>><'row'p>",
      buttons: {
        dom: {
          button: {
            className: 'btn' //Primary class for all buttons
          },
        },
        buttons: [{
            //Botón para Excel
            extend: 'excel',
            footer: true,
            title: 'Listado Embarque - Ordenes',
            filename: 'Listado_embarque_ordenes_excel',

            //Aquí es donde generas el botón personalizado
            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          },
          {
            //Botón para PDF
            extend: 'pdf',
            footer: true,
            title: 'Listado Embarque - Ordenes',
            filename: 'Listado_embarque_ordenes_pdf',
            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          },
          //Botón para print
          {
            extend: 'print',
            footer: true,
            title: 'Listado Embarque - Ordenes',
            filename: 'Listado_embarque_ordenes_print',
            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          }
        ]
      },
      ajax: {
        url: 'funciones/ordenes_embarque_detalle_listado.php',
        dataSrc: ''
      },
      columns: [{
          data: 'fecha_creacion'
        },
        {
          data: 'cliente_nombre'
        },
        {
          data: 'factura',
          render: function(data, type, row) {
            return data ? data : 'Factura no relacionada';
          }
        },
        {
          data: 'estado',
          render: function(data, type, row) {
            if (type === 'export') return data; // Para exportaciones mantiene el texto plano

            const badges = {
              'PENDIENTE': {
                class: 'bg-warning bg-gradient text-dark',
                icon: '<i class="fa-regular fa-clock me-1"></i>'
              },
              'PROCESO': {
                class: 'bg-info bg-gradient text-dark',
                icon: '<i class="fa-solid fa-rotate me-1 fa-spin"></i>'
              },
              'EN PROCESO': {
                class: 'bg-info bg-gradient text-dark',
                icon: '<i class="fa-solid fa-rotate me-1 fa-spin"></i>'
              },
              'ETIQUETA LIBERADA': {
                class: 'bg-primary bg-gradient',
                icon: '<i class="fa-solid fa-tag me-1"></i>'
              },
              'LIBERADO': {
                class: 'bg-success bg-gradient',
                icon: '<i class="fa-regular fa-circle-check me-1"></i>'
              },
              'COMPLETADO': {
                class: 'bg-success bg-gradient',
                icon: '<i class="fa-regular fa-circle-check me-1"></i>'
              },
              'COMPLETADA': {
                class: 'bg-success bg-gradient',
                icon: '<i class="fa-regular fa-circle-check me-1"></i>'
              },
              'CANCELADO': {
                class: 'bg-danger bg-gradient',
                icon: '<i class="fa-regular fa-circle-xmark me-1"></i>'
              },
              'CANCELADA': {
                class: 'bg-danger bg-gradient',
                icon: '<i class="fa-regular fa-circle-xmark me-1"></i>'
              },
              'FACTURADA': {
                class: 'bg-success bg-gradient',
                icon: '<i class="fa-solid fa-file-invoice me-1"></i>'
              }
            };

            const badge = badges[data] || {
              class: 'bg-secondary bg-gradient',
              icon: '<i class="fa-regular fa-question-circle me-1"></i>'
            };

            return `<span class="badge ${badge.class} px-3 py-2 rounded-pill fw-semibold shadow-sm">
                          ${badge.icon}${data}
                    </span>`;
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function(data, type, row) {

            if (type !== 'display') return '';

            return crearBotonAccion({
              id: row.orden_id,
              clase: 'btn-detalle',
              titulo: 'Ver detalles completos de la orden',
              icono: 'fa-solid fa-circle-info',
              texto: 'Detalles'
            });
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function(data, type, row) {

            if (type !== 'display') return '';

            return crearBotonAccion({
              id: row.orden_id,
              clase: 'btn-recibo',
              titulo: 'Recibo de embarque',
              icono: 'fa-solid fa-receipt',
              texto: 'Recibo de embarque'
            });
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function(data, type, row) {

            if (type !== 'display') return '';

            //Mostrar el botón de cancelar solo si el estado es "PENDIENTE"

            if(row.estado !== 'PENDIENTE') {
              return  crearBotonAccion({
              id: row.orden_id,
              clase: 'btn-cancelar',
              titulo: 'Cancelar orden de embarque',
              icono: 'fa-solid fa-ban',
              texto: 'Cancelar',
              tipo: 'disabled'
            });
            }

            return crearBotonAccion({
              id: row.orden_id,
              clase: 'btn-cancelar',
              titulo: 'Cancelar orden de embarque',
              icono: 'fa-solid fa-ban',
              texto: 'Cancelar',
              tipo: 'danger'
            });
          }, 
          visible: <?= json_encode($_SESSION['privilegio'] == 2) ?>
          }
      ]
    });

    function crearBotonAccion({
      id,
      clase,
      titulo,
      icono,
      texto,
      tipo = 'primary'
    }) {
      return `
        <button
            type="button"
            class="btn btn-sm btn-accion btn-accion-${tipo} ${clase}"
            data-id="${id}"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="${titulo}"
        >
            <i class="${icono}"></i>
            <span class="d-none d-md-inline">${texto}</span>
        </button>
    `;
    }


    $('#dataTableOrdenEmbarque').on('click', '.btn-detalle', function() {
      let idOrden = $(this).data('id');
      abrir_modal_detalle_orden_embarque(idOrden);
    });

    $('#dataTableOrdenEmbarque').on('click', '.btn-recibo', function() {
      let idOrden = $(this).data('id');
      window.open(`funciones/recibo_embarque.php?orden_id=${idOrden}`, "_blank");
    });

    $('#dataTableOrdenEmbarque').on('click', '.btn-cancelar', function() {

      const idOrden = $(this).data('id');

      console.log('Cancelar orden:', idOrden);

      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cancelar orden',
        cancelButtonText: 'No, cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'POST',
            url: 'funciones/ordenes_embarque_cancelar.php',
            data: JSON.stringify({ oe_id: idOrden }),
            success: function(response) {
              const res = JSON.parse(response);
              if (res.success) {
                Swal.fire(
                  '¡Cancelada!',
                  res.message,
                  'success'
                );
                $('#dataTableOrdenEmbarque').DataTable().ajax.reload();
              } else {
                Swal.fire(
                  'Error',
                  res.message,
                  'error'
                );
              }
            },
            error: function() {
              Swal.fire(
                'Error',
                'Ocurrió un error al procesar la solicitud.',
                'error'
              );
            }
          });
        }
      });
    });


  });
</script>


<div class="container-fluid">
  <div class="row mb-3 mt-3">
    <div class="col-md-7">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            Ordenes de embarque - Listado
          </li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
      <table class="table table-hover display" cellpadding="0" cellspacing="0" id="dataTableOrdenEmbarque" style="width: 100%;">
        <thead>
          <tr>
            <th>Fecha solicitud</th>
            <th>Cliente</th>
            <th>Factura</th>
            <th>Estatus</th>
            <th>Detalles</th>
            <th>Recibo</th>
            <th>Cancelar</th>
          </tr>
        </thead>
        <tbody>
          <tr>
          </tr>
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="modal_detalle_orden_embarque" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
  function abrir_modal_detalle_orden_embarque(idOrden) {
    console.log(idOrden);
    $.ajax({
      type: 'POST',
      url: 'funciones/ordenes_embarque_detalle_modal.php',
      data: {
        oe_id: idOrden
      },
      success: function(result) {
        $('#modal_detalle_orden_embarque').html(result);
        $('#modal_detalle_orden_embarque').modal('show');
      }
    });
  }
</script>