<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
?>
<script>
  $(document).ready(function() {
    $('#dataTableTransportes').DataTable({
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
            title: 'Listado transportes',
            filename: 'Listado_transportes_excel',

            //Aquí es donde generas el botón personalizado
            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
            exportOptions: {
              columns: [0, 1]
            }
          },
          {
            //Botón para PDF
            extend: 'pdf',
            footer: true,
            title: 'Listado transportes',
            filename: 'Listado_transportes_pdf',
            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
            exportOptions: {
              columns: [0, 1]
            }
          },
          //Botón para print
          {
            extend: 'print',
            footer: true,
            title: 'Listado transportes',
            filename: 'Listado_transportes_print',
            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
            exportOptions: {
              columns: [0, 1]
            }
          }
        ]
      },
      ajax: {
        url: 'catalogos/transportes_listado.php',
        dataSrc: ''
      },
      columns: [{
          data: 'trans_id'
        },
        {
          data: 'trans_nombre'
        },
        {
          data: 'trans_estatus',
          render: function(data, type, row) {
            return data === 'A' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            const accion = row.trans_estatus === 'A' ? 'B' : 'A';
            // Aquí se generan los botones de acción
            if (row.trans_estatus === 'A') {
              return `<button class="btn btn-outline-primary btn-sm"
              onclick="abrir_modal_transportes_actualizar(${row.trans_id})" title="Editar transporte"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-outline-danger btn-sm"
                    onclick="eliminar_transportista(${row.trans_id}, '${row.trans_nombre}','${accion}')" title="Eliminar transporte"><i class="fas fa-trash-alt"></i></button>`;
            }
            if (row.trans_estatus === 'B') {
              return `
              <button class="btn btn-outline-success btn-sm"
              onclick="eliminar_transportista(${row.trans_id},'${row.trans_nombre}' ,'${accion}')" title="Activar transporte"><i class="fas fa-check"></i></button>`;
            }
            return '';
          }
        }
      ]
    });
  });
</script>

<div class="container-fluid">
  <div class="row mb-3 mt-3">
    <div class="col-md-7">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Transportes</li>
        </ol>
      </nav>
    </div>
    <?php if (fnc_permiso($_SESSION['privilegio'], 49, 'upe_agregar') == 1) { ?>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_transporte_insertar" onclick="abrir_modal_transportes()"> <i class="fa fa-plus"></i> Agregar Transporte</button>
      </div>
    <?php } ?>
  </div>
  <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
      <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableTransportes" style="width: 100%;">
        <thead>
          <tr>
            <th>Clave</th>
            <th>Nombre</th>
            <th>Editar</th>
            <th>Baja</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>



<div class="modal fade" id="modal_transportes_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_transportes_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
  function abrir_modal_transportes() {
    $.ajax({
      type: 'POST',
      url: 'catalogos/transportes_modal_insertar.php',
      success: function(result) {
        $('#modal_transportes_insertar').html(result);
        $('#modal_transportes_insertar').modal('show');
      }
    });
  }

  function abrir_modal_transportes_actualizar(id) {
    $.ajax({
      type: 'POST',
      url: 'catalogos/transportes_modal_actualizar.php',
      data: {
        'id': id
      },
      success: function(result) {
        $('#modal_transportes_actualizar').html(result);
        $('#modal_transportes_actualizar').modal('show');
      }
    });
  }

  function eliminar_transportista(id, desc, accion) {
    let dataForm = {
      "trans_id": id,
      "accion": accion
    };



    console.log(dataForm);

    Swal.fire({
      title: `${accion === 'B' ? '¿Seguro que deseas darlo de baja?' : '¿Seguro que deseas reactivarlo?'}`,
      text: `${accion === 'B' ? `Darás de baja a ${desc}` : `Reactivaras a ${desc} `}`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: 'catalogos/transportes_eliminar.php',
          data: JSON.stringify(dataForm),
          success: function(res) {
            console.log(res);
            if (res.success) {
              Swal.fire({
                title: "Dado de baja!",
                text: `${res.message}`,
                icon: "success"
              });
              $('#dataTableTransportes').DataTable().ajax.reload();
            } else {
              Swal.fire({
                title: "Ocurrio un error!",
                text: `${res.error}`,
                icon: "error"
              });
            }
          }
        });
      }
    });
  }
</script>
