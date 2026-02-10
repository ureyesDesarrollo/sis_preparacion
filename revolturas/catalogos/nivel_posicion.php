<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

if (isset($_POST['action']) && $_POST['action'] == 'cargar_rack') {
    $rack_id = isset($_POST['rack_id']) ? intval($_POST['rack_id']) : 0;

    $sql = "SELECT 
    n.*, 
    r.*, 
    d.tipo,
    d.cantidad,
    d.tar_id, 
    d.rev_id,
    t.tar_folio, t.pro_id, t.pro_id_2,
    d.rr_id, rg.rev_id AS rev_general_id, p1.pres_descrip AS pres_general,
    d.rrc_id, rc.rev_id AS rev_cliente_id, p2.pres_descrip AS pres_cliente,
    rv1.rev_folio AS rev_folio_general,
    rv2.rev_folio AS rev_folio_cliente,
    rv3.rev_folio AS rev_folio_directo,
    -- Cliente lógico según rr_id o rrc_id
    CASE 
        WHEN d.rr_id IS NOT NULL THEN 74
        ELSE rc.cte_id
    END AS cliente_id,

    CASE 
        WHEN d.rr_id IS NOT NULL THEN c2.cte_nombre
        ELSE c1.cte_nombre
    END AS cte_nombre
FROM rev_nivel_posicion n
INNER JOIN rev_racks r ON n.rac_id = r.rac_id
LEFT JOIN rev_nivel_posicion_detalle d ON d.niv_id = n.niv_id
LEFT JOIN rev_tarimas t ON t.tar_id = d.tar_id
-- Revoltura general
LEFT JOIN rev_revolturas_pt rg ON rg.rr_id = d.rr_id
LEFT JOIN rev_revolturas rv1 ON rv1.rev_id = rg.rev_id
LEFT JOIN rev_presentacion p1 ON p1.pres_id = rg.pres_id
-- Revoltura cliente
LEFT JOIN rev_revolturas_pt_cliente rc ON rc.rrc_id = d.rrc_id
LEFT JOIN rev_revolturas rv2 ON rv2.rev_id = rc.rev_id
LEFT JOIN rev_presentacion p2 ON p2.pres_id = rc.pres_id
-- Revoltura directa
LEFT JOIN rev_revolturas rv3 ON rv3.rev_id = d.rev_id
-- Cliente por cliente
LEFT JOIN rev_clientes c1 ON c1.cte_id = rc.cte_id
-- Cliente por defecto (cte_id 74)
LEFT JOIN rev_clientes c2 ON c2.cte_id = 74
WHERE n.rac_id = $rack_id
ORDER BY SUBSTRING(n.niv_codigo, 2) DESC, n.niv_codigo ASC;";

    $result = mysqli_query($cnx, $sql);

    $ubicaciones = [];
    $filas = [];
    $niveles = [];

    $contador_tarimas = 0;
    $contador_generales = 0;
    $contador_clientes = 0;
    $contador_tarimas_revolturas = 0;
    $contador_vacios = 0;

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fila = substr($row['niv_codigo'], 0, 1);
            $nivel = substr($row['niv_codigo'], 1);

            if (!in_array($fila, $filas)) $filas[] = $fila;
            if (!in_array($nivel, $niveles)) $niveles[] = $nivel;

            $ubicaciones[$nivel][$fila] = $row;

            $tipo = $row['tipo'] ?? 'vacio';

            switch ($tipo) {
                case 'tarima':
                    $contador_tarimas++;
                    break;
                case 'general':
                    $contador_generales++;
                    break;
                case 'cliente':
                    $contador_clientes++;
                    break;
                case 'revoltura':
                    $contador_tarimas_revolturas++;
                    break;
                default:
                    $contador_vacios++;
            }
        }

        $revolturas = $contador_tarimas_revolturas / 5;

        sort($filas);
        rsort($niveles);

        $rack = $ubicaciones[$nivel][$fila]['rac_descripcion'];
        $rac_id = $ubicaciones[$nivel][$fila]['rac_id'];
        $rack_color = $ubicaciones[$nivel][$fila]['rac_color'];
        $rack_zona = strtoupper($ubicaciones[$nivel][$fila]['rac_zona']);

        echo "<div class='rack-header'>";
        echo "<div class='rack-title-container'>";
        echo "<h2 class='rack-title'>Rack: $rack</h2>";
        echo "</div>";
        echo "<input value='$rac_id' id='rac_id_p' class='hidden-input'>";
        echo "<div class='rack-summary'>";
        if($rack_zona != 'EMBARQUE'){
        echo "<div class='summary-item'><span class='summary-label'>Tarimas:</span> <span class='summary-value'>$contador_tarimas</span></div>";
        echo "<div class='summary-item'><span class='summary-label'>Tarimas por revoltura:</span> <span class='summary-value'>$contador_tarimas_revolturas</span></div>";
        echo "<div class='summary-item'><span class='summary-label'>Revolturas:</span> <span class='summary-value'>$revolturas</span></div>";
        echo "<div class='summary-item'><span class='summary-label'>Vacías:</span> <span class='summary-value'>$contador_vacios</span></div>";
        }
        echo "</div>";
        echo "</div>";

        echo "<div class='rack-grid-container'>";
        echo "<div class='rack-grid' style='grid-template-columns: repeat(" . count($filas) . ", minmax(160px, 1fr));'>";

        foreach ($niveles as $nivel) {
            foreach ($filas as $fila) {
                $codigo = "{$fila}{$nivel}";
                $row = $ubicaciones[$nivel][$fila];

                $ocupado = $row['niv_ocupado'] ?? 0;
                $cliente_asignado_id = $row['cte_id'] ?? null;
                $cliente_id = $row['cliente_id'] ?? null;
                $cliente_nombre = $row['cte_nombre'] ?? "Sin Cliente";
                $tipo = $row['tipo'];
                $tar_id = $row['tar_id'];
                $niv_id = $row['niv_id'];
                $tipo_texto = "";
                $tarima = "";
                $badge_class = "";

                switch ($tipo) {
                    case 'tarima':
                        $pro_id_2_part = $row['pro_id_2'] ? "/{$row['pro_id_2']}" : '';
                        $tipo_texto = "TARIMA";
                        $tarima = "P{$row['pro_id']}{$pro_id_2_part}T{$row['tar_folio']}";
                        $badge_class = "badge-tarima";
                        $contador_tarimas++;
                        break;
                    case 'general':
                        $folio = $row['rev_folio_general'] ?? '';
                        $tipo_texto = "REVOL. GENERAL";
                        $tarima = "{$row['pres_general']} ({$row['cantidad']} Kg) - Rev: $folio";
                        $badge_class = "badge-general";
                        $contador_generales++;
                        break;
                    case 'cliente':
                        $folio = $row['rev_folio_cliente'] ?? '';
                        $tipo_texto = "REVOL. CLIENTE";
                        $tarima = "{$row['pres_cliente']} ({$row['cantidad']} Kg) - Rev: $folio";
                        $badge_class = "badge-cliente";
                        $contador_clientes++;
                        break;
                    case 'revoltura':
                        $folio = $row['rev_folio_directo'] ?? '';
                        $tipo_texto = "REVOLTURA";
                        $tarima = "FOLIO $folio";
                        $badge_class = "badge-revoltura";
                        $contador_tarimas_revolturas++;
                        break;
                    default:
                        $tipo_texto = "DISPONIBLE";
                        $tarima = "Espacio libre";
                        $badge_class = "badge-disponible";
                        $contador_vacios++;
                }

                $clase = $ocupado ? 'ocupado' : ($cliente_asignado_id ? 'asignada' : 'disponible');

                echo "<div class='rack-cell $clase' 
                    data-tarima='$tar_id' 
                    data-codigo='$codigo' 
                    data-niv='$niv_id'>
                    <div class='cell-header'>
                        <span class='cell-badge $badge_class'>$tipo_texto - $niv_id</span>
                        <span class='cell-code'>$codigo</span>
                    </div>
                    <div class='cell-content'>
                        <div class='cell-details'>$tarima</div>
                        " . ($cliente_id ? "<div class='cell-client'>Cliente: $cliente_nombre</div>" : "") . "
                    </div>
                </div>";
            }
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='empty-rack-message'>";
        echo "<p>Rack sin posiciones registradas.</p>";
        echo "</div>";
    }
    exit();
}
?>


