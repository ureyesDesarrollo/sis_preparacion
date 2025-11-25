<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../seguridad/user_seguridad.php";
include "../funciones/funciones.php";
?>
<style>
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }
</style>

<nav class="navbar bg-body-tertiary" style="box-shadow: 10px 5px 5px #e6e6e6;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../imagenes/logo_progel_v5.png" alt="Progel Mexicana">
        </a>
        <ul class="nav justify-content-center">
            <li>
                <h4>Sistema de revolturas</h4>
            </li>
        </ul>
        <ul class="nav justify-content-end">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-folder-tree"></i> Catálogos
                </a>
                <ul class="dropdown-menu">
                    <?php if (fnc_permiso($_SESSION['privilegio'], 36, 'upe_listar') == 1) { ?>
                        <li><a class="menu-item dropdown-item" href="catalogos/presentaciones_producto.php">Presentaciones del producto</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 37, 'upe_listar') == 1) { ?>
                        <li><a class="menu-item dropdown-item" href="catalogos/parametros_calidad.php">Parámetros de calidad</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 39, 'upe_listar') == 1 || $_SESSION['privilegio'] == 22 || $_SESSION['privilegio'] == 8 || $_SESSION['privilegio'] === 22) { ?>
                        <li class="dropdown-submenu"><a class="test dropdown-item dropdown-toggle" href="#">Racks</a>
                            <ul class="dropdown-menu">
                                <li><a class="menu-item dropdown-item" href="catalogos/racks.php">Racks</a></li>
                                <li><a class="menu-item dropdown-item" href="catalogos/nivel_posicion.php">Nivel - Posición</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 42, 'upe_listar') == 1) { ?>
                        <li class="dropdown-submenu"><a class="test dropdown-item dropdown-toggle" href="#">Calidades</a>
                            <ul class="dropdown-menu">
                                <li><a class="menu-item dropdown-item" href="catalogos/calidades.php">Calidades</a></li>
                                <li><a class="menu-item dropdown-item" href="catalogos/calidades_rangos.php">Calidades Rangos</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 47, 'upe_listar') == 1) { ?>
                        <li><a class="menu-item dropdown-item" href="catalogos/parametros.php">Parámetros</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 49, 'upe_listar') == 1) { ?>
                        <li><a class="menu-item dropdown-item" href="catalogos/clientes.php">Clientes</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 50, 'upe_listar') == 1) { ?>
                        <li><a class="menu-item dropdown-item" href="catalogos/recetas.php">Recetas</a></li>
                    <?php } ?>
					<?php if($_SESSION['privilegio'] == 2){ ?>
                    <li><a class="menu-item dropdown-item" href="catalogos/vendedores.php">Vendedores</a></li>
					<?php } ?>
                </ul>
            </li>
            <?php if (($_SESSION['privilegio'] == 1) || ($_SESSION['privilegio'] == 2)) { ?>
                <li class="nav-item">
                    <a class="nav-link menu-item" href="administrador/administrador.php"><i class="fa-solid fa-gear"></i> Administrador</a>
                </li>
            <?php } ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-gears"></i> Funciones
                </a>
                <ul class="dropdown-menu">
                    <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_listar') == 1 || $_SESSION['privilegio'] == 22) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/tarimas.php">Tarimas</a></li>
                    <?php } ?>
                    <?php if ((fnc_permiso($_SESSION['privilegio'], 45, 'upe_listar') == 1) || ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 | $_SESSION['privilegio'] == 26)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/tarimas_almacen.php">Tarimas en almacen</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 46, 'upe_listar') == 1 || ($_SESSION['privilegio'] == 26)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/revolturas.php">Revolturas</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 48, 'upe_listar') == 1 || ($_SESSION['privilegio'] == 1) || ($_SESSION['privilegio'] == 2)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/mezclas.php">Mezclas</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1) || ($_SESSION['privilegio'] == 2) || ($_SESSION['privilegio'] == 22)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/facturas_empacado.php">Empaques</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 22)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/cliente_empacado.php">Empaques Clientes</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 26 || $_SESSION['privilegio'] == 22)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/orden_embarque.php">Ordenes de embarque</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 28 || $_SESSION['privilegio'] == 6 || $_SESSION['privilegio'] == 20 || $_SESSION['privilegio'] == 26 || $_SESSION['privilegio'] == 22)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/orden_embarque_calidad.php"> Calidad - Ordenes de embarque</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 26)) { ?>
                        <li><a class="menu-item dropdown-item" href="funciones/tarimas_almacen_venta.php">Tarimas para venta</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 26 || $_SESSION['idUsu'] == 218 || $_SESSION['privilegio'] == 20 ||  $_SESSION['privilegio'] == 22)) { ?>
                    <li><a class="menu-item dropdown-item" href="funciones/orden_devolucion.php">Devoluciones</a></li>
                    <?php } ?>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-file-lines"></i> Reportes</a>
                <ul class="dropdown-menu">
                    <?php if (fnc_permiso($_SESSION['privilegio'], 44, 'upe_listar') == 1) { ?>
                        <li><a class="dropdown-item" href="reportes/reporte_tarimas.php" target="_blank"> Reporte Tarimas</a></li>
                    <?php } ?>
                    <?php if (fnc_permiso($_SESSION['privilegio'], 44, 'upe_listar') == 1) { ?>
                        <li><a class="menu-item dropdown-item" href="reportes/rastreabilidad.php" target="_blank"> Rastreabilidad</a></li>
                    <?php } ?>
                    <li><a class="dropdown-item" href="reportes/reporte_producto_terminado.php" target="_blank"> Producto terminado (empacado)</a></li>
                    <?php if (($_SESSION['privilegio'] == 1) || ($_SESSION['privilegio'] == 2)) { ?>
                        <li><a class="menu-item dropdown-item" href="reportes/reporte_procesos.php" target="_blank"> Reporte de procesos</a></li>
                    <?php } ?>
                    <?php if (($_SESSION['privilegio'] == 1) || ($_SESSION['privilegio'] == 2) || ($_SESSION['privilegio'] == 26 || $_SESSION['privilegio'] == 22)) { ?>
                        <li><a class="menu-item dropdown-item" href="reportes/reporte_facturas.php" target="_blank"> Reporte de facturas</a></li>
                    <?php } ?>
                    <li><a class="dropdown-item" href="reportes/reporte_inventario.php" target="_blank"> Producto terminado (sin empacar)</a></li>
                    <li><a class="menu-item dropdown-item" href="reportes/reporte_kardex.php" target="_blank"> Entradas - Salidas</a></li>
                </ul>
            </li>
            <?php if ($_SESSION['privilegio'] == 1 or $_SESSION['privilegio'] == 2 or $_SESSION['privilegio'] == 15 or $_SESSION['privilegio'] == 28) {
            ?>
                <li class="nav-item">
                    <a href="../index_inicio.php" class="nav-link">
                        <i class="fa-solid fa-mortar-pestle"></i> Sis. preparación
                    </a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <?php $urlCompleta = $_SERVER['REQUEST_URI'];

                // Obtén la parte de la URL después de "/sis_preparacion/"
                $parteDeseada = substr($urlCompleta, strlen("/sis_preparacion/"));
                ?>

                <a href=<?php echo "../seguridad/salir.php?url_revolturas=" . $parteDeseada ?> class="nav-link">
                    <i class="fa-solid fa-user"></i> Cerrar sesión
                </a>
            </li>
        </ul>
    </div>
</nav>
<script>
    $(document).ready(function() {
        $('.dropdown-submenu a.test').on("click", function(e) {
            $(this).next('ul').toggle();
            $('.dropdown-submenu .dropdown-menu').not($(this).next('ul')).hide();
            e.stopPropagation();
            e.preventDefault();
        });

        $(document).on("click", function(e) {
            if (!$(e.target).closest('.dropdown-submenu').length) {
                $('.dropdown-submenu .dropdown-menu').hide();
            }
        });
    });
</script>