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
          render: function(data, type, row) {
            if (type === 'export') return '';

            const ordenId = typeof row.orden_id === 'object' ? JSON.stringify(row.orden_id) : `"${row.orden_id}"`;

            return `<button class="btn btn-sm btn-detalle"
            data-id=${ordenId}
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Ver detalles completos de la orden"
            style="
              background: transparent;
              border: 1.5px solid #0a2472;
              color: #0a2472;
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
              box-shadow: 0 2px 8px rgba(10, 36, 114, 0.1);
              letter-spacing: 0.3px;
              backdrop-filter: blur(2px);
            "
            onmouseover="this.style.background='#0a2472'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(10, 36, 114, 0.25)'; this.style.borderColor='#0a2472';"
            onmouseout="this.style.background='transparent'; this.style.color='#0a2472'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(10, 36, 114, 0.1)'; this.style.borderColor='#0a2472';">
      <i class="fa-solid fa-receipt" style="font-size: 0.95rem;"></i>
      <span class="d-none d-md-inline">Detalles</span>
    </button>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            if (type === 'export') return '';

            const ordenId = typeof row.orden_id === 'object' ? JSON.stringify(row.orden_id) : `"${row.orden_id}"`;

            return `<button class="btn btn-sm btn-recibo"
            data-id=${ordenId}
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Recibo de embarque"
            style="
              background: transparent;
              border: 1.5px solid #0a2472;
              color: #0a2472;
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
              box-shadow: 0 2px 8px rgba(10, 36, 114, 0.1);
              letter-spacing: 0.3px;
              backdrop-filter: blur(2px);
            "
            onmouseover="this.style.background='#0a2472'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(10, 36, 114, 0.25)'; this.style.borderColor='#0a2472';"
            onmouseout="this.style.background='transparent'; this.style.color='#0a2472'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(10, 36, 114, 0.1)'; this.style.borderColor='#0a2472';">
      <i class="fa-solid fa-receipt" style="font-size: 0.95rem;"></i>
      <span class="d-none d-md-inline">Recibo de embarque</span>
    </button>`;
          }
        }
      ]
    });


    $('#dataTableOrdenEmbarque').on('click', '.btn-detalle', function() {
      let idOrden = $(this).data('id');
      abrir_modal_detalle_orden_embarque(idOrden);
    });

    $('#dataTableOrdenEmbarque').on('click', '.btn-recibo', function() {
      let idOrden = $(this).data('id');
      window.open(`funciones/recibo_embarque.php?orden_id=${idOrden}`, "_blank");
    });
  });
</script>


<div class="container-fluid">
  <div class="row mb-3 mt-3">
    <div class="col-mb-7">
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
      <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" id="dataTableOrdenEmbarque" style="width: 100%;">
        <thead>
          <tr>
            <th>Fecha solicitud</th>
            <th>Cliente</th>
            <th>Factura</th>
            <th>Estatus</th>
            <th>Detalles</th>
            <th>Recibo</th>
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
