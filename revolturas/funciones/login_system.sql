-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 14, 2025 at 05:46 PM
-- Server version: 8.3.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `captura_brenda`
--

DROP TABLE IF EXISTS `captura_brenda`;
CREATE TABLE IF NOT EXISTS `captura_brenda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dia_semana` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col1` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col2` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col3` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col4` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col5` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col6` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col7` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col8` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col9` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col10` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col11` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col12` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col13` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col14` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col15` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col16` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col17` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col18` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col19` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col20` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col21` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col22` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col23` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `col24` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datos_calidad`
--

DROP TABLE IF EXISTS `datos_calidad`;
CREATE TABLE IF NOT EXISTS `datos_calidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semana` int NOT NULL,
  `fecha` date NOT NULL,
  `fabricacion_pt_calidad_azul` float NOT NULL,
  `porcentaje_calidad_azul` float NOT NULL,
  `fabricacion_pt_calidad_dorada` float NOT NULL,
  `porcentaje_calidad_dorada` float NOT NULL,
  `fabricacion_pt_calidad_verde` float NOT NULL,
  `porcentaje_calidad_verde` float NOT NULL,
  `liberacion_fisico_quimica` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datos_financieros`
--

DROP TABLE IF EXISTS `datos_financieros`;
CREATE TABLE IF NOT EXISTS `datos_financieros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semana` int NOT NULL,
  `fecha` date NOT NULL,
  `mp_recepcion` decimal(10,0) NOT NULL,
  `mp_importacion` decimal(10,2) DEFAULT NULL,
  `wip_proceso` decimal(10,2) DEFAULT NULL,
  `total_mp` decimal(10,2) DEFAULT NULL,
  `pt` decimal(10,2) DEFAULT NULL,
  `rendimiento_contable` decimal(10,2) DEFAULT NULL,
  `costo_mp` decimal(10,2) DEFAULT NULL,
  `gastos_mp` decimal(10,2) DEFAULT NULL,
  `valuacion_inventarios` decimal(10,2) DEFAULT NULL,
  `gastos_quimicos` decimal(10,2) DEFAULT NULL,
  `indirectos_fabricacion` decimal(10,2) DEFAULT NULL,
  `generales_produccion` decimal(10,2) DEFAULT NULL,
  `venta` decimal(10,2) DEFAULT NULL,
  `administracion` decimal(10,2) DEFAULT NULL,
  `financieros` decimal(10,2) DEFAULT NULL,
  `fabricacion` decimal(10,2) DEFAULT NULL,
  `kg_total` decimal(10,2) DEFAULT NULL,
  `mp_producido` decimal(10,2) DEFAULT NULL,
  `inventario_producido` decimal(10,2) DEFAULT NULL,
  `quimicos_producido` decimal(10,2) DEFAULT NULL,
  `indirectos_producido` decimal(10,2) DEFAULT NULL,
  `generales_producido` decimal(10,2) DEFAULT NULL,
  `venta_producido` int NOT NULL,
  `gastos_admin` decimal(10,2) DEFAULT NULL,
  `gastos_finan` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_financieros`
--

