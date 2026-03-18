<?php
include '../../conexion/conexion.php';
$conexion = Conectarse();

$ordenId = $_POST['oe_id'] ?? 0;

if ($ordenId <= 0) {
  echo '<div class="modal-dialog"><div class="modal-content p-3">Orden inválida</div></div>';
  exit;
}

/* ==============================
   1. DATOS GENERALES
============================== */
$stmt = $conexion->prepare("
    SELECT oe.oe_id, oe.oe_fecha, cte.cte_nombre, oe.oe_estado
    FROM rev_orden_embarque oe
    LEFT JOIN rev_clientes cte ON cte.cte_id = oe.cte_id
    WHERE oe.oe_id = ?
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$orden = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$orden) {
  echo '<div class="modal-dialog"><div class="modal-content p-3">Orden no encontrada</div></div>';
  exit;
}

/* ==============================
   2. DETALLE
============================== */
$stmt = $conexion->prepare("
    SELECT
        oed.oed_id,
        oed.cantidad,
        CASE
            WHEN oed.oed_tipo_producto = 'EXTERNO' THEN pe.pe_lote
            ELSE COALESCE(rev.rev_folio, rrc_rev.rev_folio)
        END AS rev_folio,
        COALESCE(rr_pres.pres_descrip, rrc_pres.pres_descrip, pe_pres.pres_descrip) AS presentacion
    FROM rev_orden_embarque_detalle oed
    LEFT JOIN rev_revolturas_pt rr ON rr.rr_id = oed.rr_id
    LEFT JOIN rev_revolturas rev ON rev.rev_id = rr.rev_id
    LEFT JOIN rev_presentacion rr_pres ON rr_pres.pres_id = rr.pres_id

    LEFT JOIN rev_revolturas_pt_cliente rrc ON rrc.rrc_id = oed.rrc_id
    LEFT JOIN rev_revolturas rrc_rev ON rrc_rev.rev_id = rrc.rev_id
    LEFT JOIN rev_presentacion rrc_pres ON rrc_pres.pres_id = rrc.pres_id

    LEFT JOIN producto_externo pe ON pe.pe_id = oed.pe_id
    LEFT JOIN rev_presentacion pe_pres ON pe_pres.pres_id = pe.pres_id

    WHERE oed.oe_id = ?
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$detalle = $stmt->get_result();
$stmt->close();

/* ==============================
   3. EVIDENCIAS
============================== */
$stmt = $conexion->prepare("
    SELECT evidencia_id, nombre_original
    FROM embarque_evidencias
    WHERE embarque_id = ?
    ORDER BY fecha_creacion DESC
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$evidencias = $stmt->get_result();
$stmt->close();

// Función para determinar el color del badge según el estado
function getEstadoBadge($estado)
{
  $badges = [
    'PENDIENTE' => '<span class="badge bg-warning bg-gradient text-dark px-3 py-2 rounded-pill"><i class="fa-regular fa-clock me-1"></i>Pendiente</span>',
    'PROCESO' => '<span class="badge bg-info bg-gradient text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-rotate me-1 fa-spin"></i>Proceso</span>',
    'EN PROCESO' => '<span class="badge bg-info bg-gradient text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-rotate me-1 fa-spin"></i>En Proceso</span>',
    'COMPLETADO' => '<span class="badge bg-success bg-gradient px-3 py-2 rounded-pill"><i class="fa-regular fa-circle-check me-1"></i>Completado</span>',
    'COMPLETADA' => '<span class="badge bg-success bg-gradient px-3 py-2 rounded-pill"><i class="fa-regular fa-circle-check me-1"></i>Completada</span>',
    'CANCELADO' => '<span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill"><i class="fa-regular fa-circle-xmark me-1"></i>Cancelado</span>',
    'CANCELADA' => '<span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill"><i class="fa-regular fa-circle-xmark me-1"></i>Cancelada</span>',
    'ETIQUETA LIBERADA' => '<span class="badge bg-primary bg-gradient px-3 py-2 rounded-pill"><i class="fa-solid fa-tag me-1"></i>Etiqueta Liberada</span>',
    'LIBERADO' => '<span class="badge bg-success bg-gradient px-3 py-2 rounded-pill"><i class="fa-regular fa-circle-check me-1"></i>Liberado</span>',
    'FACTURADA' => '<span class="badge bg-success bg-gradient px-3 py-2 rounded-pill"><i class="fa-solid fa-file-invoice me-1"></i>Facturada</span>'
  ];

  return $badges[$estado] ?? '<span class="badge bg-secondary bg-gradient px-3 py-2 rounded-pill"><i class="fa-regular fa-question-circle me-1"></i>' . $estado . '</span>';
}
?>

<div class="modal-dialog modal-xl modal-dialog-scrollable">
  <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">

    <!-- HEADER CLARO -->
    <div class="modal-header" style="background: white; border-bottom: 2px solid #f0f4f8; padding: 1.5rem;">
      <div class="d-flex align-items-center gap-4 w-100">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-circle p-3" style="background: #f8fafc; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-ship" style="font-size: 1.5rem; color: #0a2472;"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold mb-1" style="font-size: 1.5rem; color: #1e293b;">
              Orden de Embarque #<?= $orden['oe_id'] ?>
            </h5>
          </div>
        </div>

        <!-- INFO COMPACTA EN UNA SOLA LÍNEA -->
        <div class="d-flex align-items-center gap-4 ms-auto">
          <!-- FECHA -->
          <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background: #f8fafc;">
            <i class="fa-regular fa-calendar text-primary" style="color: #0a2472;"></i>
            <span class="fw-semibold" style="color: #1e293b;"><?= date('d/m/Y', strtotime($orden['oe_fecha'])) ?></span>
          </div>

          <!-- CLIENTE -->
          <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background: #f8fafc;">
            <i class="fa-regular fa-building text-primary" style="color: #0a2472;"></i>
            <span class="fw-semibold" style="color: #1e293b;"><?= $orden['cte_nombre'] ?></span>
          </div>

          <!-- ESTADO -->
          <?= getEstadoBadge($orden['oe_estado']) ?>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <div class="modal-body" style="padding: 2rem; background-color: #f8fafc;">

      <!-- CARD DE RESUMEN -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-circle p-3" style="background: #f0f7ff;">
                <i class="fa-solid fa-box" style="font-size: 1.5rem; color: #0a2472;"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold">Total Productos</small>
                <h3 class="mb-0 fw-bold" style="color: #1e293b;"><?= $detalle->num_rows ?></h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-circle p-3" style="background: #f0f7ff;">
                <i class="fa-solid fa-camera" style="font-size: 1.5rem; color: #0a2472;"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold">Evidencias</small>
                <h3 class="mb-0 fw-bold" style="color: #1e293b;"><?= $evidencias->num_rows ?></h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-circle p-3" style="background: #f0f7ff;">
                <i class="fa-solid fa-cubes" style="font-size: 1.5rem; color: #0a2472;"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold">Total Unidades</small>
                <h3 class="mb-0 fw-bold" style="color: #1e293b;">
                  <?php
                  $detalle->data_seek(0);
                  $total = 0;
                  while ($row = $detalle->fetch_assoc()) {
                    $total += $row['cantidad'];
                  }
                  $detalle->data_seek(0);
                  echo number_format($total);
                  ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECCIÓN DETALLE MEJORADA (SIN TIPO) -->
      <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white py-3" style="border-bottom: 2px solid #f0f4f8;">
          <h6 class="mb-0 fw-bold" style="color: #0a2472;">
            <i class="fa-solid fa-clipboard-list me-2"></i>Detalle de Productos
          </h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0" style="border-collapse: separate; border-spacing: 0;">
              <thead style="background-color: #f8fafc;">
                <tr>
                  <th class="py-3 px-4" style="font-weight: 600; color: #4a5568; border-bottom: 2px solid #e2e8f0;">Folio</th>
                  <th class="py-3 text-center" style="font-weight: 600; color: #4a5568; border-bottom: 2px solid #e2e8f0;">Cantidad</th>
                  <th class="py-3 px-4" style="font-weight: 600; color: #4a5568; border-bottom: 2px solid #e2e8f0;">Presentación</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $detalle->fetch_assoc()): ?>
                  <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor=''">
                    <td class="py-3 px-4 fw-bold" style="color: #0a2472;"><?= $row['rev_folio'] ?></td>
                    <td class="py-3 text-center">
                      <span class="badge bg-light text-dark py-2 px-3 rounded-pill" style="background: #f1f5f9 !important;">
                        <?= number_format($row['cantidad']) ?>
                      </span>
                    </td>
                    <td class="py-3 px-4"><?= $row['presentacion'] ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- SECCIÓN EVIDENCIAS MEJORADA -->
      <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #f0f4f8;">
          <h6 class="mb-0 fw-bold" style="color: #0a2472;">
            <i class="fa-solid fa-images me-2"></i>Evidencias Fotográficas
          </h6>
          <?php if ($evidencias->num_rows > 0): ?>
            <span class="badge rounded-pill px-3 py-2" style="background: #f0f7ff; color: #0a2472;"><?= $evidencias->num_rows ?> archivos</span>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <?php if ($evidencias->num_rows == 0): ?>
            <div class="alert d-flex align-items-center gap-3 py-4" style="border: none; border-radius: 12px; background-color: #f1f5f9;">
              <i class="fa-regular fa-image fa-2x" style="color: #0a2472;"></i>
              <div>
                <h6 class="mb-1 fw-bold" style="color: #1e293b;">Sin evidencias</h6>
                <p class="mb-0 text-muted small">No se han subido fotografías para esta orden</p>
              </div>
            </div>
          <?php else: ?>
            <div class="row g-3">
              <?php while ($ev = $evidencias->fetch_assoc()): ?>
                <div class="col-md-3 col-6">
                  <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: all 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(10,36,114,0.1)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                    <div style="position: relative; padding-top: 100%; overflow: hidden; border-radius: 12px 12px 0 0;">
                      <img
                        src="funciones/ordenes_embarque_ver_evidencia.php?evidencia_id=<?= $ev['evidencia_id'] ?>"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                        onclick="abrirVisorEvidencia(<?= $ev['evidencia_id'] ?>)">
                    </div>
                    <div class="card-body p-2 text-center">
                      <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">
                        <i class="fa-regular fa-file-image me-1"></i>
                        <?= $ev['nombre_original'] ?>
                      </small>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- VISOR MEJORADO -->
      <div id="visorEvidencia" class="mt-4" style="display:none;">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold" style="color: #0a2472;">
              <i class="fa-regular fa-eye me-2"></i>Vista Previa
            </h6>
            <button class="btn btn-sm btn-outline-secondary" onclick="cerrarVisor()" style="border-radius: 50px;">
              <i class="fa-solid fa-times me-1"></i>Cerrar
            </button>
          </div>
          <div class="card-body p-0">
            <iframe id="iframeEvidencia" width="100%" height="600px" style="border: none;"></iframe>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
  function abrirVisorEvidencia(id) {
    const visor = document.getElementById("visorEvidencia");
    const iframe = document.getElementById("iframeEvidencia");

    iframe.src = "funciones/ordenes_embarque_ver_evidencia.php?evidencia_id=" + id;
    visor.style.display = "block";

    // Smooth scroll al visor
    visor.scrollIntoView({
      behavior: 'smooth',
      block: 'center'
    });
  }

  function cerrarVisor() {
    const visor = document.getElementById("visorEvidencia");
    const iframe = document.getElementById("iframeEvidencia");

    visor.style.display = "none";
    iframe.src = "";
  }

  // Tooltips para mejor UX
  document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>

<style>
  /* Animaciones y estilos adicionales */
  .card {
    transition: all 0.3s ease;
  }

  .table tbody tr {
    transition: all 0.2s ease;
  }

  .badge {
    font-weight: 500;
    letter-spacing: 0.3px;
  }

  .modal-content {
    animation: modalSlideIn 0.3s ease;
  }

  @keyframes modalSlideIn {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