<style>
    .rack-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 98%;
        margin: 20px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .rack-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }

    .rack-title-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .rack-title {
        color: #2c3e50;
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
    }

    .rack-color {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #ddd;
    }

    .hidden-input {
        display: none;
    }

    .rack-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-left: auto;
    }

    .summary-item {
        display: flex;
        align-items: center;
        background-color: #f1f3f5;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.95rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .summary-label {
        color: #495057;
        margin-right: 8px;
        font-weight: 500;
    }

    .summary-value {
        font-weight: bold;
        color: #212529;
        font-size: 1.1rem;
    }

    .rack-grid-container {
        overflow-x: auto;
        padding-bottom: 15px;
    }

    .rack-grid {
        display: grid;
        gap: 18px;
        padding: 5px;
    }

    .rack-cell {
        position: relative;
        border-radius: 10px;
        padding: 15px;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
        background-color: white;
        border: 1px solid #e0e0e0;
    }

    .cell-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #e0e0e0;
    }

    .cell-badge {
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-tarima {
        background-color: #d0f0fd;
        color: #0b5ed7;
    }

    .badge-general {
        background-color: #ffe7d9;
        color: #d63900;
    }

    .badge-cliente {
        background-color: #e6f7e6;
        color: #0d6e0d;
    }

    .badge-revoltura {
        background-color: #f0e6ff;
        color: #6f42c1;
    }

    .badge-disponible {
        background-color: #f0f0f0;
        color: #495057;
    }

    .cell-code {
        font-weight: bold;
        font-size: 1.1rem;
        color: #343a40;
    }

    .cell-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .cell-details {
        font-size: 0.9rem;
        color: #495057;
        line-height: 1.4;
        margin-bottom: 8px;
        word-break: break-word;
    }

    .cell-client {
        font-size: 0.8rem;
        color: #6c757d;
        font-style: italic;
        margin-top: auto;
        padding-top: 8px;
        border-top: 1px dashed #e0e0e0;
    }

    /* Estados de las celdas */
    .rack-cell.ocupado {
        border-left: 5px solid #ff6b6b;
    }

    .rack-cell.asignada {
        border-left: 5px solid #ffd43b;
    }

    .rack-cell.disponible {
        border-left: 5px solid #40c057;
    }

    .rack-cell.seleccionada {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        border: 1px solid #339af0;
    }

    .rack-cell:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .empty-rack-message {
        text-align: center;
        padding: 50px;
        background-color: #f8f9fa;
        border-radius: 10px;
        color: #6c757d;
        font-size: 1.2rem;
        border: 1px dashed #ced4da;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .rack-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .rack-summary {
            margin-left: 0;
            width: 100%;
        }

        .rack-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)) !important;
        }
    }

    @media (max-width: 768px) {
        .rack-container {
            padding: 15px;
        }

        .rack-title {
            font-size: 1.5rem;
        }

        .rack-cell {
            min-height: 140px;
            padding: 12px;
        }

        .cell-code {
            font-size: 1rem;
        }

        .cell-details {
            font-size: 0.85rem;
        }
    }
