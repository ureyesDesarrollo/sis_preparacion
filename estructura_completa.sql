-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: bd_sis_preparacion
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `almacen_cajones`
--

DROP TABLE IF EXISTS `almacen_cajones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `almacen_cajones` (
  `ac_id` int NOT NULL AUTO_INCREMENT,
  `ac_descripcion` int NOT NULL,
  `ac_estatus` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `ac_ban` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL COMMENT 'P:preparacion M:materia prima',
  PRIMARY KEY (`ac_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_acciones`
--

DROP TABLE IF EXISTS `bitacora_acciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_acciones` (
  `ba_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `ba_fecha` datetime NOT NULL COMMENT 'Fecha',
  `bm_id` int NOT NULL COMMENT 'Modulo',
  `ba_accion` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'AcciÔö£Ôöén relizada',
  `ba_valor` int NOT NULL COMMENT 'Valor clave',
  PRIMARY KEY (`ba_id`),
  KEY `fk_ba_usu` (`usu_id`),
  KEY `fk_ba_bm` (`bm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=149084 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_alertas`
--

DROP TABLE IF EXISTS `bitacora_alertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_alertas` (
  `ba_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pep_id` int NOT NULL COMMENT 'Id parametro',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `pep_tipo` varchar(5) NOT NULL COMMENT 'Tipo de parametro',
  `pro_id` int NOT NULL COMMENT 'proceso',
  `ba_valor` decimal(10,4) NOT NULL COMMENT 'Valor',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `ba_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',
  `pl_id` int DEFAULT NULL,
  `pp_id` int DEFAULT NULL,
  `ba_tipo` char(1) NOT NULL DEFAULT 'R' COMMENT 'Renglon, Material, LiberaciÔö£Ôöén, N',
  `ba_fe_seg` datetime DEFAULT NULL COMMENT 'Fecha seguimiento',
  `usu_seg` int DEFAULT NULL COMMENT 'Usuario seg.',
  `ba_comentarios` varchar(200) DEFAULT NULL COMMENT 'Comentarios',
  PRIMARY KEY (`ba_id`),
  KEY `fk_ba_pe` (`pe_id`),
  KEY `fk_ba_usu2` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54361 DEFAULT CHARSET=latin1 COMMENT='Control de alertas en desfase en parametros';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_cajones`
--

DROP TABLE IF EXISTS `bitacora_cajones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_cajones` (
  `bc_id` int NOT NULL AUTO_INCREMENT,
  `inv_id` int NOT NULL,
  `cajon_inicial` int NOT NULL,
  `cajon_final` int NOT NULL,
  `usu_id` int NOT NULL,
  `bc_fecha_movimiento` datetime NOT NULL,
  PRIMARY KEY (`bc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7927 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_cambio_estatus`
--

DROP TABLE IF EXISTS `bitacora_cambio_estatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_cambio_estatus` (
  `bce_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `bce_fecha` datetime NOT NULL COMMENT 'Fecha',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `bce_est_actual` int NOT NULL COMMENT 'Est. actual',
  `bce_est_nuevo` int NOT NULL COMMENT 'Est. nuevo',
  `bce_descripcion` varchar(150) NOT NULL COMMENT 'Descripcion',
  `pp_id` int DEFAULT NULL COMMENT 'Paleto',
  `pl_id` int DEFAULT NULL COMMENT 'Lavador',
  `ep_id` int NOT NULL DEFAULT '0' COMMENT 'Equipo preparacion',
  `bce_ot` varchar(10) DEFAULT NULL COMMENT 'orden de trabajo cuando esta en reparacion',
  PRIMARY KEY (`bce_id`),
  KEY `fk_bce_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15641 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_equipos`
--

DROP TABLE IF EXISTS `bitacora_equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_equipos` (
  `be_id` int NOT NULL AUTO_INCREMENT,
  `pro_id` int NOT NULL COMMENT 'proceso',
  `pa_id` int NOT NULL COMMENT 'dato agrupador',
  `ep_anterior` int NOT NULL COMMENT 'equipo anterior',
  `ep_nuevo` int NOT NULL COMMENT 'equipo nuevo',
  `usu_id` int NOT NULL COMMENT 'usuario',
  `be_fecha` datetime NOT NULL COMMENT 'fecha de movimiento',
  `be_comentarios` varchar(250) NOT NULL COMMENT 'comentarios de accion realizada',
  PRIMARY KEY (`be_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1967 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_login`
--

DROP TABLE IF EXISTS `bitacora_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_login` (
  `bl_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `bl_fecha` datetime NOT NULL COMMENT 'Fecha',
  `bl_ip` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL COMMENT 'Ip',
  PRIMARY KEY (`bl_id`),
  KEY `fk_bl_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136964 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bitacora_modulos`
--

DROP TABLE IF EXISTS `bitacora_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_modulos` (
  `bm_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `bm_descripcion` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  PRIMARY KEY (`bm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ciudades`
--

DROP TABLE IF EXISTS `ciudades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciudades` (
  `ciu_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave de la ciudad',
  `est_id` int NOT NULL COMMENT 'Clave del estado',
  `ciu_descripcion` varchar(40) NOT NULL COMMENT 'Descripcion',
  PRIMARY KEY (`ciu_id`),
  KEY `fk_ciu_est` (`est_id`),
  KEY `ciu_id` (`ciu_id`,`est_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `embarque_evidencias`
--

DROP TABLE IF EXISTS `embarque_evidencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `embarque_evidencias` (
  `evidencia_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `embarque_id` bigint unsigned NOT NULL,
  `carpeta_relativa` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_guardado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_original` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tamano_bytes` bigint unsigned NOT NULL,
  `orden` int unsigned DEFAULT NULL,
  `creado_por` bigint unsigned NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`evidencia_id`),
  KEY `idx_embarque` (`embarque_id`),
  KEY `idx_embarque_orden` (`embarque_id`,`orden`)
) ENGINE=InnoDB AUTO_INCREMENT=979 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipos_preparacion`
--

DROP TABLE IF EXISTS `equipos_preparacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipos_preparacion` (
  `ep_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `ep_descripcion` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `ep_tipo` char(1) NOT NULL DEFAULT 'N' COMMENT 'Tipo de equipos(lavador,paleto,receptor,prepardor,etc)',
  `le_id` int NOT NULL COMMENT 'Estatus',
  `estatus` char(1) NOT NULL DEFAULT 'A' COMMENT 'Activo,baja',
  `ep_carga_min` float NOT NULL COMMENT 'capacidad minima del equipo',
  `ep_carga_max` float NOT NULL COMMENT 'capacidad maxima del equipo',
  PRIMARY KEY (`ep_id`),
  KEY `fk_pp_le` (`le_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipos_tipos`
--

DROP TABLE IF EXISTS `equipos_tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipos_tipos` (
  `et_id` int NOT NULL AUTO_INCREMENT,
  `et_descripcion` varchar(25) NOT NULL,
  `et_tipo` char(1) NOT NULL COMMENT 'tipo de equipo(P:paleto,X:preparador,R:recepceptor,L:lavador)',
  `et_orden` int NOT NULL COMMENT 'orden de prioridad o acomo en tablero',
  `et_imagen` varchar(50) NOT NULL COMMENT 'imagen generica de tipo de equipo',
  `et_estatus` char(1) NOT NULL,
  `ban_almacena` char(1) NOT NULL DEFAULT 'N' COMMENT 'S: si, N: no',
  PRIMARY KEY (`et_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados` (
  `est_id` int NOT NULL AUTO_INCREMENT COMMENT 'CLave del estado',
  `est_descripcion` varchar(40) NOT NULL COMMENT 'Descripcion del estado',
  `est_abreviacion` varchar(10) NOT NULL COMMENT 'Abreviacion del nombre',
  PRIMARY KEY (`est_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `factura_sai_detalle`
--

DROP TABLE IF EXISTS `factura_sai_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura_sai_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `factura_id` int NOT NULL,
  `producto_cve` varchar(30) DEFAULT NULL,
  `producto_descripcion` varchar(150) DEFAULT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL,
  `precio_kg` decimal(12,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `factura_id` (`factura_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1414 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `factura_sai_detalle_lote`
--

DROP TABLE IF EXISTS `factura_sai_detalle_lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura_sai_detalle_lote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `detalle_id` int NOT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `detalle_id` (`detalle_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2085 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facturas_sai`
--

DROP TABLE IF EXISTS `facturas_sai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturas_sai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `factura` int NOT NULL,
  `vendedor_nombre` varchar(100) DEFAULT NULL,
  `cliente_nombre` varchar(150) DEFAULT NULL,
  `ubicacion_cliente` varchar(100) DEFAULT NULL,
  `tipo_cliente` varchar(10) DEFAULT NULL,
  `tipo_venta` enum('Comercial','Industrial') NOT NULL,
  `total_factura` decimal(15,2) DEFAULT NULL,
  `total_credito` decimal(15,2) DEFAULT NULL,
  `total_real` decimal(15,2) DEFAULT NULL,
  `observaciones` text,
  `fecha_factura` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `factura` (`factura`)
) ENGINE=MyISAM AUTO_INCREMENT=992 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `indicadores_semanales`
--

DROP TABLE IF EXISTS `indicadores_semanales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indicadores_semanales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `anio` int NOT NULL,
  `semana` int NOT NULL,
  `indicador` varchar(50) NOT NULL,
  `valor` decimal(12,4) NOT NULL,
  `fecha_calculo` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_indicador` (`anio`,`semana`,`indicador`)
) ENGINE=InnoDB AUTO_INCREMENT=357 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `inv_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `inv_fecha` date NOT NULL COMMENT 'Fecha',
  `inv_hora` time NOT NULL COMMENT 'Hora',
  `inv_dia` char(1) NOT NULL COMMENT 'Dia',
  `inv_no_ticket` int NOT NULL COMMENT 'No. ticket',
  `inv_placas` varchar(9) NOT NULL COMMENT 'Placas',
  `inv_camioneta` varchar(15) NOT NULL COMMENT 'Camioneta',
  `prv_id` int NOT NULL COMMENT 'Proveedor',
  `mat_id` int NOT NULL COMMENT 'Material',
  `inv_kilos` float(7,2) NOT NULL COMMENT 'Kilos',
  `inv_prueba` float(7,2) NOT NULL COMMENT 'Prueba secador',
  `inv_desc_ag` int DEFAULT NULL COMMENT 'Desc Agua',
  `inv_desc_d` int DEFAULT NULL COMMENT 'Desc descarne',
  `inv_desc_ren` int DEFAULT NULL COMMENT 'desc Rendimiento',
  `inv_kg_totales` float(7,2) NOT NULL COMMENT 'Kg Totales',
  `inv_calidad` char(1) NOT NULL COMMENT 'Calidad',
  `inv_no_factura` varchar(10) DEFAULT NULL COMMENT 'No. factura',
  `inv_peso_factura` float(7,2) DEFAULT '0.00' COMMENT 'Peso facura',
  `inv_por_merma` int DEFAULT '0' COMMENT 'Porcentaje merma',
  `inv_no_tarimas` float(7,2) DEFAULT '0.00' COMMENT 'No. tarimas',
  `inv_no_sacos` float(7,2) DEFAULT '0.00' COMMENT 'No. sacos',
  `inv_enviado` int NOT NULL DEFAULT '0' COMMENT '0-no enviado, 1-enviado, 2-recibido, 3-baja, 4-devoluciÔö£Ôöén, 5 - pelambrado',
  `inv_fe_enviado` datetime DEFAULT NULL COMMENT 'Fecha de envio',
  `inv_fe_recibe` datetime DEFAULT NULL COMMENT 'Fecha de recepciÔö£Ôöén',
  `prv_recibe` int DEFAULT '0' COMMENT 'Proveedor recibe',
  `inv_tomado` tinyint(1) NOT NULL DEFAULT '0',
  `inv_id_key` int DEFAULT NULL COMMENT 'Clave de la que fue copiado',
  `inv_kg_entrada_maq` float(7,2) DEFAULT NULL COMMENT 'Kilos de carga lavador',
  `inv_kg_lavador` float(7,2) DEFAULT NULL COMMENT 'Kilos de carga lavador o tambor',
  `int_cve_compra` varchar(10) DEFAULT NULL COMMENT 'Clave compra',
  `inv_total_cueros` float(7,2) DEFAULT NULL COMMENT 'Total cueros',
  `inv_prueba2` float(7,2) DEFAULT NULL COMMENT 'Pruebas',
  `inv_desc_ag2` int DEFAULT NULL COMMENT 'Descto agua 2',
  `inv_desc_d2` int DEFAULT NULL COMMENT 'Descto descarne',
  `inv_desc_ren2` int DEFAULT NULL COMMENT 'Descto rendimiento 2',
  `inv_hora_entrada` datetime DEFAULT NULL,
  `inv_hora_salida` datetime DEFAULT NULL,
  `inv_hora_salida2` datetime DEFAULT NULL COMMENT 'fecha y hora salida camion que entrega material de maquila ',
  `inv_folio_interno` int NOT NULL DEFAULT '0' COMMENT 'NO DE VIAJE /CONSEC',
  `inv_folio_interno2` int DEFAULT NULL COMMENT 'NO DE VIAJE /CONSEC EN RECECPCIÔö£├┤N DE MAQUILA',
  `inv_estado` char(1) NOT NULL DEFAULT 'X',
  `inv_prueba_rendimiento` float(8,2) NOT NULL DEFAULT '0.00',
  `usu_id` int DEFAULT NULL,
  `inv_extrac` decimal(10,4) DEFAULT NULL COMMENT 'Extractibilidad',
  `inv_observaciones` varchar(500) DEFAULT NULL,
  `ac_id` int NOT NULL DEFAULT '0' COMMENT 'Cajon',
  `inv_costo` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'costo de material',
  `inv_solicitado` char(1) DEFAULT NULL COMMENT 'S:solicitado a mp E:entregado a patio molinos',
  `inv_especial` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Seguimiento especial',
  `inv_ban_flor` tinyint(1) DEFAULT '0' COMMENT 'Bandera de seleccion de venta flor',
  `inv_alcalinidad` decimal(10,2) DEFAULT NULL COMMENT 'Alcalinidad total',
  `inv_calcios` decimal(10,2) DEFAULT NULL COMMENT 'calcios',
  `inv_humedad` decimal(10,2) DEFAULT NULL COMMENT 'humedad',
  `inv_ce` decimal(7,2) DEFAULT NULL,
  `inv_costo_mql` decimal(7,2) DEFAULT NULL COMMENT 'Costo de maquila',
  `inv_humedad_origen` float(5,2) DEFAULT NULL COMMENT 'Humedad de origen',
  `inv_solidos` float(5,2) DEFAULT NULL COMMENT '% Solidos',
  PRIMARY KEY (`inv_id`),
  KEY `fk_inv_mat` (`mat_id`),
  KEY `fk_inv_prv` (`prv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31322 DEFAULT CHARSET=latin1 COMMENT='Entradas de material';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario_diario_materiales`
--

DROP TABLE IF EXISTS `inventario_diario_materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_diario_materiales` (
  `idm_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `idm_fecha` datetime NOT NULL COMMENT 'Fecha',
  `idm_documento` varchar(25) NOT NULL COMMENT 'Documento',
  `mat_id` int NOT NULL COMMENT 'Material',
  `idm_cant_ing` float NOT NULL COMMENT 'Cantidad Ingresada',
  `idm_cant_ant` float NOT NULL COMMENT 'Cantidad anterior',
  `idm_cant_new` float NOT NULL COMMENT 'Cantidad nueva',
  `inv_id` int NOT NULL COMMENT 'Clave inventario',
  PRIMARY KEY (`idm_id`),
  UNIQUE KEY `idx_damp` (`idm_fecha`,`idm_documento`,`mat_id`,`idm_cant_ing`,`usu_id`,`inv_id`) USING BTREE,
  KEY `fk_damp_mat` (`mat_id`),
  KEY `fk_damp_usu` (`usu_id`),
  KEY `fk_idm_inv` (`inv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48774 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario_pelambre`
--

DROP TABLE IF EXISTS `inventario_pelambre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_pelambre` (
  `ip_id` int NOT NULL AUTO_INCREMENT,
  `inv_id` int NOT NULL,
  `ep_id` int NOT NULL COMMENT 'equipo',
  `ip_fecha_envio` datetime NOT NULL COMMENT 'fecha de envio a pelambrado',
  `ip_fecha_remojo` datetime NOT NULL COMMENT 'fecha de remojo',
  `ip_hora_ini_remojo` time NOT NULL COMMENT 'Hora Que Inicia El Remojo',
  `ip_hora_ini_carga` time NOT NULL COMMENT 'Hora Inicio de Carga',
  `ip_hora_fin_carga` time NOT NULL COMMENT 'Hora Termino Carga',
  `ip_ban` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL COMMENT '1:proceso activo ',
  `usu_id` int NOT NULL,
  `ip_fe_hr_ter_remojo` datetime DEFAULT NULL COMMENT 'Fecha termino remojo',
  `ip_fe_hr_ter_encalado` datetime DEFAULT NULL COMMENT 'Fecha termino encalado',
  `ip_ph_encalado` decimal(5,2) DEFAULT NULL COMMENT 'ph encalado',
  `ip_lavado_encalado` decimal(5,2) DEFAULT NULL COMMENT 'lavado encalado',
  `ip_hrs_totales` decimal(5,2) DEFAULT NULL COMMENT 'horas totales del proceso',
  `ip_fe_descarga` datetime DEFAULT NULL COMMENT 'fecha de descarga a mp',
  `ip_kg_finales` decimal(10,2) DEFAULT NULL COMMENT 'kilos finales',
  `ip_observaciones` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL COMMENT 'Observaciones',
  PRIMARY KEY (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario_pelambre_etapas`
--

DROP TABLE IF EXISTS `inventario_pelambre_etapas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_pelambre_etapas` (
  `ipe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `ipe_nombre` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL DEFAULT 'A' COMMENT 'nombre de etapa',
  `ipe_estatus` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL DEFAULT 'A' COMMENT 'estatus etapa',
  PRIMARY KEY (`ipe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario_pelambre_etapas_1`
--

DROP TABLE IF EXISTS `inventario_pelambre_etapas_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_pelambre_etapas_1` (
  `ipe_id` int NOT NULL AUTO_INCREMENT COMMENT 'clave',
  `ipe_ren` int NOT NULL COMMENT 'renglon',
  `ip_id` int NOT NULL COMMENT 'inventario pelambre',
  `ipe_etapa` int NOT NULL COMMENT 'etapa',
  `ipe_porcentaje` decimal(5,2) NOT NULL,
  `ipe_cantidad` decimal(10,2) NOT NULL COMMENT 'kilos o litros',
  `quim_id` int NOT NULL COMMENT 'quimico',
  `ipe_lote` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL COMMENT 'lote',
  `ipe_horas` decimal(5,2) DEFAULT NULL COMMENT 'horas',
  `ipe_minutos` decimal(5,2) DEFAULT NULL COMMENT 'minutos',
  `ipe_fe_hr_inicio` datetime DEFAULT NULL COMMENT 'fecha hora inicio',
  `ipe_fe_hr_fin` datetime DEFAULT NULL COMMENT 'fecha hora fin',
  `ipe_observaciones` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL COMMENT 'observaciones',
  `usu_id` int NOT NULL COMMENT 'usuario',
  `ipe_fe_sistema` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de sistema',
  PRIMARY KEY (`ipe_id`),
  KEY `fk_ipe_ip` (`ip_id`),
  KEY `fk_ipe_etapa` (`ipe_etapa`),
  KEY `fk_ipe_usu` (`usu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario_pelambre_etapas_2`
--

DROP TABLE IF EXISTS `inventario_pelambre_etapas_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_pelambre_etapas_2` (
  `ipe_id` int NOT NULL AUTO_INCREMENT COMMENT 'clave',
  `ipe_ren` int NOT NULL COMMENT 'renglon',
  `ip_id` int NOT NULL COMMENT 'inventario pelambre',
  `ipe_etapa` int NOT NULL COMMENT 'etapa',
  `ipe_fe_inicio` date DEFAULT NULL COMMENT 'fecha',
  `ipe_hr_inicio` time DEFAULT NULL COMMENT 'hora inicio',
  `ipe_hr_fin` time DEFAULT NULL COMMENT 'hora fin',
  `ipe_ph` decimal(5,2) DEFAULT NULL COMMENT 'ph',
  `ipe_ce` decimal(5,2) DEFAULT NULL COMMENT 'ce',
  `ipe_redox` decimal(5,2) DEFAULT NULL COMMENT 'redox',
  `usu_id` int NOT NULL COMMENT 'usuario',
  `ipe_fe_sistema` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de sistema',
  PRIMARY KEY (`ipe_id`),
  KEY `fk_ipe_ip` (`ip_id`),
  KEY `fk_ipe_etapa` (`ipe_etapa`),
  KEY `fk_ipe_usu` (`usu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `listado_estatus`
--

DROP TABLE IF EXISTS `listado_estatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listado_estatus` (
  `le_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `le_estatus` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `le_aplica` char(1) NOT NULL COMMENT 'Lavador, Paleto',
  `le_color` varchar(7) NOT NULL COMMENT 'color de estatus de quipos	',
  `le_tipo` char(1) NOT NULL COMMENT 'E:estatus de equipo, P:estatus de proceso	',
  PRIMARY KEY (`le_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lotes`
--

DROP TABLE IF EXISTS `lotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotes` (
  `lote_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `lote_fecha` date NOT NULL COMMENT 'Fecha',
  `lote_hora` time NOT NULL COMMENT 'Hora',
  `lote_mes` varchar(2) NOT NULL COMMENT 'Mes',
  `lote_turno` char(1) NOT NULL COMMENT 'Turno',
  `lote_folio` varchar(10) NOT NULL COMMENT 'Folio',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `lote_lim_param` varchar(11) DEFAULT NULL,
  `lote_bloom` float DEFAULT NULL,
  `lote_viscocidad` float DEFAULT NULL,
  `lote_ph_final` float DEFAULT NULL,
  `lote_transparencia` float DEFAULT NULL,
  `lote_porcen_t` float DEFAULT NULL,
  `lote_ntu` float DEFAULT NULL,
  `lote_humedad` float DEFAULT NULL,
  `lote_cenizas` float DEFAULT NULL,
  `lote_redox` float DEFAULT NULL,
  `lote_color` float DEFAULT NULL,
  `lote_grano` float DEFAULT NULL,
  `lote_olor` varchar(11) DEFAULT NULL,
  `lote_part_ext` float DEFAULT NULL,
  `lote_part_ind` float DEFAULT NULL,
  `lote_hidratacion` varchar(11) DEFAULT NULL,
  `lote_aceptado` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`lote_id`),
  KEY `fk_lote_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1360 DEFAULT CHARSET=latin1 COMMENT='Tabla para almacenar los lotes de los procesos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lotes_anio`
--

DROP TABLE IF EXISTS `lotes_anio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotes_anio` (
  `lote_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `lote_fecha` date NOT NULL COMMENT 'Fecha',
  `lote_hora` time NOT NULL COMMENT 'Hora',
  `lote_anio` int NOT NULL,
  `lote_mes` varchar(2) NOT NULL COMMENT 'Mes',
  `lote_turno` char(1) NOT NULL COMMENT 'Turno',
  `lote_folio` varchar(10) NOT NULL COMMENT 'Folio',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `lote_rendimiento` decimal(7,2) DEFAULT NULL COMMENT 'Rendimiento de lote',
  `lote_estatus` tinyint DEFAULT '2' COMMENT '0 - terminado preparacion,1 - Extraccion,2 - Tomar a revolturas,3- Tomado a revolver',
  PRIMARY KEY (`lote_id`),
  KEY `fk_lote_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1666 DEFAULT CHARSET=latin1 COMMENT='Tabla para almacenar los lotes de los procesos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lotes_procesos`
--

DROP TABLE IF EXISTS `lotes_procesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotes_procesos` (
  `lp_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `lote_id` int NOT NULL COMMENT 'Lote',
  `prop_id` int NOT NULL COMMENT 'Proceso',
  PRIMARY KEY (`lp_id`),
  KEY `fk_lp_lote` (`lote_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1360 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materiales`
--

DROP TABLE IF EXISTS `materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materiales` (
  `mat_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `mt_id` int NOT NULL COMMENT 'Tipo de material',
  `mat_nombre` varchar(60) NOT NULL COMMENT 'Decripcion',
  `um_id` int NOT NULL COMMENT 'Unidad de medida',
  `mat_costo` float NOT NULL DEFAULT '0' COMMENT 'Costo del material',
  `mat_stock_min` float NOT NULL DEFAULT '0' COMMENT 'Stock minimo',
  `mat_stock_max` float NOT NULL DEFAULT '0',
  `mat_existencia` float NOT NULL DEFAULT '0' COMMENT 'Existencia de material',
  `mat_est` char(1) NOT NULL DEFAULT 'A' COMMENT 'Estatus',
  `mat_comentarios` varchar(100) DEFAULT NULL COMMENT 'Comentarios extra de material',
  `mat_ingreso` char(1) NOT NULL DEFAULT 'N' COMMENT 'Si kilos de proveedor, No',
  PRIMARY KEY (`mat_id`),
  UNIQUE KEY `mat_nombre` (`mat_nombre`),
  KEY `fk_mat_um` (`um_id`),
  KEY `fk_mat_mt` (`mt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materiales_costos`
--

DROP TABLE IF EXISTS `materiales_costos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materiales_costos` (
  `mc_id` int NOT NULL AUTO_INCREMENT,
  `prv_id` int NOT NULL,
  `mat_id` int NOT NULL,
  `mc_costo` decimal(10,2) NOT NULL,
  `mc_fe_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mc_year` int NOT NULL COMMENT 'aÔö£ÔûÆo ',
  PRIMARY KEY (`mc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materiales_tipo`
--

DROP TABLE IF EXISTS `materiales_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materiales_tipo` (
  `mt_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `mt_descripcion` varchar(17) NOT NULL COMMENT 'Descripcion',
  `mt_est` char(1) NOT NULL,
  PRIMARY KEY (`mt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `materiales_tipo_obj`
--

DROP TABLE IF EXISTS `materiales_tipo_obj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materiales_tipo_obj` (
  `mto_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `mt_id` int NOT NULL COMMENT 'Tipo de material',
  `mto_kilos` float NOT NULL COMMENT 'Kilos',
  `mto_fecha` date NOT NULL COMMENT 'Fecha',
  `prv_id` int DEFAULT NULL COMMENT 'Proveedor',
  PRIMARY KEY (`mto_id`),
  KEY `fk_mto_mt` (`mt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1166 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mezclas`
--

DROP TABLE IF EXISTS `mezclas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mezclas` (
  `mez_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `mez_nombre` varchar(25) NOT NULL COMMENT 'Nombre',
  PRIMARY KEY (`mez_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Mezclas';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mezclas_materiales`
--

DROP TABLE IF EXISTS `mezclas_materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mezclas_materiales` (
  `mm_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `mez_id` int NOT NULL COMMENT 'Mezcla',
  `mat_id` int NOT NULL COMMENT 'Material',
  PRIMARY KEY (`mm_id`),
  KEY `fk_mm_mez` (`mez_id`),
  KEY `fk_mm_mat` (`mat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Mezclas y materiales';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `movimiento_equipos`
--

DROP TABLE IF EXISTS `movimiento_equipos`;
/*!50001 DROP VIEW IF EXISTS `movimiento_equipos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `movimiento_equipos` AS SELECT 
 1 AS `pro_id`,
 1 AS `agrupado`,
 1 AS `equipo_anterior`,
 1 AS `equipo_nuevo`,
 1 AS `usu_usuario`,
 1 AS `be_fecha`,
 1 AS `be_comentarios`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `notas_credito`
--

DROP TABLE IF EXISTS `notas_credito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas_credito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `factura` int DEFAULT NULL,
  `folio_nota` varchar(10) DEFAULT NULL,
  `tipo` enum('DESCUENTO','DEVOLUCION') DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orden_devolucion`
--

DROP TABLE IF EXISTS `orden_devolucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_devolucion` (
  `od_id` int NOT NULL AUTO_INCREMENT,
  `cte_id` int NOT NULL,
  `usu_id` int NOT NULL,
  `od_motivo` text,
  `od_estado` enum('PENDIENTE','RECIBIDO','PROCESO','FINALIZADA') DEFAULT 'PENDIENTE',
  `od_fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `od_fecha_recibe` datetime DEFAULT NULL,
  PRIMARY KEY (`od_id`),
  KEY `cte_id` (`cte_id`),
  KEY `usu_id` (`usu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orden_devolucion_analisis`
--

DROP TABLE IF EXISTS `orden_devolucion_analisis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_devolucion_analisis` (
  `oda_id` int NOT NULL AUTO_INCREMENT,
  `odd_id` int NOT NULL,
  `bloom` decimal(6,2) DEFAULT NULL,
  `viscosidad` decimal(6,2) DEFAULT NULL,
  `ph` decimal(6,2) DEFAULT NULL,
  `trans` decimal(6,2) DEFAULT NULL,
  `ntu` decimal(6,2) DEFAULT NULL,
  `humedad` decimal(6,2) DEFAULT NULL,
  `cenizas` decimal(6,2) DEFAULT NULL,
  `ce` decimal(6,2) DEFAULT NULL,
  `redox` decimal(6,2) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `olor` varchar(100) DEFAULT NULL,
  `pe_1kg` decimal(6,2) DEFAULT NULL,
  `par_extr` decimal(6,2) DEFAULT NULL,
  `par_ind` decimal(6,2) DEFAULT NULL,
  `hidratacion` varchar(5) DEFAULT NULL,
  `porcentaje_t` decimal(6,2) DEFAULT NULL,
  `malla_30` decimal(6,2) DEFAULT NULL,
  `malla_45` decimal(6,2) DEFAULT NULL,
  `malla_60` decimal(6,2) DEFAULT NULL,
  `malla_100` decimal(6,2) DEFAULT NULL,
  `malla_200` decimal(6,2) DEFAULT NULL,
  `malla_base` decimal(6,2) DEFAULT NULL,
  `coliformes` decimal(6,2) DEFAULT NULL,
  `ecoli` decimal(6,2) DEFAULT NULL,
  `salmonella` decimal(6,2) DEFAULT NULL,
  `saereus` decimal(6,2) DEFAULT NULL,
  `rechazado` char(1) DEFAULT NULL,
  `fecha_analisis` datetime DEFAULT CURRENT_TIMESTAMP,
  `cal_id` int DEFAULT NULL,
  PRIMARY KEY (`oda_id`),
  KEY `odd_id` (`odd_id`),
  KEY `cal_id` (`cal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orden_devolucion_detalle`
--

DROP TABLE IF EXISTS `orden_devolucion_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_devolucion_detalle` (
  `odd_id` int NOT NULL AUTO_INCREMENT,
  `od_id` int NOT NULL,
  `tipo_empaque` enum('rr','rrc','pe') NOT NULL,
  `id_empaque` int NOT NULL,
  `lote` varchar(50) NOT NULL,
  `factura` varchar(50) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `fecha_recepcion` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado_lote` enum('PENDIENTE','RECIBIDO','EN ALMACEN','PROCESO DE ANALISIS','LIBERADA') DEFAULT 'PENDIENTE',
  PRIMARY KEY (`odd_id`),
  KEY `od_id` (`od_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parametros`
--

DROP TABLE IF EXISTS `parametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametros` (
  `rojo` int NOT NULL COMMENT 'DÔö£┬ías en color rojo',
  `amarillo` int NOT NULL COMMENT 'DÔö£┬ías en color amarillo',
  `verde` int NOT NULL COMMENT 'DÔö£┬ías en color verde',
  `ton_produccion` decimal(10,2) NOT NULL COMMENT 'TON de producciÔö£Ôöén diaria'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_etapas`
--

DROP TABLE IF EXISTS `preparacion_etapas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_etapas` (
  `pe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pe_descripcion` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `pe_nombre` varchar(20) NOT NULL COMMENT 'Nombre etapa',
  `pe_archivo` varchar(45) NOT NULL COMMENT 'Archivo a mostrar en etapa',
  `pe_archivo_exp` varchar(25) NOT NULL COMMENT 'Archivo a exportar',
  `pe_hr_ideal` int NOT NULL COMMENT 'Horas Ideales',
  `pe_hr_maxima` int NOT NULL COMMENT 'Horas Maximas',
  `pe_hr_validacion` tinyint NOT NULL DEFAULT '0' COMMENT 'Activar validacion',
  `pe_tipo` char(1) NOT NULL COMMENT 'Ce, Ph, Hr',
  `pe_inicio` float NOT NULL COMMENT 'Rango de inicio',
  `pe_fin` float NOT NULL COMMENT 'Rango de fin',
  PRIMARY KEY (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_etapas_mezclas`
--

DROP TABLE IF EXISTS `preparacion_etapas_mezclas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_etapas_mezclas` (
  `pem_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pep_id` int NOT NULL COMMENT 'Etapa parametro',
  `mez_id` int NOT NULL COMMENT 'Mezcla',
  PRIMARY KEY (`pem_id`),
  KEY `fk_pem_pe` (`pep_id`),
  KEY `fk_pem_mez` (`mez_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_etapas_param`
--

DROP TABLE IF EXISTS `preparacion_etapas_param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_etapas_param` (
  `pep_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pe_id` int NOT NULL COMMENT 'Clave',
  `pep_descripcion` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `pep_nombre` varchar(20) NOT NULL COMMENT 'Nombre etapa',
  `pep_tipo` char(4) NOT NULL COMMENT 'Ce, Ph, Hr',
  `pep_inicio` float NOT NULL COMMENT 'Rango de inicio',
  `pep_fin` float NOT NULL COMMENT 'Rango de fin',
  `pep_control_lib` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Control por liberacion',
  `pep_control_renglon` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Control por renglon',
  `pep_control_material` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'control por material',
  `pep_enviar_email` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'EnvÔö£┬ío de email',
  `pep_tabla` varchar(20) DEFAULT NULL COMMENT 'Tabla para ver la columna',
  `pep_columna` varchar(20) DEFAULT NULL COMMENT 'columna',
  `pep_tabla_p` varchar(20) DEFAULT NULL COMMENT 'tabla padre',
  `pep_columna_p` varchar(20) DEFAULT NULL COMMENT 'columna padre',
  PRIMARY KEY (`pep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_lavadores`
--

DROP TABLE IF EXISTS `preparacion_lavadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_lavadores` (
  `pl_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pl_descripcion` varchar(15) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `le_id` int NOT NULL COMMENT 'Estatus',
  PRIMARY KEY (`pl_id`),
  KEY `fk_pl_le` (`le_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_paletos`
--

DROP TABLE IF EXISTS `preparacion_paletos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_paletos` (
  `pp_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pp_descripcion` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `pp_tipo` char(1) DEFAULT 'N' COMMENT 'Tipo de paleto, Especial, Normal',
  `le_id` int NOT NULL COMMENT 'Estatus',
  PRIMARY KEY (`pp_id`),
  KEY `fk_pp_le` (`le_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_tipo`
--

DROP TABLE IF EXISTS `preparacion_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_tipo` (
  `pt_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pt_descripcion` varchar(50) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `pt_revision` varchar(20) NOT NULL,
  `pt_para` char(1) DEFAULT 'M' COMMENT 'L, P, M',
  `pt_estatus` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`pt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preparacion_tipo_etapas`
--

DROP TABLE IF EXISTS `preparacion_tipo_etapas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preparacion_tipo_etapas` (
  `pte_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pt_id` int NOT NULL COMMENT 'Tipo de preparacion',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `pte_orden` tinyint NOT NULL COMMENT 'Orden de la etapa',
  PRIMARY KEY (`pte_id`),
  KEY `fk_pte_pt` (`pt_id`),
  KEY `fk_pte_pe` (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos`
--

DROP TABLE IF EXISTS `procesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos` (
  `pro_id` int NOT NULL AUTO_INCREMENT COMMENT 'Proceso',
  `pl_id` int DEFAULT NULL COMMENT 'Lavador',
  `pt_id` int NOT NULL COMMENT 'Tipo preparaciÔö£Ôöén',
  `pro_total_kg` float(9,2) NOT NULL COMMENT 'Kilos',
  `pro_fe_carga` date DEFAULT NULL COMMENT 'Fecha de Carga',
  `pro_hr_inicio` time DEFAULT NULL COMMENT 'Hora Inicio',
  `pro_hr_fin` time DEFAULT NULL COMMENT 'Hora Fin',
  `pro_molino1` tinyint(1) DEFAULT '0' COMMENT 'Molino 1',
  `pro_molino2` tinyint(1) DEFAULT '0' COMMENT 'Molino 2',
  `pro_molino3` tinyint(1) DEFAULT '0' COMMENT 'Molino 3',
  `pro_molino4` tinyint(1) DEFAULT '0' COMMENT 'Molino 4',
  `pro_molino5` tinyint(1) DEFAULT '0' COMMENT 'Molino 5',
  `pro_pila` smallint DEFAULT '0' COMMENT 'Pila',
  `pro_ph` float DEFAULT NULL COMMENT 'PH',
  `pro_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `pro_ce` float DEFAULT NULL COMMENT 'Conductividad electrica',
  `pro_pila2` smallint DEFAULT NULL COMMENT 'Pila',
  `pro_ph2` float DEFAULT NULL COMMENT 'Ph',
  `pro_temp2` float DEFAULT NULL COMMENT 'Temperatura',
  `pro_ce2` float DEFAULT NULL COMMENT 'Ce',
  `pro_col_limp` char(1) DEFAULT NULL COMMENT 'Coladores limpios',
  `pro_cuero` char(1) DEFAULT '0' COMMENT 'Cuero',
  `pro_estatus` smallint NOT NULL DEFAULT '1' COMMENT '1 En Proceso 2 Terminado',
  `pro_operador` int NOT NULL DEFAULT '0' COMMENT 'Operador',
  `pro_supervisor` int NOT NULL COMMENT 'Supervisor',
  `pro_fe_sistema` datetime DEFAULT NULL COMMENT 'Captura de operador automatica',
  `pro_fe_termino` datetime DEFAULT NULL COMMENT 'Fecha termino',
  `pro_observaciones` varchar(200) DEFAULT NULL COMMENT 'Observaciones molinero',
  `pro_tam_cuero` char(1) DEFAULT NULL COMMENT 'Chico, Mediano, Grande	',
  `hrs_totales_calculadas` float DEFAULT NULL COMMENT 'hora totales del proceso calculado',
  `hrs_totales_capturadas` float DEFAULT NULL COMMENT 'hora totales del proceso capturado en fases finales',
  `pro_hrs_tot_muerto` float(5,2) DEFAULT '0.00' COMMENT 'Horas en tiempo muerto',
  PRIMARY KEY (`pro_id`),
  KEY `fk_pro_pl` (`pl_id`),
  KEY `fk_pro_pt` (`pt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10876 DEFAULT CHARSET=latin1 COMMENT='Tabla para guardar los procesos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_agrupados`
--

DROP TABLE IF EXISTS `procesos_agrupados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_agrupados` (
  `pa_ren` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pa_id` int NOT NULL COMMENT 'Proceso agrupado indice',
  `pro_id` int NOT NULL COMMENT 'proceso',
  `pa_fe_hr` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha y hora de bd',
  `lote_id` int DEFAULT NULL COMMENT 'lote generado por sistema',
  `pro_id_pa` varchar(255) DEFAULT NULL,
  `usu_id_auth` int DEFAULT NULL COMMENT 'Usuario de autorizaciÔö£Ôöén',
  PRIMARY KEY (`pa_ren`),
  KEY `fk_ppd_prop` (`pa_id`),
  KEY `fk_ppd_pro` (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1945 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_auxiliar`
--

DROP TABLE IF EXISTS `procesos_auxiliar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_auxiliar` (
  `proa_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `proa_fe_ini` date NOT NULL COMMENT 'Fecha inicio',
  `proa_hr_ini` time NOT NULL COMMENT 'Hr inicio',
  `proa_fe_fin` date DEFAULT NULL COMMENT 'Fecha fin',
  `proa_hr_fin` time DEFAULT NULL COMMENT 'Hr fin',
  `proa_temp_final` float(5,2) DEFAULT NULL,
  `usu_op` int NOT NULL COMMENT 'Operador',
  `proa_observaciones` varchar(350) DEFAULT NULL COMMENT 'Observaciones',
  `usu_sup` int DEFAULT NULL COMMENT 'Supervisor',
  PRIMARY KEY (`proa_id`),
  KEY `fk_proa_pro` (`pro_id`),
  KEY `fk_proa_pe` (`pe_id`),
  KEY `fk_proa_usu` (`usu_op`)
) ENGINE=InnoDB AUTO_INCREMENT=74067 DEFAULT CHARSET=latin1 COMMENT='Auxiliar en captura de comentarios';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_equipos`
--

DROP TABLE IF EXISTS `procesos_equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_equipos` (
  `ped_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso paleto',
  `ep_id` int NOT NULL COMMENT 'Paleto',
  `pe_ban_activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Bandera de equipo activo',
  `ped_fe_hr` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ped_id`),
  KEY `fk_pph_prop` (`pro_id`),
  KEY `fk_pph_pp` (`ep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3962 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_1_d`
--

DROP TABLE IF EXISTS `procesos_fase_1_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_1_d` (
  `pfd1_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg1_id` int NOT NULL COMMENT 'General',
  `pfd1_ren` int NOT NULL COMMENT 'Numero de renglon',
  `tpa_id` int NOT NULL COMMENT 'Tipo agua',
  `pfd1_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `pfd1_hr_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `pfd1_hr_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `pfd1_hr_ini_mov` time DEFAULT NULL COMMENT 'Hr inicio mov',
  `pfd1_hr_fin_mov` time DEFAULT NULL COMMENT 'Hr fin mov',
  `pfd1_ph` float DEFAULT NULL COMMENT 'PH',
  `pfd1_ce` float DEFAULT NULL COMMENT 'CE',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd1_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Operador captura',
  `pfd1_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd1_id`),
  KEY `fk_pfd1_pfg1` (`pfg1_id`),
  KEY `fk_pfd1_usu` (`usu_id`),
  KEY `fk_pfd1_tpa` (`tpa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_1_g`
--

DROP TABLE IF EXISTS `procesos_fase_1_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_1_g` (
  `pfg1_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'No. etapa',
  `pfg1_temp_ag` float DEFAULT NULL COMMENT 'Tempereatura',
  `pfg1_ph_agua` float(5,2) DEFAULT NULL COMMENT 'Ph agua inicio',
  `pfg1_ce_agua` float(5,2) DEFAULT NULL COMMENT 'Ce agua inicio',
  `pfg1_extractivilidad` float(5,2) NOT NULL DEFAULT '0.00',
  `pfg1_peroxido` decimal(6,2) DEFAULT NULL,
  `pfg1_redox` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`pfg1_id`),
  KEY `fk_pfg1_pro` (`pro_id`),
  KEY `fk_pfg1_pe` (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11707 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_2_d`
--

DROP TABLE IF EXISTS `procesos_fase_2_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_2_d` (
  `pfd2_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg2_id` int NOT NULL COMMENT 'Proceso',
  `pfd2_ren` int NOT NULL COMMENT 'Renglon',
  `pfd2_hr` time NOT NULL COMMENT 'Hora',
  `pfd2_ph` float(5,2) NOT NULL COMMENT 'PH',
  `pfd2_sosa` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd2_temp` float(5,2) NOT NULL COMMENT 'Temperatura',
  `pfd2_redox` float(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Redox',
  `pfd2_acido` float(5,2) NOT NULL COMMENT 'acido',
  `pfd2_peroxido` float NOT NULL COMMENT 'peroxido',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd2_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd2_id`),
  KEY `fk_pfd2_pfg2` (`pfg2_id`),
  KEY `fk_pfd2_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35265 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_2_g`
--

DROP TABLE IF EXISTS `procesos_fase_2_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_2_g` (
  `pfg2_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg2_temp_ag` decimal(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg2_ph_ant` decimal(5,2) NOT NULL COMMENT 'PH anterior',
  `pfg2_ce` decimal(5,2) NOT NULL COMMENT 'CE',
  `pfg2_sosa` decimal(6,2) NOT NULL COMMENT 'Sosa',
  `pfg2_ph_aju` decimal(5,2) NOT NULL COMMENT 'PH ajustado',
  `pfg2_peroxido` decimal(6,2) NOT NULL COMMENT 'Peroxido',
  PRIMARY KEY (`pfg2_id`),
  KEY `fk_pfg2_pro` (`pro_id`),
  KEY `fk_pfg2_pe` (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9493 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_2b_d`
--

DROP TABLE IF EXISTS `procesos_fase_2b_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_2b_d` (
  `pfd2_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg2_id` int NOT NULL COMMENT 'Proceso',
  `pfd2_ren` int NOT NULL COMMENT 'Renglon',
  `pfd2_hr` time NOT NULL COMMENT 'Hora',
  `pfd2_ph` float(5,2) NOT NULL COMMENT 'PH',
  `pfd2_sosa` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd2_acido` float(5,2) NOT NULL COMMENT 'Acido',
  `pfd2_temp` float(5,2) NOT NULL,
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd2_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd2_id`),
  KEY `fk_pfd2b_pfg` (`pfg2_id`),
  KEY `fk_pfd2b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39428 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_2b_d2`
--

DROP TABLE IF EXISTS `procesos_fase_2b_d2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_2b_d2` (
  `pfd22_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg2_id` int NOT NULL COMMENT 'Proceso',
  `pfd22_ren` int NOT NULL COMMENT 'Renglon',
  `pfd22_hr` time NOT NULL COMMENT 'Hora',
  `pfd22_min` float NOT NULL COMMENT 'Mon mon',
  `pfd22_reposo` float(5,2) NOT NULL COMMENT 'Reposo',
  `pfd22_ph` float(5,2) NOT NULL COMMENT 'Ph',
  `pfd22_temp` float(5,2) NOT NULL COMMENT 'Temperatura',
  `pfd22_sosa` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd22_acido` float(5,2) NOT NULL COMMENT 'Acido',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd22_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd22_id`),
  KEY `fk_pfd2b2_pfg2` (`pfg2_id`),
  KEY `fk_pfd2b2_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15427 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_2b_g`
--

DROP TABLE IF EXISTS `procesos_fase_2b_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_2b_g` (
  `pfg2_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg2_temp_ag` float(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg2_enzima` float(5,2) NOT NULL COMMENT 'Kilos de enzima',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfg2_hr_totales` int DEFAULT NULL COMMENT 'Horas totales de enzima',
  `pfg2_ph1` float(5,2) DEFAULT NULL COMMENT 'Ph',
  `pfg2_hr1` int DEFAULT NULL COMMENT 'Horas',
  `pfg2_usu1` int DEFAULT NULL COMMENT 'LCP',
  `pfg2_ph2` float(5,2) DEFAULT NULL COMMENT 'Ph',
  `pfg2_hr2` int DEFAULT NULL COMMENT 'Horas',
  `pfg2_usu2` int DEFAULT NULL COMMENT 'LCP',
  `pfg2_ajustesosa` float(5,2) NOT NULL COMMENT 'Ajuste con sosa',
  PRIMARY KEY (`pfg2_id`),
  KEY `fk_pfg2b_pro` (`pro_id`),
  KEY `fk_pfg2b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9524 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_3_d`
--

DROP TABLE IF EXISTS `procesos_fase_3_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_3_d` (
  `pfd3_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'General',
  `pfd3_ren` int NOT NULL COMMENT 'Numero de renglon',
  `tpa_id` int NOT NULL COMMENT 'Tipo agua',
  `pfd3_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `pfd3_hr_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `pfd3_hr_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `pfd3_hr_ini_mov` time DEFAULT NULL COMMENT 'Hr inicio mov',
  `pfd3_hr_fin_mov` time DEFAULT NULL COMMENT 'Hr fin mov',
  `pfd3_ph` float DEFAULT NULL COMMENT 'PH',
  `pfd3_ce` float DEFAULT NULL COMMENT 'CE',
  `pfd3_ppm` float DEFAULT NULL COMMENT 'PPM',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd3_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Operador captura',
  `pfd3_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd3_id`),
  KEY `fk_pfd3_pro` (`pro_id`),
  KEY `fk_pfd3_tpa` (`tpa_id`),
  KEY `fk_pfd3_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2338 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_3_g`
--

DROP TABLE IF EXISTS `procesos_fase_3_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_3_g` (
  `pfg3_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg3_enzima` float DEFAULT NULL COMMENT 'Enzima',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  PRIMARY KEY (`pfg3_id`),
  KEY `fk_pfg4_pro` (`pro_id`),
  KEY `fk_pfg4_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1720 DEFAULT CHARSET=latin1 COMMENT='General de fase 3';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_3b_d`
--

DROP TABLE IF EXISTS `procesos_fase_3b_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_3b_d` (
  `pfd3_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg3_id` int NOT NULL COMMENT 'Proceso',
  `pfd3_ren` int NOT NULL COMMENT 'Renglon',
  `pfd3_fecha` date NOT NULL,
  `pfd3_hr` time NOT NULL COMMENT 'Hora',
  `pfd3_temp` float(5,2) NOT NULL COMMENT 'PH',
  `pfd3_norm` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd3_sosa` float(5,2) NOT NULL COMMENT 'Acido',
  `pfd3_movimiento` float(5,2) NOT NULL COMMENT 'Movimiento',
  `pfd3_reposo` float(5,2) NOT NULL COMMENT 'Reposo',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd3_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd3_id`),
  KEY `fk_pfd3_pfg3` (`pfg3_id`),
  KEY `fk_pfd3b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_3b_g`
--

DROP TABLE IF EXISTS `procesos_fase_3b_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_3b_g` (
  `pfg3_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg3_temp_ag` decimal(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg3_lts` decimal(5,2) NOT NULL COMMENT 'Kilos de enzima',
  `pfg3_ph` decimal(5,2) NOT NULL COMMENT 'Ph',
  `pfg3_temp` decimal(5,2) NOT NULL COMMENT 'Temp',
  `pfg3_norm` int NOT NULL COMMENT 'Norm',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfg3_hr_totales` int DEFAULT NULL COMMENT 'Horas totales de enzima',
  `pfg3_norm1` decimal(5,2) DEFAULT NULL COMMENT 'Normalidad',
  `pfg3_hr1` float(5,2) DEFAULT NULL COMMENT 'Horas',
  `pfg3_usu1` int DEFAULT NULL COMMENT 'LCP',
  `pfg3_norm2` decimal(5,2) DEFAULT NULL COMMENT 'Normalidad',
  `pfg3_hr2` float(5,2) DEFAULT NULL COMMENT 'Horas',
  `pfg3_usu2` int DEFAULT NULL COMMENT 'LCP',
  PRIMARY KEY (`pfg3_id`),
  KEY `fk_pfg3_pro` (`pro_id`),
  KEY `fk_pfg3_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_4_d`
--

DROP TABLE IF EXISTS `procesos_fase_4_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_4_d` (
  `pfd4_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg4_id` int NOT NULL COMMENT 'Proceso',
  `pfd4_ren` int NOT NULL COMMENT 'Renglon',
  `pfd4_acido` float(5,2) NOT NULL COMMENT 'Hora',
  `pfd4_ph` float(5,2) NOT NULL COMMENT 'PH',
  `pfd4_ph_b` float(5,2) DEFAULT NULL COMMENT 'Ph B',
  `pfd4_temp` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd4_ppm` float NOT NULL COMMENT 'ppm',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd4_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd4_id`),
  KEY `fk_pfd4_pfg4` (`pfg4_id`),
  KEY `fk_pfd4_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=979 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_4_g`
--

DROP TABLE IF EXISTS `procesos_fase_4_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_4_g` (
  `pfg4_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `pfg4_temp_ag` decimal(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg4_temp` decimal(5,2) NOT NULL COMMENT 'Kilos de enzima',
  `pfg4_acido` decimal(5,2) NOT NULL COMMENT 'Ph',
  `pfg4_acido_fuerte` char(2) DEFAULT NULL COMMENT 'Acido Fuerte',
  `pfg4_termina` decimal(5,2) NOT NULL COMMENT 'Temp',
  `pfg4_temp2` decimal(5,2) NOT NULL COMMENT 'Norm',
  `pfg4_cocido_ph` decimal(5,2) DEFAULT NULL COMMENT 'Cocido Ph',
  `pfg4_ce` decimal(5,2) DEFAULT NULL COMMENT 'Ce',
  `pfg4_enzima` decimal(5,2) NOT NULL COMMENT 'Enzima',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  PRIMARY KEY (`pfg4_id`),
  KEY `fk_pfg4_pro` (`pro_id`),
  KEY `fk_pfg4_pe` (`pe_id`),
  KEY `fk_pfg4_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_4b_d`
--

DROP TABLE IF EXISTS `procesos_fase_4b_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_4b_d` (
  `pfd4_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg4_id` int NOT NULL COMMENT 'General',
  `pfd4_ren` int NOT NULL COMMENT 'Numero de renglon',
  `tpa_id` int NOT NULL COMMENT 'Tipo agua',
  `pfd4_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `pfd4_hr_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `pfd4_hr_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `pfd4_hr_ini_mov` time DEFAULT NULL COMMENT 'Hr inicio mov',
  `pfd4_hr_fin_mov` time DEFAULT NULL COMMENT 'Hr fin mov',
  `pfd4_ph` float DEFAULT NULL COMMENT 'PH',
  `pfd4_ce` float DEFAULT NULL COMMENT 'CE',
  `pfd4_ppm` float DEFAULT NULL,
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd4_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Operador captura',
  `pfd4_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd4_id`),
  KEY `fk_pfd4b_pfg4` (`pfg4_id`),
  KEY `fk_pfd4b_tpa` (`tpa_id`),
  KEY `fk_pfd4b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14497 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_4b_g`
--

DROP TABLE IF EXISTS `procesos_fase_4b_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_4b_g` (
  `pfg4_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg4_temp_ag` float(5,2) NOT NULL COMMENT 'Tempereatura',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfg4_horas_reales` float DEFAULT NULL COMMENT 'CE',
  `pfg4_ce` float(5,2) DEFAULT NULL,
  `pfg4_ph` float(5,2) DEFAULT NULL,
  `usu_id` int NOT NULL COMMENT 'Usuario',
  PRIMARY KEY (`pfg4_id`),
  KEY `fk_pfg4b_pro` (`pro_id`),
  KEY `fk_pfg4b_pe` (`pe_id`),
  KEY `fk_pfg4b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7884 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_5_d`
--

DROP TABLE IF EXISTS `procesos_fase_5_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_5_d` (
  `pfd5_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg5_id` int NOT NULL COMMENT 'General',
  `pfd5_ren` int NOT NULL COMMENT 'Numero de renglon',
  `tpa_id` int DEFAULT NULL COMMENT 'Tipo agua',
  `pfd5_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `pfd5_hr_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `pfd5_hr_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `pfd5_hr_ini_mov` time DEFAULT NULL COMMENT 'Hr inicio mov',
  `pfd5_hr_fin_mov` time DEFAULT NULL COMMENT 'Hr fin mov',
  `pfd5_ph` float DEFAULT NULL COMMENT 'PH',
  `pfd5_ce` float DEFAULT NULL COMMENT 'CE',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd5_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Operador captura',
  `pfd5_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd5_id`),
  KEY `fk_pfd5_pfg5` (`pfg5_id`),
  KEY `fk_pfd5_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_5_g`
--

DROP TABLE IF EXISTS `procesos_fase_5_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_5_g` (
  `pfg5_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `tpa_id` int NOT NULL COMMENT 'Tipo de agua',
  `taa_id` int DEFAULT NULL COMMENT 'Agua a',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  PRIMARY KEY (`pfg5_id`),
  KEY `fk_pfg5_pro` (`pro_id`),
  KEY `fk_pfg5_pe` (`pe_id`),
  KEY `fk_pfg5_tpa` (`tpa_id`),
  KEY `fk_pfg5_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_5b_d`
--

DROP TABLE IF EXISTS `procesos_fase_5b_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_5b_d` (
  `pfd5_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg5_id` int NOT NULL COMMENT 'Proceso',
  `pfd5_ren` int NOT NULL COMMENT 'Renglon',
  `pfd5_acido` float(5,2) NOT NULL COMMENT 'Hora',
  `pfd5_ph` float(5,2) NOT NULL COMMENT 'PH',
  `pfd5_ph_b` float(5,2) DEFAULT NULL COMMENT 'Ph B',
  `pfd5_temp` float(5,2) NOT NULL COMMENT 'Sosa',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd5_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd5_id`),
  KEY `fk_pfd5b_pfg5` (`pfg5_id`),
  KEY `fk_pfd5b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60562 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_5b_g`
--

DROP TABLE IF EXISTS `procesos_fase_5b_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_5b_g` (
  `pfg5_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `pfg5_temp_ag` decimal(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg5_temp` decimal(5,2) NOT NULL COMMENT 'Kilos de enzima',
  `pfg5_acido` decimal(5,2) NOT NULL COMMENT 'Ph',
  `pfg5_termina` decimal(5,2) NOT NULL COMMENT 'Temp',
  `pfg5_temp2` decimal(5,2) NOT NULL COMMENT 'Norm',
  `pfg5_acido_fuerte` char(2) DEFAULT NULL COMMENT 'acido fuerte',
  `pfg5_ph_agua` decimal(5,2) DEFAULT NULL COMMENT 'Ph agua',
  `pfg5_ce_agua` decimal(5,2) DEFAULT NULL COMMENT 'ce agua',
  `pfg5_cocido_ph` decimal(5,2) DEFAULT NULL COMMENT 'Cocido Ph',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfg5_ce` decimal(5,2) DEFAULT NULL COMMENT 'Ce',
  `pfg5_hr_reales` decimal(5,2) DEFAULT NULL COMMENT 'horas reales',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  PRIMARY KEY (`pfg5_id`),
  KEY `fk_pfg5b_pro` (`pro_id`),
  KEY `fk_pfg5b_pe` (`pe_id`),
  KEY `fk_pfg5b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7866 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_6_d`
--

DROP TABLE IF EXISTS `procesos_fase_6_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_6_d` (
  `pfd6_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg6_id` int NOT NULL COMMENT 'Proceso',
  `pfd6_ren` int NOT NULL COMMENT 'Renglon',
  `pfd6_acido` float(5,2) NOT NULL COMMENT 'Hora',
  `pfd6_temp` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd6_ph` float(5,2) NOT NULL COMMENT 'PH',
  `pfd6_ce` float(5,2) NOT NULL COMMENT 'Ph B',
  `pfd6_norm` float(5,2) NOT NULL,
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd6_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd6_id`),
  KEY `fk_pfd6_pfg5` (`pfg6_id`),
  KEY `fk_pfd6_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=988 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_6_d2`
--

DROP TABLE IF EXISTS `procesos_fase_6_d2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_6_d2` (
  `pfd6_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg6_id` int NOT NULL COMMENT 'Proceso',
  `pfd6_ren` int NOT NULL,
  `pfd6_ini_mov` time NOT NULL COMMENT 'Inicia movimiento',
  `pfd6_ini_reposo` time NOT NULL COMMENT 'inicia reposo',
  `pfd6_ph` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Ph',
  `pfd6_ce` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Ce',
  `pfd6_norm` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Normalidad',
  `pfd6_temp` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Temperatura',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd6_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha real',
  PRIMARY KEY (`pfd6_id`),
  KEY `fk_pfd62_pfg6` (`pfg6_id`),
  KEY `fk_pfd62_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_6_g`
--

DROP TABLE IF EXISTS `procesos_fase_6_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_6_g` (
  `pfg6_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg6_temp_ag` decimal(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg6_temp` decimal(5,2) NOT NULL COMMENT 'Kilos de enzima',
  `pfg6_acido` decimal(7,2) NOT NULL COMMENT 'Ph',
  `pfg6_temp2` decimal(5,2) NOT NULL COMMENT 'Norm',
  `pfg6_ph` decimal(5,2) NOT NULL COMMENT 'Cocido Ph',
  `pfg6_norm` decimal(5,2) NOT NULL COMMENT 'Normalidad',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfg6_fe_fin` date DEFAULT NULL,
  `pfg6_hr_fin` time DEFAULT NULL,
  `pfg6_hr_totales` decimal(5,2) DEFAULT NULL,
  `usu_sup` int DEFAULT NULL,
  `pfg6_hr_cocido` time DEFAULT NULL,
  `pfg6_ph2` decimal(5,2) DEFAULT NULL,
  `pfg6_ce2` decimal(5,2) DEFAULT NULL,
  `pfg6_ph3` decimal(5,2) DEFAULT NULL,
  `pfg6_ce3` decimal(5,2) DEFAULT NULL,
  `pfg6_acido_diluido` char(2) DEFAULT NULL,
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  PRIMARY KEY (`pfg6_id`),
  KEY `fk_pfg6_pro` (`pro_id`),
  KEY `fk_pfg6_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_6b_d`
--

DROP TABLE IF EXISTS `procesos_fase_6b_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_6b_d` (
  `pfd6_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg6_id` int NOT NULL COMMENT 'General',
  `pfd6_ren` int NOT NULL COMMENT 'Numero de renglon',
  `tpa_id` int DEFAULT NULL COMMENT 'Tipo agua',
  `pfd6_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `pfd6_hr_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `pfd6_hr_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `pfd6_hr_ini_mov` time DEFAULT NULL COMMENT 'Hr inicio mov',
  `pfd6_hr_fin_mov` time DEFAULT NULL COMMENT 'Hr fin mov',
  `pfd6_ph` float DEFAULT NULL COMMENT 'PH',
  `pfd6_ce` float DEFAULT NULL COMMENT 'CE',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd6_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Operador captura',
  `pfd6_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd6_id`),
  KEY `fk_pfd6b_pfg6` (`pfg6_id`),
  KEY `fk_pfd6b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16461 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_6b_g`
--

DROP TABLE IF EXISTS `procesos_fase_6b_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_6b_g` (
  `pfg6_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg6_temp_ag` float(5,2) DEFAULT NULL,
  `taa_id` int DEFAULT NULL COMMENT 'PH anterior',
  `tpa_id` int DEFAULT NULL COMMENT 'Tipo Agua',
  `pfg6_horas_reales` int DEFAULT NULL COMMENT 'Horas reales',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  PRIMARY KEY (`pfg6_id`),
  KEY `fk_pfg6b_pro` (`pro_id`),
  KEY `fk_pfg6b_pe` (`pe_id`),
  KEY `fk_pfg6b_usu` (`usu_id`),
  KEY `fk_pfg6b_taa` (`taa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7687 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_7_d`
--

DROP TABLE IF EXISTS `procesos_fase_7_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_7_d` (
  `pfd7_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg7_id` int NOT NULL COMMENT 'General',
  `pfd7_ren` int NOT NULL COMMENT 'Numero de renglon',
  `tpa_id` int NOT NULL COMMENT 'Tipo agua',
  `pfd7_mov` int NOT NULL COMMENT 'Hora inicio',
  `pfd7_hr_ini_dren` time DEFAULT NULL COMMENT 'Hr inicio mov',
  `pfd7_hr_fin_dren` time DEFAULT NULL COMMENT 'Hr fin mov',
  `pfd7_ph` float DEFAULT NULL COMMENT 'PH',
  `pfd7_ce` float DEFAULT NULL COMMENT 'CE',
  `pfd7_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd7_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Operador captura',
  `pfd7_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd7_id`),
  KEY `fk_pfd7_pfg7` (`pfg7_id`),
  KEY `fk_pfd7_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=521 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_7_g`
--

DROP TABLE IF EXISTS `procesos_fase_7_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_7_g` (
  `pfg7_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg7_hr_totales` int DEFAULT NULL COMMENT 'PH anterior',
  `usu_id` int NOT NULL COMMENT 'operador',
  `usu_sup` int DEFAULT NULL COMMENT 'supervisor',
  `pfg7_hr_totales2` int(2) unsigned zerofill DEFAULT NULL,
  `pfg7_fe_lib_pal` date DEFAULT NULL COMMENT 'liberacion de paleto',
  `pfg7_hr_lib_pal` time DEFAULT NULL COMMENT 'hr liberacion paleto',
  `pfg7_fe_lib_prod` date DEFAULT NULL COMMENT 'fecha liberacion producto',
  `pfg7_hr_lib_prod` time DEFAULT NULL COMMENT 'hora liberacion producto',
  PRIMARY KEY (`pfg7_id`),
  KEY `fk_pfg7_pro` (`pro_id`),
  KEY `fk_pfg7_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_7b_d`
--

DROP TABLE IF EXISTS `procesos_fase_7b_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_7b_d` (
  `pfd7_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg7_id` int NOT NULL COMMENT 'Proceso',
  `pfd7_ren` int NOT NULL COMMENT 'Renglon',
  `pfd7_acido` float(5,2) NOT NULL COMMENT 'Hora',
  `pfd7_temp` float(5,2) NOT NULL COMMENT 'Sosa',
  `pfd7_ph` float(5,2) NOT NULL COMMENT 'PH',
  `pfd7_ce` float(5,2) NOT NULL COMMENT 'Ph B',
  `pfd7_norm` float(5,2) NOT NULL,
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd7_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha y Hora del sistema',
  PRIMARY KEY (`pfd7_id`),
  KEY `fk_pfd7b_pfg7` (`pfg7_id`),
  KEY `fk_pfd7b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56345 DEFAULT CHARSET=latin1 COMMENT='Detalle de la fase 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_7b_d2`
--

DROP TABLE IF EXISTS `procesos_fase_7b_d2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_7b_d2` (
  `pfd7_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg7_id` int NOT NULL COMMENT 'Proceso',
  `pfd7_ren` int NOT NULL,
  `pfd7_ini_mov` time NOT NULL COMMENT 'Inicia movimiento',
  `pfd7_ini_reposo` time NOT NULL COMMENT 'inicia reposo',
  `pfd7_ph` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Ph',
  `pfd7_ce` float(5,2) NOT NULL,
  `pfd7_temp` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Temperatura',
  `pfd7_norm` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Normalidad',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd7_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha real',
  PRIMARY KEY (`pfd7_id`),
  KEY `fk_pfd7b2_pfg7` (`pfg7_id`),
  KEY `fk_pfd7b2_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5677 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_7b_g`
--

DROP TABLE IF EXISTS `procesos_fase_7b_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_7b_g` (
  `pfg7_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `pfg7_temp_ag` decimal(5,2) NOT NULL COMMENT 'Tempereatura',
  `pfg7_temp` decimal(5,2) NOT NULL COMMENT 'Kilos de enzima',
  `pfg7_acido` decimal(7,2) NOT NULL COMMENT 'Ph',
  `pfg7_ph` decimal(5,2) NOT NULL COMMENT 'Temp',
  `pfg7_ce` decimal(5,2) NOT NULL COMMENT 'Norm',
  `pfg7_norm` decimal(5,2) NOT NULL,
  `pfg7_fe_fin` date DEFAULT NULL,
  `pfg7_hr_fin` time DEFAULT NULL,
  `pfg7_hr_totales` decimal(5,2) DEFAULT NULL,
  `pfg7_hr_ini_co` time DEFAULT NULL,
  `pfg7_agua_ph` decimal(5,2) DEFAULT NULL COMMENT 'Ph agua',
  `pfg7_cocido_ph` decimal(5,2) DEFAULT NULL COMMENT 'Cocido Ph',
  `pfg7_agua_ce` decimal(5,2) DEFAULT NULL,
  `pfg7_cocido_ce` decimal(5,2) DEFAULT NULL,
  `pfg7_tem_final` float(5,2) DEFAULT NULL,
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfg7_horas_reales` decimal(5,2) DEFAULT NULL,
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `usu_sup` int DEFAULT NULL,
  `pfg7_acido_diluido` char(2) DEFAULT NULL,
  PRIMARY KEY (`pfg7_id`),
  KEY `fk_pfg7b_pro` (`pro_id`),
  KEY `fk_pfg7b_pe` (`pe_id`),
  KEY `fk_pfg7b_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6622 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_8_d`
--

DROP TABLE IF EXISTS `procesos_fase_8_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_8_d` (
  `pfd8_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pfg8_id` int NOT NULL COMMENT 'Proceso',
  `pfd8_ren` int NOT NULL,
  `tpa_id` int NOT NULL COMMENT 'Tipo agua',
  `pfd8_mov` float(5,2) NOT NULL,
  `pfd8_ini_llenado` time DEFAULT NULL COMMENT 'Inicia llenado',
  `pfd8_fin_llenado` time DEFAULT NULL COMMENT 'Fin llenado',
  `pfd8_ini_dren` time DEFAULT NULL COMMENT 'Inicia movimiento',
  `pfd8_fin_dren` time DEFAULT NULL COMMENT 'inicia reposo',
  `pfd8_ph` float DEFAULT NULL COMMENT 'Ph',
  `pfd8_ce` float DEFAULT NULL COMMENT 'Normalidad',
  `pfd8_temp` float DEFAULT NULL COMMENT 'Temperatura',
  `taa_id` int DEFAULT NULL COMMENT 'agua a',
  `pfd8_observaciones` varchar(100) DEFAULT NULL COMMENT 'Observaciones',
  `usu_id` int NOT NULL COMMENT 'Usuario',
  `pfd8_fe_hr_sys` datetime NOT NULL COMMENT 'Fecha real',
  PRIMARY KEY (`pfd8_id`),
  KEY `fk_pfd8_pfg8` (`pfg8_id`),
  KEY `fk_pfd8_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20762 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_fase_8_g`
--

DROP TABLE IF EXISTS `procesos_fase_8_g`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_fase_8_g` (
  `pfg8_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL,
  `pfg8_hr_totales` int DEFAULT NULL COMMENT 'PH anterior',
  `usu_id` int NOT NULL COMMENT 'operador',
  `usu_sup` int DEFAULT NULL COMMENT 'supervisor',
  `pfg8_hr_totales2` int DEFAULT NULL,
  `pfg8_fe_lib_prod` date DEFAULT NULL COMMENT 'fecha liberacion producto',
  `pfg8_hr_lib_prod` time DEFAULT NULL COMMENT 'hora liberacion producto',
  PRIMARY KEY (`pfg8_id`),
  KEY `fk_pfg8_pro` (`pro_id`),
  KEY `fk_pfg8_pe` (`pe_id`),
  KEY `fk_pfg8_usu` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7471 DEFAULT CHARSET=latin1 COMMENT='General de fase 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_juntos`
--

DROP TABLE IF EXISTS `procesos_juntos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_juntos` (
  `pj_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `prop_id` int NOT NULL COMMENT 'subproceso',
  `pro_id` int NOT NULL COMMENT 'proceso',
  `ep_id` int NOT NULL COMMENT 'equipo destino',
  PRIMARY KEY (`pj_id`),
  KEY `fk_ppd_prop` (`prop_id`),
  KEY `fk_ppd_pro` (`pro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_liberacion`
--

DROP TABLE IF EXISTS `procesos_liberacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_liberacion` (
  `prol_id` int NOT NULL AUTO_INCREMENT COMMENT 'Proceso',
  `usu_id` int NOT NULL COMMENT 'Usurio',
  `pro_id` int DEFAULT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `prol_hr_totales` float NOT NULL COMMENT 'Hr totales',
  `prol_ph` float DEFAULT NULL COMMENT 'Ph',
  `prol_ce` float DEFAULT NULL COMMENT 'Ce',
  `prol_color` varchar(3) DEFAULT NULL COMMENT 'Color',
  `prol_adelgasamiento` char(2) DEFAULT NULL COMMENT 'Adelgasamiento',
  `prol_peroxido` int DEFAULT NULL COMMENT 'Peroxido',
  `extractibilidad` float(5,2) DEFAULT NULL,
  `prol_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha y hora',
  `prol_extrac2doacido` float(5,2) NOT NULL COMMENT 'Extractibilidad 2do acido',
  PRIMARY KEY (`prol_id`),
  KEY `fk_prol_pro` (`pro_id`),
  KEY `fk_prol_usu` (`usu_id`),
  KEY `fk_prol_pe` (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24366 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_liberacion_b`
--

DROP TABLE IF EXISTS `procesos_liberacion_b`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_liberacion_b` (
  `prol_id` int NOT NULL AUTO_INCREMENT COMMENT 'Proceso',
  `usu_id` int NOT NULL COMMENT 'Usurio',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `prol_fecha` date NOT NULL COMMENT 'Fecha',
  `prol_hora` time DEFAULT NULL COMMENT 'Hora',
  `prol_hr_totales` float(5,2) DEFAULT NULL COMMENT 'horas totales lib',
  `prol_cocido_ph1` float(5,2) DEFAULT NULL COMMENT 'ph1',
  `prol_ce1` float(5,2) DEFAULT NULL COMMENT 'Ce1',
  `prol_cocido_ph2` float(5,2) DEFAULT NULL COMMENT 'Ph2',
  `prol_ce2` float(5,2) DEFAULT NULL COMMENT 'Ce2',
  `prol_cocido_lib` float(5,2) DEFAULT NULL COMMENT 'cocido liberacion',
  `prol_color_caldo` varchar(3) DEFAULT NULL COMMENT 'color de caldo',
  `prol_color` varchar(3) DEFAULT NULL,
  `prol_solides` float(5,2) DEFAULT NULL,
  `prol_por_extrac` float(5,2) DEFAULT NULL,
  `prol_observaciones` varchar(250) DEFAULT NULL COMMENT 'Observaciones',
  PRIMARY KEY (`prol_id`),
  KEY `fk_prolb_usu` (`usu_id`),
  KEY `fk_prolb_pro` (`pro_id`),
  KEY `fk_prolb_pe` (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6826 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_liberacion_b_cocidos`
--

DROP TABLE IF EXISTS `procesos_liberacion_b_cocidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_liberacion_b_cocidos` (
  `prol_id` int NOT NULL COMMENT 'Proceso liberaciÔö£Ôöén',
  `prol_ren` int NOT NULL COMMENT 'renglon',
  `prol_cocido` float(9,2) NOT NULL COMMENT 'Cocido',
  `prol_ce` float(9,2) NOT NULL COMMENT 'CE',
  `prol_cuero_sob` float(9,2) NOT NULL COMMENT 'Cuero sobrante',
  `prol_por_extrac` float(9,2) NOT NULL COMMENT 'Porcentaje extractibilidad',
  `prol_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha y hora',
  UNIQUE KEY `idx_pro_lib_ren` (`prol_id`,`prol_ren`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cocidos de procesos de liberaciÔö£Ôöén';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_materiales`
--

DROP TABLE IF EXISTS `procesos_materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_materiales` (
  `pma_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `inv_id` int NOT NULL COMMENT 'Clave inventario',
  `mat_id` int NOT NULL COMMENT 'Material',
  `pma_kg` float NOT NULL COMMENT 'Kilos',
  `pma_fe_entrada` date NOT NULL COMMENT 'Fecha entrada',
  `pma_fe_entrada_maquila` datetime DEFAULT NULL,
  PRIMARY KEY (`pma_id`),
  KEY `fk_pma_inv` (`inv_id`),
  KEY `fk_pma_mat` (`mat_id`),
  KEY `fk_pma_pro` (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31628 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_paletos`
--

DROP TABLE IF EXISTS `procesos_paletos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_paletos` (
  `prop_id` int NOT NULL AUTO_INCREMENT COMMENT 'Proceso paleto',
  `pp_id` int NOT NULL COMMENT 'Paleto',
  `prop_estatus` tinyint NOT NULL COMMENT '1 Pendiente 2 Terminado',
  `prop_directo` tinyint(1) NOT NULL DEFAULT '0',
  `pt_id` int DEFAULT NULL COMMENT 'Tipo de preparcion',
  PRIMARY KEY (`prop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5037 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_paletos_d`
--

DROP TABLE IF EXISTS `procesos_paletos_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_paletos_d` (
  `prod_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `prop_id` int NOT NULL COMMENT 'pro. paleto',
  `pro_id` int NOT NULL COMMENT 'proceso',
  PRIMARY KEY (`prod_id`),
  KEY `fk_ppd_prop` (`prop_id`),
  KEY `fk_ppd_pro` (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8733 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_paletos_hist`
--

DROP TABLE IF EXISTS `procesos_paletos_hist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_paletos_hist` (
  `pph_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `prop_id` int NOT NULL COMMENT 'Proceso paleto',
  `pp_id` int NOT NULL COMMENT 'Paleto',
  PRIMARY KEY (`pph_id`),
  KEY `fk_pph_prop` (`prop_id`),
  KEY `fk_pph_pp` (`pp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4243 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procesos_renglones`
--

DROP TABLE IF EXISTS `procesos_renglones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesos_renglones` (
  `pr_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pro_id` int NOT NULL COMMENT 'Proceso',
  `pe_id` int NOT NULL COMMENT 'Etapa',
  `pr_ren` int NOT NULL COMMENT 'RenglÔö£Ôöén',
  PRIMARY KEY (`pr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1555 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `producto_externo`
--

DROP TABLE IF EXISTS `producto_externo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_externo` (
  `pe_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID DEL PRODUCTO COMPRADO',
  `pres_id` int NOT NULL COMMENT 'ID DE PRESENTACION',
  `pe_lote` varchar(30) NOT NULL COMMENT 'LOTE DEL PRODUCTO',
  `pe_existencia_inicial` decimal(7,2) DEFAULT NULL,
  `pe_existencia_real` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`pe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `prv_id` int NOT NULL AUTO_INCREMENT,
  `prv_nombre` varchar(25) NOT NULL,
  `prv_nom_comercial` varchar(45) DEFAULT NULL COMMENT 'Nombre comercial',
  `prv_rfc` varchar(15) DEFAULT NULL,
  `prv_telefono` varchar(20) NOT NULL,
  `prv_email` varchar(35) NOT NULL,
  `prv_contacto` varchar(35) DEFAULT NULL,
  `prv_tipo` char(1) NOT NULL DEFAULT 'L' COMMENT 'L, local, E extranjero',
  `prv_calle` varchar(30) NOT NULL,
  `prv_numero` varchar(10) NOT NULL,
  `prv_colonia` varchar(30) NOT NULL,
  `est_id` int NOT NULL,
  `ciu_id` int NOT NULL,
  `prv_cp` int DEFAULT NULL,
  `prv_est` char(1) NOT NULL DEFAULT 'A',
  `prv_ban` tinyint NOT NULL DEFAULT '0' COMMENT '0 normal 1 especial',
  `prv_ncorto` varchar(15) NOT NULL DEFAULT '0',
  `prv_mql` char(1) NOT NULL COMMENT 'S: Maquila, N: Materia Prima, C: Combinado (Mql y Mp)',
  PRIMARY KEY (`prv_id`),
  KEY `fk_prv_ciu` (`ciu_id`),
  KEY `fk_prv_est` (`est_id`),
  KEY `fk_prv_esta` (`prv_est`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quimicos`
--

DROP TABLE IF EXISTS `quimicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quimicos` (
  `quimico_id` int NOT NULL AUTO_INCREMENT,
  `quimico_descripcion` varchar(30) NOT NULL,
  `quimico_est` char(1) NOT NULL,
  PRIMARY KEY (`quimico_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quimicos_almacen`
--

DROP TABLE IF EXISTS `quimicos_almacen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quimicos_almacen` (
  `qa_id` int NOT NULL AUTO_INCREMENT,
  `usu_id` int NOT NULL COMMENT 'operador',
  `qa_fe_entrega` datetime NOT NULL,
  `quim_id` int NOT NULL,
  `qa_lote` varchar(40) NOT NULL,
  `qm_cant_entrega` float(8,2) NOT NULL,
  `um_id` int NOT NULL,
  PRIMARY KEY (`qa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quimicos_etapas`
--

DROP TABLE IF EXISTS `quimicos_etapas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quimicos_etapas` (
  `quim_id` int NOT NULL AUTO_INCREMENT,
  `quimico_id` int NOT NULL,
  `quim_lote` varchar(40) NOT NULL,
  `quim_litros` int NOT NULL,
  `pe_id` int NOT NULL COMMENT 'etapa',
  `pro_id` int NOT NULL COMMENT 'proceso',
  `usu_id` int NOT NULL COMMENT 'usuario',
  `quim_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha y hora',
  PRIMARY KEY (`quim_id`),
  KEY `pro_id` (`pro_id`),
  KEY `pe_id` (`pe_id`),
  KEY `quimico_id` (`quimico_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10941 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `racks_posiciones_clientes`
--

DROP TABLE IF EXISTS `racks_posiciones_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `racks_posiciones_clientes` (
  `rpc_id` int NOT NULL AUTO_INCREMENT,
  `niv_id` int NOT NULL COMMENT 'ID de la posiciÔö£Ôöén en el rack',
  `cte_id` int NOT NULL COMMENT 'ID del cliente',
  PRIMARY KEY (`rpc_id`),
  KEY `fk_rpc_pos` (`niv_id`),
  KEY `fk_rpc_cte` (`cte_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `remision_detalle`
--

DROP TABLE IF EXISTS `remision_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remision_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `remision_id` int NOT NULL,
  `producto_cve` varchar(30) DEFAULT NULL,
  `producto_descripcion` varchar(150) DEFAULT NULL,
  `promocion` tinyint(1) DEFAULT '0',
  `cantidad` decimal(12,2) DEFAULT NULL,
  `precio_kg` decimal(12,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `remision_id` (`remision_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `remision_detalle_lote`
--

DROP TABLE IF EXISTS `remision_detalle_lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remision_detalle_lote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `detalle_id` int NOT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `detalle_id` (`detalle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `remisiones`
--

DROP TABLE IF EXISTS `remisiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remisiones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `remision` varchar(20) NOT NULL,
  `vendedor_nombre` varchar(100) DEFAULT NULL,
  `cliente_nombre` varchar(150) DEFAULT NULL,
  `ubicacion_cliente` varchar(100) DEFAULT NULL,
  `tipo_cliente` varchar(10) DEFAULT NULL,
  `tipo_venta` enum('Comercial','Industrial') NOT NULL,
  `total_remision` decimal(15,2) DEFAULT NULL,
  `total_credito` decimal(15,2) DEFAULT NULL,
  `total_real` decimal(15,2) DEFAULT NULL,
  `fecha_remision` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remision` (`remision`)
) ENGINE=MyISAM AUTO_INCREMENT=773 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_bloom`
--

DROP TABLE IF EXISTS `rev_bloom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_bloom` (
  `blo_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `blo_ini` int NOT NULL COMMENT 'Bloom inicial',
  `blo_fin` int NOT NULL COMMENT 'Bloom final',
  `blo_etiqueta` varchar(25) NOT NULL,
  `blo_estatus` char(1) NOT NULL DEFAULT 'A' COMMENT 'Activo, Baja',
  PRIMARY KEY (`blo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_calidad`
--

DROP TABLE IF EXISTS `rev_calidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_calidad` (
  `cal_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `cal_descripcion` varchar(15) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  `cal_color` varchar(10) DEFAULT NULL COMMENT 'color de la calidad',
  PRIMARY KEY (`cal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_calidad_rango`
--

DROP TABLE IF EXISTS `rev_calidad_rango`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_calidad_rango` (
  `cr_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `blo_ini` decimal(10,2) NOT NULL COMMENT 'bloom inicial',
  `blo_fin` decimal(10,2) NOT NULL COMMENT 'bloom final',
  `vis_ini` decimal(10,2) NOT NULL COMMENT 'viscosidad inicial',
  `vis_fin` decimal(10,2) NOT NULL COMMENT 'viscosidad final',
  `cal_id` int NOT NULL COMMENT 'Calidad',
  PRIMARY KEY (`cr_id`),
  KEY `fk_cr_cal` (`cal_id`),
  CONSTRAINT `fk_cr_cal` FOREIGN KEY (`cal_id`) REFERENCES `rev_calidad` (`cal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_clientes`
--

DROP TABLE IF EXISTS `rev_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_clientes` (
  `cte_id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de la tabla',
  `cte_rfc` varchar(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL COMMENT 'RFC del cliente',
  `cte_razon_social` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Notas ',
  `cte_nombre` varchar(255) NOT NULL COMMENT 'NOMBRE DEL CLIENTE',
  `cte_estatus` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT 'estatus del cliente',
  `cte_ubicacion` varchar(255) DEFAULT NULL COMMENT 'Ubicacion del cliente',
  `cte_tipo` enum('Comercial','Industrial','Ambos') DEFAULT NULL,
  `cte_clasificacion` enum('EX A','EX AA','EX AAA','A','AA','AAA','B','C') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cte_estado` varchar(50) DEFAULT NULL,
  `cte_tipo_bloom` int DEFAULT NULL COMMENT 'Tipo de bloom que se envia',
  `cte_bloom_min` int DEFAULT NULL COMMENT 'Este bloom es el que aparecera en el campo de bloom en el certificado',
  `cte_direccion_fiscal` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`cte_id`)
) ENGINE=InnoDB AUTO_INCREMENT=500 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_clientes_direcciones_entrega`
--

DROP TABLE IF EXISTS `rev_clientes_direcciones_entrega`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_clientes_direcciones_entrega` (
  `id` int NOT NULL AUTO_INCREMENT,
  `direccion_entrega` varchar(255) DEFAULT NULL,
  `cte_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_equipos`
--

DROP TABLE IF EXISTS `rev_equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_equipos` (
  `e_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `e_nombre` varchar(50) NOT NULL COMMENT 'Nombre',
  `e_estatus` int NOT NULL DEFAULT '1' COMMENT '1: Disponible, 2: Ocupado, 3: Descompuesto',
  PRIMARY KEY (`e_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_kardex`
--

DROP TABLE IF EXISTS `rev_kardex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_kardex` (
  `id_kardex` int NOT NULL AUTO_INCREMENT,
  `kar_fecha` date NOT NULL DEFAULT (curdate()),
  `kar_total_entrada` decimal(10,2) NOT NULL,
  `kar_total_salida` decimal(10,2) NOT NULL,
  `kar_inventario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_kardex`)
) ENGINE=MyISAM AUTO_INCREMENT=399 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_mezcla`
--

DROP TABLE IF EXISTS `rev_mezcla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_mezcla` (
  `mez_id` int NOT NULL AUTO_INCREMENT,
  `mez_folio` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mez_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usu_id` int NOT NULL,
  `mez_estatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 pendiente, 1 proceso, 2 terminado',
  `cal_id` int DEFAULT NULL COMMENT 'Calidad',
  `mez_hora_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `mez_hora_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `mez_imanes_limpios` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Imanes limpios?',
  `mez_sacos_limpios` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Sacos limpios?',
  `mez_libre_sobrantes` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Libre sobrantes?',
  `mez_mezcladora` tinyint(1) DEFAULT NULL COMMENT 'Mezcladora',
  `mez_kilos` decimal(7,2) DEFAULT '5000.00' COMMENT 'kilos mezcla',
  PRIMARY KEY (`mez_id`),
  KEY `fk_rev_usu` (`usu_id`),
  CONSTRAINT `rev_mezcla_ibfk_1` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_mezclas_tarimas`
--

DROP TABLE IF EXISTS `rev_mezclas_tarimas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_mezclas_tarimas` (
  `rm_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `mez_id` int NOT NULL COMMENT 'Revoltura',
  `tar_id` int NOT NULL COMMENT 'Tarima',
  PRIMARY KEY (`rm_id`),
  KEY `fk_rt_rev` (`mez_id`),
  KEY `fk_rt_tar` (`tar_id`),
  CONSTRAINT `rev_mezclas_tarimas_ibfk_2` FOREIGN KEY (`tar_id`) REFERENCES `rev_tarimas` (`tar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_nivel_posicion`
--

DROP TABLE IF EXISTS `rev_nivel_posicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_nivel_posicion` (
  `niv_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave Ôö£Ôòænica de la ubicaciÔö£Ôöén',
  `rac_id` int NOT NULL COMMENT 'Rack al que pertenece',
  `niv_codigo` varchar(5) NOT NULL COMMENT 'CÔö£Ôöédigo de la posiciÔö£Ôöén (Ej: A1, B2, C3)',
  `niv_ocupado` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Ocupado, 0 = Desocupado',
  `cte_id` int DEFAULT NULL COMMENT 'Si la posiciÔö£Ôöén es exclusiva de un cliente',
  PRIMARY KEY (`niv_id`),
  KEY `rac_id` (`rac_id`),
  KEY `id_cte_id` (`cte_id`),
  CONSTRAINT `fk_niv_cte` FOREIGN KEY (`cte_id`) REFERENCES `rev_clientes` (`cte_id`) ON DELETE SET NULL,
  CONSTRAINT `rev_nivel_posicion_ibfk_1` FOREIGN KEY (`rac_id`) REFERENCES `rev_racks` (`rac_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=813 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_nivel_posicion_detalle`
--

DROP TABLE IF EXISTS `rev_nivel_posicion_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_nivel_posicion_detalle` (
  `nvd_id` int NOT NULL AUTO_INCREMENT,
  `niv_id` int NOT NULL,
  `tipo` enum('tarima','general','cliente','revoltura') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'tarima',
  `tar_id` int DEFAULT NULL,
  `rr_id` int DEFAULT NULL,
  `rrc_id` int DEFAULT NULL,
  `cantidad` decimal(7,2) NOT NULL DEFAULT '0.00',
  `rev_id` int DEFAULT NULL,
  PRIMARY KEY (`nvd_id`),
  KEY `id_niv` (`niv_id`),
  KEY `id_tar` (`tar_id`),
  KEY `id_rr` (`rr_id`),
  KEY `id_rrc` (`rrc_id`),
  CONSTRAINT `fk_nvd_niv` FOREIGN KEY (`niv_id`) REFERENCES `rev_nivel_posicion` (`niv_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_nvd_rr` FOREIGN KEY (`rr_id`) REFERENCES `rev_revolturas_pt` (`rr_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_nvd_tar` FOREIGN KEY (`tar_id`) REFERENCES `rev_tarimas` (`tar_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17449 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_nivel_posicion_empaque`
--

DROP TABLE IF EXISTS `rev_nivel_posicion_empaque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_nivel_posicion_empaque` (
  `npe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave Ôö£Ôòænica de la ubicaciÔö£Ôöén de empaque',
  `niv_id` int NOT NULL COMMENT 'ID de la posiciÔö£Ôöén en el rack',
  `rr_id` int NOT NULL COMMENT 'ID del empaque asignado',
  PRIMARY KEY (`npe_id`),
  KEY `niv_id` (`niv_id`),
  KEY `rr_id` (`rr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_nivel_posicion_empaque_cliente`
--

DROP TABLE IF EXISTS `rev_nivel_posicion_empaque_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_nivel_posicion_empaque_cliente` (
  `npe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave Ôö£Ôòænica de la ubicaciÔö£Ôöén de empaque',
  `niv_id` int NOT NULL COMMENT 'ID de la posiciÔö£Ôöén en el rack',
  `rrc_id` int NOT NULL COMMENT 'ID del empaque asignado a un cliente',
  `cte_id` int NOT NULL COMMENT 'Cliente dueÔö£ÔûÆo del empaque',
  PRIMARY KEY (`npe_id`),
  KEY `niv_id` (`niv_id`),
  KEY `rrc_id` (`rrc_id`),
  KEY `cte_id` (`cte_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_orden_embarque`
--

DROP TABLE IF EXISTS `rev_orden_embarque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_orden_embarque` (
  `oe_id` int NOT NULL AUTO_INCREMENT,
  `oe_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cte_id` int NOT NULL,
  `oe_estado` enum('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO','COMPLETADA','CANCELADA','FACTURADA') DEFAULT 'PENDIENTE' COMMENT 'Estado de la orden',
  `remision_ban` tinyint(1) DEFAULT NULL,
  `tarimas_liberadas` int DEFAULT NULL,
  PRIMARY KEY (`oe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1589 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_orden_embarque_detalle`
--

DROP TABLE IF EXISTS `rev_orden_embarque_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_orden_embarque_detalle` (
  `oed_id` int NOT NULL AUTO_INCREMENT,
  `oe_id` int NOT NULL,
  `oed_tipo_producto` enum('REVOLTURA','EXTERNO') NOT NULL DEFAULT 'REVOLTURA',
  `rrc_id` int DEFAULT NULL,
  `rr_id` int DEFAULT NULL,
  `pe_id` int DEFAULT NULL,
  `cantidad` decimal(7,2) NOT NULL,
  `bloom_vendido` int DEFAULT NULL COMMENT 'Bloom que se vende',
  PRIMARY KEY (`oed_id`),
  KEY `oe_id` (`oe_id`),
  KEY `idx_oed_tipo_producto` (`oed_tipo_producto`),
  KEY `idx_oed_pe_id` (`pe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3053 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_orden_empaque`
--

DROP TABLE IF EXISTS `rev_orden_empaque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_orden_empaque` (
  `roe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave de la orden',
  `roe_fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creaciÔö£Ôöén',
  `roe_estado` enum('PENDIENTE','PROCESO','COMPLETADA','CANCELADA') DEFAULT 'PENDIENTE' COMMENT 'Estado de la orden',
  PRIMARY KEY (`roe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1268 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_orden_empaque_detalle`
--

DROP TABLE IF EXISTS `rev_orden_empaque_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_orden_empaque_detalle` (
  `roed_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave del detalle',
  `roe_id` int NOT NULL COMMENT 'Orden de empaque',
  `rev_id` int NOT NULL COMMENT 'Revoltura',
  `pres_id` int NOT NULL COMMENT 'PresentaciÔö£Ôöén',
  `roed_cantidad` decimal(7,2) NOT NULL COMMENT 'Cantidad a empacar',
  `roed_cantidad_capturada` decimal(7,2) DEFAULT '0.00' COMMENT 'Cantidad capturada hasta el momento',
  `roed_notas` text COMMENT 'NOTAS DEL EMPACADO',
  PRIMARY KEY (`roed_id`),
  KEY `fk_roed_roe` (`roe_id`),
  KEY `fk_roed_rev` (`rev_id`),
  KEY `fk_roed_pres` (`pres_id`),
  CONSTRAINT `fk_roed_pres` FOREIGN KEY (`pres_id`) REFERENCES `rev_presentacion` (`pres_id`),
  CONSTRAINT `fk_roed_rev` FOREIGN KEY (`rev_id`) REFERENCES `rev_revolturas` (`rev_id`),
  CONSTRAINT `fk_roed_roe` FOREIGN KEY (`roe_id`) REFERENCES `rev_orden_empaque` (`roe_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1339 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_parametros`
--

DROP TABLE IF EXISTS `rev_parametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_parametros` (
  `rp_id` int NOT NULL AUTO_INCREMENT COMMENT 'clave',
  `rp_parametro` varchar(25) NOT NULL COMMENT 'descripcion de parametro',
  `rp_inicio` decimal(7,2) NOT NULL COMMENT 'rango inicial',
  `rp_fin` decimal(7,2) NOT NULL COMMENT 'rango final',
  `rp_campo` varchar(20) DEFAULT NULL COMMENT 'Nombre de campo',
  `rp_tipo` char(1) DEFAULT NULL COMMENT 'Tipo de dato del parametro F -> FLOAT/DECIMAL B -> BOOLEAN C -> Calidad',
  PRIMARY KEY (`rp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_presentacion`
--

DROP TABLE IF EXISTS `rev_presentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_presentacion` (
  `pres_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `pres_descrip` varchar(30) NOT NULL COMMENT 'Descripcion',
  `pres_kg` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Kilos',
  `pres_estatus` char(1) NOT NULL DEFAULT 'A' COMMENT 'Activo, Baja',
  PRIMARY KEY (`pres_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_racks`
--

DROP TABLE IF EXISTS `rev_racks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_racks` (
  `rac_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID Ôö£Ôòænico del rack',
  `rac_descripcion` varchar(30) NOT NULL COMMENT 'Nombre o descripciÔö£Ôöén',
  `rac_color` varchar(10) DEFAULT NULL COMMENT 'Color del rack',
  `rac_zona` varchar(20) DEFAULT NULL COMMENT 'Zona dentro del almacÔö£┬«n',
  `rac_estatus` char(1) NOT NULL DEFAULT 'A' COMMENT 'A = Activo, B = Baja',
  PRIMARY KEY (`rac_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_receta`
--

DROP TABLE IF EXISTS `rev_receta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_receta` (
  `rre_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rre_descripcion` varchar(30) DEFAULT NULL COMMENT 'Descripcion de la receta',
  `rre_estatus` char(1) DEFAULT 'A' COMMENT 'Alta: A Baja: B',
  `cte_id` int DEFAULT NULL COMMENT 'ID Cliente',
  PRIMARY KEY (`rre_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_receta_detalle`
--

DROP TABLE IF EXISTS `rev_receta_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_receta_detalle` (
  `rrd_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rrd_no_tarima` int DEFAULT NULL,
  `rp_id` int DEFAULT NULL,
  `rp_valor` varchar(10) DEFAULT NULL,
  `rre_id` int DEFAULT NULL COMMENT 'Id de la receta',
  `rrd_signo` char(4) DEFAULT NULL COMMENT 'SIGNO DE COMPARACION',
  PRIMARY KEY (`rrd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_revolturas`
--

DROP TABLE IF EXISTS `rev_revolturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_revolturas` (
  `rev_id` int NOT NULL AUTO_INCREMENT,
  `rev_folio` varchar(10) DEFAULT NULL,
  `rev_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rev_fecha_procesamiento` datetime DEFAULT NULL,
  `usu_id` int NOT NULL,
  `rev_estatus` int NOT NULL DEFAULT '0' COMMENT '0 pendiente, 1 proceso, 2 terminado',
  `rev_color` decimal(5,2) DEFAULT NULL COMMENT 'Color',
  `rev_redox` decimal(5,2) DEFAULT NULL COMMENT 'Redox',
  `rev_ph` decimal(5,2) DEFAULT NULL COMMENT 'ph',
  `rev_trans` decimal(5,2) DEFAULT NULL COMMENT 'Trans',
  `rev_porcentaje_t` decimal(5,2) DEFAULT NULL,
  `rev_bloom` decimal(5,2) DEFAULT NULL COMMENT 'Bloom',
  `rev_viscosidad` decimal(5,2) DEFAULT NULL COMMENT 'Viscosidad',
  `rev_olor` tinyint DEFAULT NULL COMMENT 'olor',
  `rev_ntu` decimal(7,2) DEFAULT NULL COMMENT 'ntu',
  `rev_humedad` decimal(7,2) DEFAULT NULL COMMENT 'humedad',
  `rev_cenizas` decimal(7,2) DEFAULT NULL COMMENT 'cenizas',
  `rev_ce` decimal(7,2) DEFAULT NULL COMMENT 'Conductividad electrica',
  `rev_fino` decimal(7,2) DEFAULT NULL COMMENT 'fino',
  `rev_pe_1kg` decimal(7,2) DEFAULT NULL COMMENT 'particulas extraÔö£ÔûÆas en 1 kilo',
  `rev_par_extr` decimal(7,2) DEFAULT NULL COMMENT 'particulas extraÔö£ÔûÆas',
  `rev_par_ind` decimal(7,2) DEFAULT NULL COMMENT 'particulas indisolubles',
  `rev_hidratacion` varchar(5) DEFAULT NULL COMMENT 'hidrataciÔö£Ôöén',
  `rev_malla_30` decimal(5,2) DEFAULT NULL COMMENT 'Malla 30',
  `rev_malla_45` decimal(5,2) DEFAULT NULL COMMENT 'Malla 45',
  `rev_malla_60` decimal(5,2) DEFAULT NULL COMMENT 'MALLA 60',
  `rev_malla_100` decimal(5,2) DEFAULT NULL COMMENT 'MALLA 100',
  `rev_malla_200` decimal(5,2) DEFAULT NULL COMMENT 'MALLA 200',
  `rev_malla_base` decimal(5,2) DEFAULT NULL COMMENT 'MALLA BASE',
  `cal_id` int DEFAULT NULL COMMENT 'Calidad',
  `rev_hora_ini` time DEFAULT NULL COMMENT 'Hora inicio',
  `rev_hora_fin` time DEFAULT NULL COMMENT 'Hora fin',
  `rev_imanes_limpios` char(1) DEFAULT NULL COMMENT 'Imanes limpios?',
  `rev_sacos_limpios` char(1) DEFAULT NULL COMMENT 'Sacos limpios?',
  `rev_libre_sobrantes` char(1) DEFAULT NULL COMMENT 'Libre sobrantes?',
  `rev_mezcladora` tinyint(1) DEFAULT NULL COMMENT 'Mezcladora',
  `rev_fe_param` datetime NOT NULL COMMENT 'Fecha de parametros',
  `rev_rechazado` char(1) DEFAULT NULL COMMENT 'A: Aceptado R: Rechazado',
  `rev_fecha_empacado` datetime DEFAULT NULL COMMENT 'Fecha de empacado',
  `rev_kilos` decimal(7,2) DEFAULT '5000.00' COMMENT 'Total de kilos',
  `rev_factura` varchar(10) DEFAULT NULL COMMENT 'Numero de factura',
  `rev_teo_bloom` decimal(5,2) DEFAULT NULL COMMENT 'Bloom teorico',
  `rev_teo_viscosidad` decimal(5,2) DEFAULT NULL COMMENT 'Viscosidad teorica',
  `rev_teo_calidad` int DEFAULT NULL COMMENT 'Calidad teorica',
  `rev_count_etiquetado` char(1) DEFAULT NULL COMMENT 'Contador de etiquetas por revoltura',
  `rev_teo_cliente` int DEFAULT NULL COMMENT 'Cliente teorico',
  `rev_merma` decimal(7,2) DEFAULT '0.00' COMMENT 'Merma de la revoltura',
  PRIMARY KEY (`rev_id`),
  KEY `fk_rev_usu` (`usu_id`),
  CONSTRAINT `fk_rev_usu` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1897 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_revolturas_pt`
--

DROP TABLE IF EXISTS `rev_revolturas_pt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_revolturas_pt` (
  `rr_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rev_id` int NOT NULL COMMENT 'Revoltura',
  `pres_id` int NOT NULL COMMENT 'PresentaciÔö£Ôöén',
  `roed_id` int DEFAULT NULL COMMENT 'Detalle de orden de empaque que originó este PT',
  `rr_ext_inicial` decimal(7,2) DEFAULT '0.00' COMMENT 'Existencia Inicial',
  `rr_ext_real` decimal(7,2) DEFAULT NULL COMMENT 'Existencia Real',
  PRIMARY KEY (`rr_id`),
  UNIQUE KEY `uk_rr_roed_id` (`roed_id`),
  KEY `fk_rr_rev` (`rev_id`),
  KEY `fk_rr_pres` (`pres_id`),
  KEY `idx_rr_roed_id` (`roed_id`),
  CONSTRAINT `fk_rr_pres` FOREIGN KEY (`pres_id`) REFERENCES `rev_presentacion` (`pres_id`),
  CONSTRAINT `fk_rr_rev` FOREIGN KEY (`rev_id`) REFERENCES `rev_revolturas` (`rev_id`)
) ENGINE=InnoDB AUTO_INCREMENT=880 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_revolturas_pt_cliente`
--

DROP TABLE IF EXISTS `rev_revolturas_pt_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_revolturas_pt_cliente` (
  `rrc_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rev_id` int NOT NULL COMMENT 'Revoltura',
  `pres_id` int NOT NULL COMMENT 'PresentaciÔö£Ôöén',
  `roed_id` int DEFAULT NULL COMMENT 'Detalle de orden de empaque que originó este PT cliente',
  `rrc_ext_inicial` decimal(7,2) DEFAULT '0.00' COMMENT 'Existencia Inicial',
  `rrc_ext_real` decimal(7,2) DEFAULT NULL COMMENT 'Existencia Real',
  `cte_id` int NOT NULL COMMENT 'Cliente',
  PRIMARY KEY (`rrc_id`),
  UNIQUE KEY `uk_rrc_roed_id` (`roed_id`),
  KEY `fk_rrc_rev` (`rev_id`),
  KEY `fk_rrc_pres` (`pres_id`),
  KEY `fk_rrc_cte` (`cte_id`),
  KEY `idx_rrc_roed_id` (`roed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1432 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_revolturas_pt_facturas`
--

DROP TABLE IF EXISTS `rev_revolturas_pt_facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_revolturas_pt_facturas` (
  `fe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rr_id` int DEFAULT NULL COMMENT 'Clave empacado general',
  `fe_factura` varchar(50) NOT NULL,
  `fe_cantidad` decimal(7,2) DEFAULT '0.00' COMMENT 'Cantidad',
  `fe_fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `cte_id` int NOT NULL COMMENT 'Cliente',
  `fe_tipo` char(1) NOT NULL COMMENT 'Factura, Remision',
  `rrc_id` int DEFAULT NULL COMMENT 'Revoltura Presentacion Cliente',
  `fe_cartaporte` varchar(50) DEFAULT NULL,
  `orden_embarque_id` int DEFAULT NULL,
  `pe_id` int DEFAULT NULL COMMENT 'Producto externo',
  `fe_tipo_producto` enum('REVOLTURA','EXTERNO') NOT NULL DEFAULT 'REVOLTURA',
  PRIMARY KEY (`fe_id`),
  KEY `idx_fe_rr_id` (`rr_id`),
  KEY `idx_fe_rrc_id` (`rrc_id`),
  KEY `idx_fe_orden_embarque_id` (`orden_embarque_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_revolturas_pt_muestreo`
--

DROP TABLE IF EXISTS `rev_revolturas_pt_muestreo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_revolturas_pt_muestreo` (
  `rm_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rm_ren` int NOT NULL COMMENT 'Renglon',
  `rev_id` int NOT NULL COMMENT 'Revoltura',
  `pres_id` int NOT NULL COMMENT 'PresentaciÔö£Ôöén',
  `rm_kilos` decimal(7,2) NOT NULL COMMENT 'Kilos del muestreo',
  `rm_estatus` char(1) NOT NULL COMMENT 'Estatus de muestreo',
  PRIMARY KEY (`rm_id`),
  KEY `fk_rm_pres` (`pres_id`),
  KEY `fk_rm_rev` (`rev_id`),
  CONSTRAINT `fk_rm_pres` FOREIGN KEY (`pres_id`) REFERENCES `rev_presentacion` (`pres_id`),
  CONSTRAINT `fk_rm_rev` FOREIGN KEY (`rev_id`) REFERENCES `rev_revolturas` (`rev_id`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_revolturas_tarimas`
--

DROP TABLE IF EXISTS `rev_revolturas_tarimas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_revolturas_tarimas` (
  `rt_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `rev_id` int NOT NULL COMMENT 'Revoltura',
  `tar_id` int NOT NULL COMMENT 'Tarima',
  PRIMARY KEY (`rt_id`),
  KEY `fk_rt_rev` (`rev_id`),
  KEY `fk_rt_tar` (`tar_id`),
  CONSTRAINT `fk_rt_rev` FOREIGN KEY (`rev_id`) REFERENCES `rev_revolturas` (`rev_id`),
  CONSTRAINT `fk_rt_tar` FOREIGN KEY (`tar_id`) REFERENCES `rev_tarimas` (`tar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9376 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_tarimas`
--

DROP TABLE IF EXISTS `rev_tarimas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_tarimas` (
  `tar_id` int NOT NULL AUTO_INCREMENT COMMENT 'clave',
  `pro_id` int NOT NULL COMMENT 'proceso',
  `tar_folio` varchar(40) NOT NULL,
  `niv_id` int NOT NULL COMMENT 'nivel-posicion',
  `usu_id` int NOT NULL COMMENT 'usuario',
  `tar_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de sistema',
  `tar_color` decimal(5,2) DEFAULT NULL COMMENT 'Color',
  `tar_redox` decimal(6,3) DEFAULT NULL,
  `tar_ph` decimal(5,2) DEFAULT NULL COMMENT 'ph',
  `tar_trans` decimal(5,2) DEFAULT NULL COMMENT 'Trans',
  `tar_porcentaje_t` decimal(5,2) DEFAULT NULL,
  `tar_bloom` decimal(5,2) DEFAULT NULL COMMENT 'Bloom',
  `tar_viscosidad` decimal(5,2) DEFAULT NULL COMMENT 'Viscosidad',
  `cal_id` int DEFAULT NULL COMMENT 'Calidad',
  `tar_rendimiento` decimal(9,4) DEFAULT NULL COMMENT 'Rendimiento',
  `tar_olor` tinyint DEFAULT NULL COMMENT 'olor',
  `tar_ntu` decimal(7,2) DEFAULT NULL COMMENT 'ntu',
  `tar_humedad` decimal(7,2) DEFAULT NULL COMMENT 'humedad',
  `tar_cenizas` decimal(7,2) DEFAULT NULL COMMENT 'cenizas',
  `tar_ce` decimal(7,2) DEFAULT NULL COMMENT 'Conductividad electrica',
  `tar_fino` char(1) DEFAULT 'N' COMMENT 'F: FINO, N: NORMAL',
  `tar_pe_1kg` decimal(7,2) DEFAULT NULL COMMENT 'particulas extraÔö£ÔûÆas en 1 kilo',
  `tar_par_extr` decimal(7,2) DEFAULT NULL COMMENT 'particulas extraÔö£ÔûÆas',
  `tar_par_ind` decimal(7,2) DEFAULT NULL COMMENT 'particulas indisolubles',
  `tar_hidratacion` varchar(5) DEFAULT NULL COMMENT 'hidrataciÔö£Ôöén',
  `tar_malla_30` decimal(5,2) DEFAULT NULL COMMENT 'Malla 30',
  `tar_malla_45` decimal(5,2) DEFAULT NULL COMMENT 'Malla 45',
  `tar_coliformes` decimal(7,2) DEFAULT NULL,
  `tar_ecoli` decimal(7,2) DEFAULT NULL,
  `tar_salmonella` decimal(7,2) DEFAULT NULL,
  `tar_saereus` decimal(7,2) DEFAULT NULL,
  `tar_fe_param` datetime NOT NULL COMMENT 'Fecha de parametros',
  `tar_rechazado` char(1) DEFAULT NULL COMMENT 'A: Aceptado R: Rechazado C: Curentena',
  `tar_estatus` int DEFAULT '0',
  `tar_kilos` decimal(7,2) NOT NULL DEFAULT '1000.00' COMMENT 'Kilos de la tarima',
  `pro_id_2` int DEFAULT NULL COMMENT 'Proceso 2',
  `tar_count_etiquetado` char(1) DEFAULT NULL COMMENT 'Contador de etiquetas por tarima',
  `tar_token` varchar(64) DEFAULT NULL,
  `tar_bma` int DEFAULT NULL,
  PRIMARY KEY (`tar_id`),
  UNIQUE KEY `tar_token` (`tar_token`),
  KEY `fk_tar_niv` (`niv_id`),
  KEY `fk_tar_pro` (`pro_id`),
  KEY `fk_tar_usu` (`usu_id`),
  CONSTRAINT `fk_tar_usu` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_tarimas_facturas`
--

DROP TABLE IF EXISTS `rev_tarimas_facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_tarimas_facturas` (
  `ft_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `tar_id` int NOT NULL COMMENT 'Id Tarima',
  `ft_factura` varchar(50) DEFAULT NULL COMMENT 'Factura, Remision',
  `ft_vale_salida` varchar(30) DEFAULT NULL,
  `ft_kilos_facturados` decimal(10,2) DEFAULT NULL,
  `ft_fecha` datetime DEFAULT NULL,
  `ft_tipo` char(1) NOT NULL COMMENT 'Factura, Remision',
  `cte_id` int NOT NULL COMMENT 'Cliente',
  PRIMARY KEY (`ft_id`),
  KEY `fk_tar_id` (`tar_id`),
  KEY `fk_cte_id` (`cte_id`)
) ENGINE=MyISAM AUTO_INCREMENT=334 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_tarimas_hist`
--

DROP TABLE IF EXISTS `rev_tarimas_hist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_tarimas_hist` (
  `tar_id` int NOT NULL COMMENT 'clave',
  `tar_color` decimal(5,2) NOT NULL COMMENT 'Color',
  `tar_redox` decimal(5,2) NOT NULL COMMENT 'Redox',
  `tar_ph` decimal(5,2) NOT NULL COMMENT 'ph',
  `tar_trans` decimal(5,2) NOT NULL COMMENT 'Trans',
  `tar_porcentaje_t` decimal(5,2) DEFAULT NULL,
  `tar_bloom` decimal(5,2) NOT NULL COMMENT 'Bloom',
  `tar_viscosidad` decimal(5,2) NOT NULL COMMENT 'Viscosidad',
  `cal_id` int NOT NULL COMMENT 'Calidad',
  `tar_rendimiento` decimal(7,2) NOT NULL COMMENT 'Rendimiento',
  `tar_olor` tinyint NOT NULL COMMENT 'olor',
  `tar_ntu` decimal(7,2) NOT NULL COMMENT 'ntu',
  `tar_humedad` decimal(7,2) NOT NULL COMMENT 'humedad',
  `tar_cenizas` decimal(7,2) NOT NULL COMMENT 'cenizas',
  `tar_ce` decimal(7,2) NOT NULL COMMENT 'Conductividad electrica',
  `tar_fino` decimal(7,2) NOT NULL COMMENT 'fino',
  `tar_pe_1kg` decimal(7,2) NOT NULL COMMENT 'particulas extraÔö£├óÔö¼ÔûÆas en 1 kilo',
  `tar_par_extr` decimal(7,2) NOT NULL COMMENT 'particulas extraÔö£├óÔö¼ÔûÆas',
  `tar_par_ind` decimal(7,2) NOT NULL COMMENT 'particulas indisolubles',
  `tar_hidratacion` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'hidrataciÔö£├óÔö¼Ôöén',
  `tar_malla_30` decimal(5,2) NOT NULL COMMENT 'Malla 30',
  `tar_malla_45` decimal(5,2) NOT NULL COMMENT 'Malla 45',
  `tar_fe_param` datetime NOT NULL COMMENT 'Fecha de parametros',
  `tar_rechazado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'A: Aceptado R: Rechazado',
  `mez_id` int DEFAULT NULL COMMENT 'ID MEZCLA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_transportistas`
--

DROP TABLE IF EXISTS `rev_transportistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_transportistas` (
  `trans_id` int NOT NULL AUTO_INCREMENT,
  `trans_nombre` varchar(255) DEFAULT NULL,
  `trans_estatus` enum('A','B') DEFAULT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_vendedores`
--

DROP TABLE IF EXISTS `rev_vendedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_vendedores` (
  `ven_id` int NOT NULL AUTO_INCREMENT,
  `ven_numero_nomina` varchar(20) NOT NULL,
  `ven_nombre` varchar(200) NOT NULL,
  `ven_porcentaje_comision` decimal(5,2) NOT NULL,
  `ven_estatus` char(1) DEFAULT 'A' COMMENT 'A: Activo, B: Baja',
  PRIMARY KEY (`ven_id`),
  UNIQUE KEY `ven_numero_nomina` (`ven_numero_nomina`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rev_viscosidades`
--

DROP TABLE IF EXISTS `rev_viscosidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rev_viscosidades` (
  `vis_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `vis_descrip` varchar(20) NOT NULL COMMENT 'Descripcion',
  `vis_min_val` int NOT NULL,
  `vis_max_val` int NOT NULL COMMENT 'Maximo Valor',
  `vis_color` varchar(9) NOT NULL COMMENT 'Phantome',
  PRIMARY KEY (`vis_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tarimas`
--

DROP TABLE IF EXISTS `tarimas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarimas` (
  `tarima_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `lote_id` int NOT NULL COMMENT 'Lote',
  `tarima_fecha` date NOT NULL,
  `tarima_lim_param` varchar(11) DEFAULT NULL,
  `tarima_bloom` float DEFAULT '0',
  `tarima_viscocidad` float DEFAULT '0',
  `tarima_ph_final` float DEFAULT '0',
  `tarima_transparencia` float DEFAULT '0',
  `tarima_porcen_t` float DEFAULT '0',
  `tarima_ntu` float DEFAULT '0',
  `tarima_humedad` float DEFAULT '0',
  `tarima_cenizas` float DEFAULT '0',
  `tarima_redox` float DEFAULT '0',
  `tarima_color` float DEFAULT '0',
  `tarima_grano` float DEFAULT '0',
  `tarima_olor` varchar(11) DEFAULT NULL,
  `tarima_part_ext` float DEFAULT '0',
  `tarima_part_ind` float DEFAULT '0',
  `tarima_hidratacion` varchar(11) DEFAULT NULL,
  `tarima_aceptado` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`tarima_id`),
  KEY `lote_id` (`lote_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3899 DEFAULT CHARSET=latin1 COMMENT='Tabla para almacenar los tarimas de los procesos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_agua`
--

DROP TABLE IF EXISTS `tipos_agua`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_agua` (
  `tpa_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `tpa_descripcion` varchar(30) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  PRIMARY KEY (`tpa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_agua_a`
--

DROP TABLE IF EXISTS `tipos_agua_a`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_agua_a` (
  `taa_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `taa_descripcion` varchar(25) NOT NULL COMMENT 'DescripciÔö£Ôöén',
  PRIMARY KEY (`taa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unidades_medida`
--

DROP TABLE IF EXISTS `unidades_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidades_medida` (
  `um_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave de unidad',
  `um_descripcion` varchar(20) NOT NULL COMMENT 'Descripcion de la nidad',
  `um_abreviacion` varchar(12) NOT NULL COMMENT 'Abreviacion de la unidad de medida',
  PRIMARY KEY (`um_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `usu_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `usu_nombre` varchar(50) NOT NULL COMMENT 'Nombre de usuario de acceso al sistema',
  `usu_usuario` varchar(25) NOT NULL COMMENT 'Usuario del sistema',
  `up_id` int NOT NULL COMMENT 'Nivel de acceso',
  `usu_pwr` varchar(50) NOT NULL COMMENT 'ContraseÔö£ÔûÆa de acceso',
  `usu_email` varchar(40) DEFAULT NULL COMMENT 'Email',
  `usu_est` char(1) NOT NULL DEFAULT 'A' COMMENT 'Estatus',
  `usu_clave_auth` int DEFAULT NULL COMMENT 'Clave de autenticaciÔö£Ôöén',
  PRIMARY KEY (`usu_id`),
  UNIQUE KEY `idx_usuario` (`usu_usuario`) USING BTREE,
  KEY `fk_usu_nivel` (`up_id`),
  KEY `fk_usu_esta` (`usu_est`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_perfiles`
--

DROP TABLE IF EXISTS `usuarios_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_perfiles` (
  `up_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `up_nombre` varchar(25) NOT NULL COMMENT 'Descripcion',
  `up_ban` char(1) NOT NULL DEFAULT '0' COMMENT '1:autorizado 0:no autorizado ',
  PRIMARY KEY (`up_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_permisos`
--

DROP TABLE IF EXISTS `usuarios_permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_permisos` (
  `upe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `bm_id` int NOT NULL COMMENT 'Modulo',
  `up_id` int NOT NULL COMMENT 'Permiso',
  `upe_agregar` tinyint(1) NOT NULL COMMENT '1 si, 0 no',
  `upe_borrar` tinyint(1) NOT NULL COMMENT '1 si, 0 no',
  `upe_editar` tinyint(1) NOT NULL COMMENT '1 si, 0 no',
  `upe_listar` tinyint(1) NOT NULL COMMENT '1 si, 0 no',
  PRIMARY KEY (`upe_id`),
  KEY `fk_upe_bm` (`bm_id`),
  KEY `fk_upe_up` (`up_id`)
) ENGINE=InnoDB AUTO_INCREMENT=496 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_permisos_esp`
--

DROP TABLE IF EXISTS `usuarios_permisos_esp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_permisos_esp` (
  `upe_id` int NOT NULL AUTO_INCREMENT COMMENT 'Clave',
  `bm_id` int NOT NULL COMMENT 'Modulo',
  `up_id` int NOT NULL COMMENT 'Perfil',
  `upe_permiso` tinyint(1) NOT NULL COMMENT '0 No, 1 Si',
  `upe_descripcion` varchar(25) NOT NULL COMMENT 'Descripcion',
  PRIMARY KEY (`upe_id`),
  KEY `fk_upes_up` (`up_id`),
  KEY `fk_upes_bm` (`bm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `vw_rev_revolturas_pt_cliente_disponible`
--

DROP TABLE IF EXISTS `vw_rev_revolturas_pt_cliente_disponible`;
/*!50001 DROP VIEW IF EXISTS `vw_rev_revolturas_pt_cliente_disponible`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_rev_revolturas_pt_cliente_disponible` AS SELECT 
 1 AS `rrc_id`,
 1 AS `rev_id`,
 1 AS `pres_id`,
 1 AS `roed_id`,
 1 AS `cte_id`,
 1 AS `rrc_ext_inicial`,
 1 AS `rrc_ext_real`,
 1 AS `cantidad_comprometida`,
 1 AS `cantidad_disponible`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_rev_revolturas_pt_disponible`
--

DROP TABLE IF EXISTS `vw_rev_revolturas_pt_disponible`;
/*!50001 DROP VIEW IF EXISTS `vw_rev_revolturas_pt_disponible`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_rev_revolturas_pt_disponible` AS SELECT 
 1 AS `rr_id`,
 1 AS `rev_id`,
 1 AS `pres_id`,
 1 AS `roed_id`,
 1 AS `rr_ext_inicial`,
 1 AS `rr_ext_real`,
 1 AS `cantidad_comprometida`,
 1 AS `cantidad_disponible`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'bd_sis_preparacion'
--

--
-- Dumping routines for database 'bd_sis_preparacion'
--

--
-- Final view structure for view `movimiento_equipos`
--

/*!50001 DROP VIEW IF EXISTS `movimiento_equipos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `movimiento_equipos` AS select `b`.`pro_id` AS `pro_id`,`b`.`pa_id` AS `agrupado`,`p`.`ep_descripcion` AS `equipo_anterior`,`pn`.`ep_descripcion` AS `equipo_nuevo`,`u`.`usu_usuario` AS `usu_usuario`,`b`.`be_fecha` AS `be_fecha`,`b`.`be_comentarios` AS `be_comentarios` from (((`bitacora_equipos` `b` join `equipos_preparacion` `p` on((`b`.`ep_anterior` = `p`.`ep_id`))) join `equipos_preparacion` `pn` on((`b`.`ep_nuevo` = `pn`.`ep_id`))) join `usuarios` `u` on((`b`.`usu_id` = `u`.`usu_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_rev_revolturas_pt_cliente_disponible`
--

/*!50001 DROP VIEW IF EXISTS `vw_rev_revolturas_pt_cliente_disponible`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_rev_revolturas_pt_cliente_disponible` AS select `c`.`rrc_id` AS `rrc_id`,`c`.`rev_id` AS `rev_id`,`c`.`pres_id` AS `pres_id`,`c`.`roed_id` AS `roed_id`,`c`.`cte_id` AS `cte_id`,`c`.`rrc_ext_inicial` AS `rrc_ext_inicial`,`c`.`rrc_ext_real` AS `rrc_ext_real`,ifnull(sum((case when (`oe`.`oe_estado` in ('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO')) then `d`.`cantidad` else 0 end)),0) AS `cantidad_comprometida`,(`c`.`rrc_ext_real` - ifnull(sum((case when (`oe`.`oe_estado` in ('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO')) then `d`.`cantidad` else 0 end)),0)) AS `cantidad_disponible` from ((`rev_revolturas_pt_cliente` `c` left join `rev_orden_embarque_detalle` `d` on(((`d`.`rrc_id` = `c`.`rrc_id`) and (`d`.`oed_tipo_producto` = 'REVOLTURA')))) left join `rev_orden_embarque` `oe` on((`oe`.`oe_id` = `d`.`oe_id`))) group by `c`.`rrc_id`,`c`.`rev_id`,`c`.`pres_id`,`c`.`roed_id`,`c`.`cte_id`,`c`.`rrc_ext_inicial`,`c`.`rrc_ext_real` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_rev_revolturas_pt_disponible`
--

/*!50001 DROP VIEW IF EXISTS `vw_rev_revolturas_pt_disponible`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_rev_revolturas_pt_disponible` AS select `pt`.`rr_id` AS `rr_id`,`pt`.`rev_id` AS `rev_id`,`pt`.`pres_id` AS `pres_id`,`pt`.`roed_id` AS `roed_id`,`pt`.`rr_ext_inicial` AS `rr_ext_inicial`,`pt`.`rr_ext_real` AS `rr_ext_real`,ifnull(sum((case when (`oe`.`oe_estado` in ('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO')) then `d`.`cantidad` else 0 end)),0) AS `cantidad_comprometida`,(`pt`.`rr_ext_real` - ifnull(sum((case when (`oe`.`oe_estado` in ('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO')) then `d`.`cantidad` else 0 end)),0)) AS `cantidad_disponible` from ((`rev_revolturas_pt` `pt` left join `rev_orden_embarque_detalle` `d` on(((`d`.`rr_id` = `pt`.`rr_id`) and (`d`.`oed_tipo_producto` = 'REVOLTURA')))) left join `rev_orden_embarque` `oe` on((`oe`.`oe_id` = `d`.`oe_id`))) group by `pt`.`rr_id`,`pt`.`rev_id`,`pt`.`pres_id`,`pt`.`roed_id`,`pt`.`rr_ext_inicial`,`pt`.`rr_ext_real` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-08 22:35:23
