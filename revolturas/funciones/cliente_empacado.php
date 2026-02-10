<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx =  Conectarse();

if (isset($_POST['action']) && $_POST['action'] == 'obtener_datos') {
    try {
        $cte_id = $_POST['cte_id'];
        $listado_empacado_cliente = mysqli_query($cnx, "SELECT rrc.rrc_ext_inicial as rr_ext_inicial,rrc.rrc_id, 
        rrc.rrc_ext_real as rr_ext_real,pres.pres_descrip,rev.rev_folio as revoltura FROM rev_revolturas_pt_cliente rrc
        INNER JOIN rev_presentacion pres ON pres.pres_id = rrc.pres_id
        INNER JOIN rev_revolturas rev ON rev.rev_id = rrc.rev_id
        WHERE rrc.cte_id = '$cte_id' AND rrc.rrc_ext_real != 0 AND rev.rev_count_etiquetado > 0");

        $datos = array();

        while ($fila = mysqli_fetch_assoc($listado_empacado_cliente)) {
            $datos[] = $fila;
        }

        $json_empacado = json_encode($datos);

        echo $json_empacado;
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
    exit();
}

?>

<script>
    $(document).ready(function() {
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
        $('#dataTableEmpaquesClientes').DataTable({
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
                        title: 'Listado Cliente Empacado',
                        filename: 'Listado_cliente_empacado_excel',

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
                        title: 'Listado Cliente Empacado',
                        filename: 'Listado_cliente_empacado_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Cliente Empacado',
                        filename: 'Listado_cliente_empacado_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/cliente_empacado_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'cte_nombre'
                },
                {
                    data: 'total_presentaciones'
                },
                {
                    data: 'total_empaques',

                },
                {
                    data: 'total_kilos',
                    render(data) {
                        return formatter.format(data);
                    }
                },
                {
                    data: null,
                    render: function(row) {
                        return `<button class="btn btn-primary btn-facturar-cliente" data-emp='${row.cte_id}'>Crear orden</button>`;
                    }
                }
            ]

        });

        $('#dataTableEmpaquesClientes').on('click', '.btn-facturar-cliente', function() {
            let empData = $(this).data('emp'); // Obtiene el objeto completo de datos del botón
            agregarEmpaque(empData);
            localStorage.setItem('cliente_id', empData);
            setTimeout(() => {
                abrir_modal_facturas();
            }, 100);
        });

        function abrir_modal_facturas() {
            $.ajax({
                type: 'POST',
                url: 'funciones/orden_embarque_modal.php',
                success: function(result) {
                    $('#modal_facturas').html(result);
                    $('#modal_facturas').modal('show');
                }
            });
        }

        function agregarEmpaque(empData) {
            // Recupera los datos del Local Storage o inicializa un arreglo vacío
            let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

            $.ajax({
                url: 'funciones/cliente_empacado.php',
                type: 'POST',
                data: {
                    cte_id: empData,
                    action: 'obtener_datos'
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    console.log(response); // Verifica la estructura de la respuesta

                    // Itera sobre cada objeto dentro de la respuesta
                    response.forEach(empaque => {
                        // Construye el objeto con los datos del empaque
                        const empaqueConDatos = {
                            revoltura: empaque.revoltura,
                            rev_id: empaque.rev_id,
                            rrc_id: empaque.rrc_id,
                            pres_descrip: empaque.pres_descrip,
                            rr_ext_inicial: empaque.rr_ext_inicial,
                            rr_ext_real: empaque.rr_ext_real,
                            pres_kg: empaque.pres_kg
                        };

                        // Agrega el empaque al arreglo de empaques
                        empaquesArray.push(empaqueConDatos);
                    });

                    // Guarda el arreglo actualizado en el Local Storage
                    localStorage.setItem('empaques', JSON.stringify(empaquesArray));

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Error al crear orden: ${textStatus}, ${errorThrown}`,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }

    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        Clientes empacado
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" id="dataTableEmpaquesClientes" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Presentaciones totales</th>
                        <th>Empaques totales</th>
                        <th>Kilos totales real</th>
                        <th>Generar orden</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>

                </tfoot>

            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_facturas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
    $('#modal_facturas').on('hidden.bs.modal', function() {
        localStorage.clear();
    });
</script>