</style>
<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Vista de Racks</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-3">
            <label for="rac_zona" class="form-label">Zona del Rack</label>
            <select name="rac_zona" id="rac_zona_sel_r" class="form-select" required>
                <option value="">Seleccione</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="rac_id_r" class="form-label">Rack</label>
            <select name="rac_id" id="rac_id_r" class="form-select" required>
                <option value="">Seleccione</option>
            </select>
        </div>
    </div>
    <div id="alert-message" class="alert alert-dismissible fade" role="alert">
        <span id="alert-text"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="botones">
        <button id="btn-entrada" class="btn btn-success">Asignar Tarima (Entrada)</button>
        <button id="btn-salida" class="btn btn-warning">Mover Tarima (Salida)</button>
        <button id="btn-asignar-cliente" class="btn btn-primary">Asignar posición a cliente</button>
        <button id="btn-seleccionar-cliente" class="btn btn-info d-none">Seleccionar Cliente</button>
    </div>
    <div id="rack-content" class="rack-container">

    </div>
    <div class="botones">
        <button id="btn-seleccionar" class="d-none" disabled>Seleccionar Tarima</button>
        <button id="btn-mover" class="d-none" disabled>Mover a Nueva Posición</button>
    </div>



</div>

<div class="modal fade" id="modalEntrada" tabindex="-1" aria-labelledby="modalEntradaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEntradaLabel">Asignar Tarima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEntrada">
                    <div class="mb-3">
                        <label for="tarimaId" class="form-label">Tarima</label>
                        <select name="tar_id_a" id="tar_id_a" class="form-select"></select>
                    </div>
                </form>

                <div id="estatus" style="font-size: 40px;">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarEntrada">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAsignarCliente" tabindex="-1" aria-labelledby="modalAsignarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsignarClienteLabel">Seleccionar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAsignarCliente">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <input type="text" id="search_clientes_a" class="form-control" placeholder="Buscar cliente">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <select name="cte_id_a" id="cte_id_a" class="form-select" required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarAsignacion">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let niv_id = null; //Id de la posición
        let tarimaSeleccionada = null; // Almacena la tarima seleccionada
        let nuevaPosicion = null; // Almacena la nueva posición seleccionada
        let modoEntrada = null; //Modo de entrada (asignar tarima)
        let modoSalida = null; //Modo salida (mover/eliminar tarima)
        let modoAsignarCliente = null; // Modo de asignar cliente a posicion
        let posicionesSeleccionadas = [];
        let arrayClientes = [];

        cargarRacksZonas();

        $('#rac_zona_sel_r').on('change', function(e) {
            $("#rack-content").html("");
            cargarRacks($(this).val());
        });

        $("#rac_id_r").on("change", function() {
            const rackId = $(this).val();
            resetSeleccion();
            if (rackId > 0) {
                cargarRack(rackId);
            } else {
                $("#rack-content").html("");
            }
        });

        $(document).off().on("click", ".rack-cell.ocupado", function() {
            if (!tarimaSeleccionada) {
                tarimaSeleccionada = $(this).data("tarima");
                niv_id = $(this).data('niv');
                $(this).addClass("seleccionada");
                resaltarPosicionesDisponibles();
            }
        });

        // Evento para seleccionar una nueva posición
        $(document).off("click", ".rack-cell.disponible").on("click", ".rack-cell.disponible", function() {
            if (tarimaSeleccionada) {
                nuevaPosicion = $(this).data("codigo");
                $(this).addClass("seleccionada");
                moverTarima();
            }
        });

        // Evento para activar el modo de entrada
        $("#btn-entrada").on("click", function() {
            modoEntrada = true;
            modoSalida = false;
            $("#btn-entrada").prop("disabled", true);
            $("#btn-salida").prop("disabled", false);
        });

        $(document).on("click", ".rack-cell", function() {
            if (modoEntrada) {
                if (!$(this).hasClass("ocupado")) {
                    nuevaPosicion = $(this).data("codigo");
                    tarimaSeleccionada = $(this).data("tarima");
                    niv_id = $(this).data('niv');
                    $('#rack-content').find('.seleccionada').removeClass('seleccionada');
                    $(this).addClass("seleccionada");
                    $('#modalEntrada').modal('show');
                    cargarTarimas();
                }
            } else if (modoSalida) {
                // Modo de salida: seleccionar una tarima ocupada
                if ($(this).hasClass("ocupado")) {
                    tarimaSeleccionada = $(this).data("tarima");
                }
            }
        });

        $("#btn-asignar-cliente").on("click", function() {
            modoAsignarCliente = true;
            $("#btn-asignar-cliente").prop("disabled", true);
            $("#btn-salida").prop("disabled", true); // Deshabilitar otros modos
            $("#btn-entrada").prop("disabled", true);
            mostrarPosicionesDisponiblesParaCliente(); // Mostrar solo las posiciones disponibles
        });

        $(document).on("click", ".rack-cell", function() {
            if (modoAsignarCliente) {
                const seleccionada = $(this); // Obtener la celda seleccionada
                const niv_id = seleccionada.data("niv"); // Obtener el id de la posición

                // Verificar si la celda tiene la clase 'disponible' o 'seleccionada'
                if (seleccionada.hasClass("disponible")) {
                    // Marcar la celda como seleccionada
                    seleccionada.removeClass("disponible");
                    seleccionada.addClass("seleccionada");

                    // Agregar la posición al array
                    posicionesSeleccionadas.push(niv_id);
                } else if (seleccionada.hasClass("seleccionada")) {
                    // Si la celda ya está seleccionada, desmarcarla
                    seleccionada.removeClass("seleccionada");
                    seleccionada.addClass("disponible");

                    // Eliminar la posición del array
                    posicionesSeleccionadas = posicionesSeleccionadas.filter(id => id !== niv_id);
                }

                if (posicionesSeleccionadas.length > 0) {
                    $("#btn-seleccionar-cliente").removeClass('d-none');
                } else {
                    $("#btn-seleccionar-cliente").addClass('d-none');
                }

                console.log(posicionesSeleccionadas);
            }
        });

        function mostrarPosicionesDisponiblesParaCliente() {
            $(".rack-cell").removeClass("disponible"); // Eliminar las celdas disponibles de la vista anterior
            $(".rack-cell:not(.ocupado)").addClass("disponible"); // Solo las celdas vacías
        }

        $('#tar_id_a').on('change', function(e) {
            let tar_id = $(this).val();
            console.log(tar_id);
            validarEstatusTarima($(this).val());
        });

        // Evento para confirmar la entrada de una tarima
        $("#btnConfirmarEntrada").on("click", function() {
            const tarimaId = $("#tar_id_a").val();
            if (tarimaId) {
                console.log($('#estatus').hasClass('text-success'));
                if ($('#estatus').hasClass('text-success')) {
                    asignarTarima(tarimaId);
                    $("#modalEntrada").modal("hide");
                    resetSeleccion();
                } else {
                    Swal.fire({
                        title: '⚠️ Tarima en cuarentena',
                        text: 'La tarima seleccionada está en cuarentena. ¿Seguro que va en este rack?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'No, cancelar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Confirmado',
                                text: 'La tarima se asignará al rack.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            asignarTarima(tarimaId);
                            $("#modalEntrada").modal("hide");
                            resetSeleccion();
                        } else {
                            Swal.fire({
                                title: 'Cancelado',
                                text: 'La acción ha sido cancelada.',
                                icon: 'info',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }

            } else {
                alert("Por favor, ingrese el ID de la tarima.");
            }
        });

        $('#modalEntrada').on('hidden.bs.modal', function() {
            resetSeleccion();
            $('#estatus').html('').removeClass('text-warning text-danger text-success');
        });

        function cargarRacksZonas() {
            $.ajax({
                type: 'GET',
                url: 'catalogos/racks_listado.php',
                success: function(data) {
                    let racks = JSON.parse(data);
                    let zonasSet = new Set();
                    let options = '';

                    racks.forEach(function(rack) {
                        if (rack.rac_estatus === 'A') {
                            zonasSet.add(rack.rac_zona);
                        }
                    });
                    zonasSet.forEach(function(zona) {
                        options += `<option value="${zona}">${zona}</option>`;
                    });

                    $('#rac_zona_sel_r').append(options);
                },
                error: function() {
                    alert('Error al cargar los racks.');
                }
            });
        }

        function cargarRacks(rac_zona) {
            $.ajax({
                type: 'GET',
                url: 'catalogos/racks_listado.php',
                success: function(data) {
                    let racks = JSON.parse(data);
                    let options = '<option value="">Seleccione</option>';
                    racks.forEach(function(rack) {
                        if (rack.rac_estatus === 'A' && rack.rac_zona === rac_zona) {
                            options += `<option value="${rack.rac_id}">${rack.rac_descripcion}</option>`;
                        }
                    });
                    $('#rac_id_r').empty().append(options);
                },
                error: function() {
                    alert('Error al cargar los racks.');
                }
            });
        }

        function cargarRack(rackId) {
            $.ajax({
                url: "catalogos/nivel_posicion.php",
                method: "POST",
                data: {
                    rack_id: rackId,
                    action: 'cargar_rack'
                },
                success: function(html) {
                    $("#rack-content").html(html);
                    $("#btn-seleccionar").prop("disabled", false);
                },
                error: function(error) {
                    console.error("Error al cargar el rack:", error);
                }
            });
        }

        // Función para resaltar las posiciones disponibles
        function resaltarPosicionesDisponibles() {
            $(".rack-cell").removeClass("disponible");
            $(".rack-cell:not(.ocupado)").addClass("disponible");
        }

        // Función para mover la tarima
        function moverTarima() {
            console.log({
                tarima_id: tarimaSeleccionada,
                nueva_posicion: nuevaPosicion,
                niv_id: niv_id,
                rac_id: $('#rac_id_p').val()
            });
            if (tarimaSeleccionada && nuevaPosicion) {
                $.ajax({
                    url: "catalogos/mover_tarima.php",
                    method: "POST",
                    data: {
                        tarima_id: tarimaSeleccionada,
                        nueva_posicion: nuevaPosicion,
                        niv_id: niv_id,
                        rac_id: $('#rac_id_p').val()
                    },
                    success: function(response) {
                        if (response === "success") {
                            const rackId = $("#rac_id_r").val();
                            cargarRack(rackId); // Recargar el rack
                            mostrarAlerta("Tarima movida correctamente.", "success");
                        } else if (response === "ocupado") {
                            mostrarAlerta("Error: La posición seleccionada está ocupada.", "danger");
                        } else {
                            mostrarAlerta("Hubo un error al mover la tarima.", "danger");
                        }
                        resetSeleccion();
                    },
                    error: function(error) {
                        console.error("Error al mover la tarima:", error);
                        mostrarAlerta("Hubo un error al mover la tarima.", "danger");
                    }
                });
            }
        }

        // Función para resetear la selección
        function resetSeleccion() {
            modoAsignarCliente = false;
            posicionesSeleccionadas = []; // Limpiar las posiciones seleccionadas
            niv_id = null;
            $(".rack-cell").removeClass("seleccionada disponible"); // Limpiar las selecciones en el frontend
            $("#btn-asignar-cliente").prop("disabled", false); // Habilitar el botón de asignar cliente nuevamente
            $("#btn-salida").prop("disabled", false);
            $("#btn-entrada").prop("disabled", false);
            $("#btn-seleccionar-cliente").addClass('d-none');
        }

        function mostrarAlerta(mensaje, tipo) {
            const alert = $("#alert-message");
            alert.removeClass("alert-success alert-danger").addClass(`alert-${tipo}`);
            $("#alert-text").text(mensaje);
            alert.addClass("show");
            setTimeout(() => {
                alert.removeClass("show");
            }, 3000); // Ocultar la alerta después de 3 segundos
        }

        function asignarTarima(tarimaId) {
            if (nuevaPosicion && tarimaId) {
                $.ajax({
                    url: "catalogos/asignar_tarima.php",
                    method: "POST",
                    data: {
                        tarima_id: tarimaId,
                        nueva_posicion: nuevaPosicion,
                        rac_id: $('#rac_id_p').val()
                    },
                    success: function(response) {
                        if (response === "success") {
                            mostrarAlerta("Tarima asignada correctamente.", "success");
                            const rackId = $("#rac_id_r").val();
                            cargarRack(rackId);
                        } else {
                            mostrarAlerta("Hubo un error al asignar la tarima.", "danger");
                        }
                        resetSeleccion();
                    },
                    error: function(error) {
                        console.error("Error al asignar la tarima:", error);
                        mostrarAlerta("Hubo un error al asignar la tarima.", "danger");
                    }
                });
            }
        }

        $('#search_clientes_a').on('input', function() {
            const inputValue = $(this).val().toLowerCase();
            if (inputValue.length > 0) {

                const filteredClientes = arrayClientes.filter(cliente =>
                    cliente.cte_nombre.toLowerCase().includes(inputValue)
                );

                // Actualiza el select con los clientes filtrados
                const select = $('#cte_id_a');
                select.empty(); // Limpia las opciones actuales
                if (filteredClientes.length > 0) {
                    filteredClientes.forEach(cliente => {
                        select.append(`<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`);
                    });
                } else {
                    select.append('<option value="">No se encontraron resultados</option>');
                }
            } else {
                actualizarListadoClientes('');
            }
        });


        function cargarTarimas() {
            $.ajax({
                type: 'GET',
                url: 'funciones/tarimas_listado.php',
                success: function(data) {
                    let tarimas = JSON.parse(data);
                    let options = '<option value="">Seleccione tarima</option>';

                    tarimas.forEach(function(tar) {
                        if (tar.tar_estatus === '0' &&
                            tar.tar_count_etiquetado > 0 &&
                            tar.niv_id === null &&
                            tar.pro_id !== '1' &&
                            tar.pro_id !== '2') {
                            let pro_id = '';

                            if (tar.pro_id_2) {
                                pro_id = `${tar.pro_id}/${tar.pro_id_2}`;
                            } else if (tar.pro_id === '1') {
                                pro_id = 'FINOS';
                            } else {
                                pro_id = tar.pro_id;
                            }

                            // Determinar si es Cuarentena o Rechazada
                            let estado = '';
                            if (tar.tar_rechazado == 'C') {
                                estado = ' - Cuarentena';
                            } else if (tar.tar_rechazado == 'R') {
                                estado = ' - Rechazada';
                            }

                            // Agregar solo una vez la opción
                            options += `<option value="${tar.tar_id}">P${pro_id}T${tar.tar_folio}${estado}</option>`;
                        }
                    });

                    $('#tar_id_a').empty().append(options);
                },
                error: function() {
                    alert('Error al cargar las tarimas.');
                }
            });
        }


        function validarEstatusTarima(tar_id) {
            $.ajax({
                type: 'POST',
                url: 'app/consultar_estatus_tarima.php',
                contentType: 'application/json',
                data: JSON.stringify({
                    tar_id: tar_id
                }), // Enviar como JSON
                success: function(response) {
                    $('#estatus').removeClass('text-warning text-success text-danger');
                    if (response.status === 'success') {
                        $('#estatus').html(response.data.estatus);
                        if (response.data.estatus === 'En proceso') {
                            $('#estatus').addClass('text-warning');
                            $('#btnConfirmarEntrada').removeClass('d-none');
                        } else if (response.data.estatus === 'Rechazada') {
                            $('#estatus').addClass('text-danger');
                            $('#btnConfirmarEntrada').removeClass('d-none');
                        } else if (response.data.estatus === 'Aceptada') {
                            $('#estatus').addClass('text-success');
                            $('#btnConfirmarEntrada').removeClass('d-none');
                        } else if (response.data.estatus === 'Cuarentena') {
                            $('#estatus').addClass('text-danger');
                            $('#btnConfirmarEntrada').removeClass('d-none');
                        }

                    } else {
                        console.error('Error:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                }
            });
        }

        $("#btn-seleccionar-cliente").on("click", function() {
            abrir_modal_seleccionar_cliente();
        });

        function abrir_modal_seleccionar_cliente() {
            $('#modalAsignarCliente').modal('show');
            obtenerClientes();
        }

        function obtenerClientes() {
            $.ajax({
                type: 'GET',
                url: 'catalogos/clientes_listado.php',
                success: function(data) {
                    let clientes = JSON.parse(data);

                    clientes.forEach(function(cte) {
                        if (cte.cte_estatus === 'A') {
                            arrayClientes.push({
                                cte_id: cte.cte_id,
                                cte_nombre: cte.cte_nombre
                            });
                        }
                    });
                    actualizarListadoClientes('');
                },
                error: function() {
                    alert('Error al cargar los clientes.');
                }
            });
        }

        function actualizarListadoClientes(filtro) {
            let opciones = '<option value="">Seleccione un cliente</option>';

            if (filtro.length > 0) {
                arrayClientes.filter(cliente => cliente.cte_nombre.toLowerCase().includes(filtro))
                    .forEach(cliente => {
                        opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                    });
            } else {
                // Si no hay filtro, muestra todos los clientes
                arrayClientes.forEach(cliente => {
                    opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                });
            }

            $('#cte_id_a').html(opciones);
        }

        function enviarPosiciones() {
            if (posicionesSeleccionadas.length === 0) {
                alert("Por favor, selecciona al menos una posición.");
                return;
            }

            $.ajax({
                url: 'catalogos/nivel_posicion_asignar_cliente.php',
                type: 'POST',
                data: {
                    niv_id: posicionesSeleccionadas,
                    cte_id: $('#cte_id_a').val()
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        // Limpia el array de posiciones seleccionadas
                        posicionesSeleccionadas = [];
                        $('#modalAsignarCliente').modal('hide');
                        mostrarAlerta("Posiciones asignadas correctamente.", "success");
                        resetSeleccion();
                    } else if (data.status === 'warning') {
                        $('#modalAsignarCliente').modal('hide');
                        mostrarAlerta(`Atención: ${data.message}`, "warning");
                    } else {
                        $('#modalAsignarCliente').modal('hide');
                        mostrarAlerta(`Error: ${data.message}`, "error");
                    }
                },
                error: function() {
                    alert("Error al comunicarse con el servidor.");
                }
            });
        }

        $('#btnConfirmarAsignacion').on('click', function(e) {
            e.preventDefault();
            enviarPosiciones();
        });
    });
</script>