INSERT INTO `datos_financieros` (`id`, `semana`, `fecha`, `mp_recepcion`, `mp_importacion`, `wip_proceso`, `total_mp`, `pt`, `rendimiento_contable`, `costo_mp`, `gastos_mp`, `valuacion_inventarios`, `gastos_quimicos`, `indirectos_fabricacion`, `generales_produccion`, `venta`, `administracion`, `financieros`, `fabricacion`, `kg_total`, `mp_producido`, `inventario_producido`, `quimicos_producido`, `indirectos_producido`, `generales_producido`, `venta_producido`, `gastos_admin`, `gastos_finan`) VALUES
(1, 1, '2025-01-05', 235220, 447890.00, 575440.00, 279630.00, 423163.00, 19.25, 283350.00, 1921315.78, NULL, 863952.03, 183361.38, 2726008.76, 68294.93, 215465.99, 374694.06, 2.15, 116.88, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(2, 2, '2025-01-12', 145710, 295590.00, 543797.00, 241825.00, 395803.00, 12.87, 2000178.90, 3570683.49, NULL, NULL, 264870.50, 4974717.59, 129478.68, 428203.10, 21139.50, 2.12, 117.36, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `datos_pro`
--

DROP TABLE IF EXISTS `datos_pro`;
CREATE TABLE IF NOT EXISTS `datos_pro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semana` int NOT NULL,
  `fecha` date NOT NULL,
  `rendimiento` float NOT NULL,
  `molienda_cuero` float NOT NULL,
  `cocedores` float NOT NULL,
  `clarificador` float NOT NULL,
  `membranas` float NOT NULL,
  `concentradores` float NOT NULL,
  `secado` float NOT NULL,
  `molienda_grenetina` float NOT NULL,
  `fabricacion_pt` float NOT NULL,
  `consumo_gas_natural` float NOT NULL,
  `consumo_gas_lp` float NOT NULL,
  `consumo_kw` float NOT NULL,
  `objetivo_prod` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_pro`
--

INSERT INTO `datos_pro` (`id`, `semana`, `fecha`, `rendimiento`, `molienda_cuero`, `cocedores`, `clarificador`, `membranas`, `concentradores`, `secado`, `molienda_grenetina`, `fabricacion_pt`, `consumo_gas_natural`, `consumo_gas_lp`, `consumo_kw`, `objetivo_prod`) VALUES
(1, 1, '2025-01-05', 13.14, 0, 0, 0, 0, 0, 0, 0, 57, 0, 0, 0, 72),
(2, 2, '2025-01-12', 15.08, 0, 0, 0, 0, 0, 0, 0, 81, 0, 0, 0, 126);

-- --------------------------------------------------------

--
-- Table structure for table `datos_rh`
--

DROP TABLE IF EXISTS `datos_rh`;
CREATE TABLE IF NOT EXISTS `datos_rh` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semana` int NOT NULL,
  `fecha` date NOT NULL,
  `tiempo_extra` float NOT NULL,
  `tiempo_extra_dinero` float NOT NULL,
  `plantilla_operativa` int NOT NULL,
  `plantilla_admin` int NOT NULL,
  `total_plantilla_progel` int NOT NULL,
  `costo_nomina` float NOT NULL,
  `costo_nomina_ton` float NOT NULL,
  `ton_personas_operativas` float NOT NULL,
  `dinero_por_platillo` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_rh`
--

INSERT INTO `datos_rh` (`id`, `semana`, `fecha`, `tiempo_extra`, `tiempo_extra_dinero`, `plantilla_operativa`, `plantilla_admin`, `total_plantilla_progel`, `costo_nomina`, `costo_nomina_ton`, `ton_personas_operativas`, `dinero_por_platillo`) VALUES
(1, 1, '2025-01-05', 722, 117855, 137, 63, 200, 815569, 0, 0, 0),
(2, 2, '2025-01-12', 802, 136572, 137, 65, 202, 1111950, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `datos_ventas`
--

DROP TABLE IF EXISTS `datos_ventas`;
CREATE TABLE IF NOT EXISTS `datos_ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semana` int NOT NULL,
  `fecha` date NOT NULL,
  `pronostico_venta_mensual_ton` float NOT NULL,
  `pronostico_venta_mensual_dinero` float NOT NULL,
  `precio_proyectado_mensual` float NOT NULL,
  `pronostico_venta_ton` float NOT NULL,
  `pronostico_venta_dinero` float NOT NULL,
  `precio_promedio_proyectado` float NOT NULL,
  `venta_comercial` float NOT NULL,
  `venta_industrial` float NOT NULL,
  `venta_real_ton` float NOT NULL,
  `venta_real_dinero` float NOT NULL,
  `precio_promedio_real` float NOT NULL,
  `objetivo_ventas` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_ventas`
--

INSERT INTO `datos_ventas` (`id`, `semana`, `fecha`, `pronostico_venta_mensual_ton`, `pronostico_venta_mensual_dinero`, `precio_proyectado_mensual`, `pronostico_venta_ton`, `pronostico_venta_dinero`, `precio_promedio_proyectado`, `venta_comercial`, `venta_industrial`, `venta_real_ton`, `venta_real_dinero`, `precio_promedio_real`, `objetivo_ventas`) VALUES
(1, 1, '0000-00-00', 410101, 0, 116.02, 47807.5, 5210300, 108.985, 0, 47807.5, 47807.5, 5138000, 107.473, 72),
(2, 2, '0000-00-00', 410101, 0, 116.02, 121420, 13800600, 113.66, 111, 107925, 108036, 11843800, 109.628, 126);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','normal') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Fernando Muro', 'muro', 'e0511aca25d89fbd612dbd4c7a2414e1', 'admin', '2025-01-06 17:09:34'),
(2, 'Administrador', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', '2025-01-06 17:09:34'),
(3, 'Brenda', 'brenda', '1692fcfff3e01e7ba8cffc2baadef5f5', 'normal', '2025-01-06 17:09:34'),
(4, 'Fernando Sandoval', 'sandoval', '7812e8b74f6837fba66f86fe86688a2b', 'normal', '2025-01-06 17:09:34'),
(5, 'Fernando Rull', 'rull', '1dc90e80c77fe245a82ea7ed30d1f849', 'normal', '2025-01-06 17:09:34'),
(6, 'Rafael Lango', 'rafael', 'af2ba5c2458b95f4f4c91d471f2ed622', 'normal', '2025-01-06 17:09:34'),
(7, 'Nancy Barreda', 'nancy', 'ed2c24a8577c6ffa2661410a6d6f27d2', 'normal', '2025-01-06 17:09:34');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
