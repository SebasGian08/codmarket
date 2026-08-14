-- ============================================================
-- SCRIPT COMPLETO MODULO POS / VENTAS - PRODUCCION
-- Crea las tablas nuevas + inserta los datos base.
-- Ejecutar en phpMyAdmin o mysql (base destino).
-- Seguro de repetir: no borra datos existentes.
-- ============================================================

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiendas` (
  `id_tienda` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tienda`),
  UNIQUE KEY `tiendas_codigo_unique` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `id_tipo_documento` bigint(20) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `es_varios` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `clientes_id_tipo_documento_foreign` (`id_tipo_documento`),
  CONSTRAINT `clientes_id_tipo_documento_foreign` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cajas` (
  `id_caja` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_tienda` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `id_vendedor` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT 'Caja Principal',
  `monto_apertura` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_cierre` decimal(10,2) DEFAULT NULL,
  `fecha_apertura` timestamp NULL DEFAULT NULL,
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_caja`),
  KEY `cajas_id_tienda_foreign` (`id_tienda`),
  KEY `cajas_id_usuario_foreign` (`id_usuario`),
  KEY `cajas_id_vendedor_foreign` (`id_vendedor`),
  CONSTRAINT `cajas_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `cajas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `cajas_id_vendedor_foreign` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendedores` (
  `id_vendedor` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_vendedor`),
  KEY `vendedores_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `vendedores_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metodos_pagos` (
  `id_metodo_pago` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(30) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_metodo_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `id_venta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) unsigned NOT NULL,
  `id_caja` bigint(20) unsigned NOT NULL,
  `id_tienda` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned DEFAULT NULL,
  `nombre_cliente` varchar(150) DEFAULT NULL,
  `id_metodo_pago` bigint(20) unsigned DEFAULT NULL,
  `id_vendedor` bigint(20) unsigned DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  UNIQUE KEY `ventas_numero_unique` (`numero`),
  KEY `ventas_id_caja_foreign` (`id_caja`),
  KEY `ventas_id_tienda_foreign` (`id_tienda`),
  KEY `ventas_id_usuario_foreign` (`id_usuario`),
  KEY `ventas_id_cliente_foreign` (`id_cliente`),
  KEY `ventas_id_metodo_pago_foreign` (`id_metodo_pago`),
  KEY `ventas_id_vendedor_foreign` (`id_vendedor`),
  CONSTRAINT `ventas_id_caja_foreign` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id_caja`) ON DELETE CASCADE,
  CONSTRAINT `ventas_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL,
  CONSTRAINT `ventas_id_metodo_pago_foreign` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pagos` (`id_metodo_pago`) ON DELETE SET NULL,
  CONSTRAINT `ventas_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `ventas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `ventas_id_vendedor_foreign` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_detalle` (
  `id_venta_detalle` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` bigint(20) unsigned NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_venta_detalle`),
  KEY `ventas_detalle_id_venta_foreign` (`id_venta`),
  KEY `ventas_detalle_id_variante_foreign` (`id_variante`),
  CONSTRAINT `ventas_detalle_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE,
  CONSTRAINT `ventas_detalle_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingresos` (
  `id_ingreso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) unsigned NOT NULL,
  `tipo` enum('compra','ajuste') NOT NULL DEFAULT 'compra',
  `id_proveedor` bigint(20) DEFAULT NULL,
  `id_tienda` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ingreso`),
  UNIQUE KEY `ingresos_numero_unique` (`numero`),
  KEY `ingresos_id_proveedor_foreign` (`id_proveedor`),
  KEY `ingresos_id_tienda_foreign` (`id_tienda`),
  KEY `ingresos_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `ingresos_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE SET NULL,
  CONSTRAINT `ingresos_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `ingresos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingresos_detalle` (
  `id_ingreso_detalle` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_ingreso` bigint(20) unsigned NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ingreso_detalle`),
  KEY `ingresos_detalle_id_ingreso_foreign` (`id_ingreso`),
  KEY `ingresos_detalle_id_variante_foreign` (`id_variante`),
  CONSTRAINT `ingresos_detalle_id_ingreso_foreign` FOREIGN KEY (`id_ingreso`) REFERENCES `ingresos` (`id_ingreso`) ON DELETE CASCADE,
  CONSTRAINT `ingresos_detalle_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transferencias` (
  `id_transferencia` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) unsigned NOT NULL,
  `id_tienda_origen` bigint(20) unsigned NOT NULL,
  `id_tienda_destino` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` enum('pendiente','en_transito','recibida','anulada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_transferencia`),
  UNIQUE KEY `transferencias_numero_unique` (`numero`),
  KEY `transferencias_id_tienda_origen_foreign` (`id_tienda_origen`),
  KEY `transferencias_id_tienda_destino_foreign` (`id_tienda_destino`),
  KEY `transferencias_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `transferencias_id_tienda_destino_foreign` FOREIGN KEY (`id_tienda_destino`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `transferencias_id_tienda_origen_foreign` FOREIGN KEY (`id_tienda_origen`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `transferencias_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transferencias_detalle` (
  `id_transferencia_detalle` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_transferencia` bigint(20) unsigned NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_transferencia_detalle`),
  KEY `transferencias_detalle_id_transferencia_foreign` (`id_transferencia`),
  KEY `transferencias_detalle_id_variante_foreign` (`id_variante`),
  CONSTRAINT `transferencias_detalle_id_transferencia_foreign` FOREIGN KEY (`id_transferencia`) REFERENCES `transferencias` (`id_transferencia`) ON DELETE CASCADE,
  CONSTRAINT `transferencias_detalle_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventarios` (
  `id_inventario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_variante` bigint(20) NOT NULL,
  `id_tienda` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_inventario`),
  UNIQUE KEY `inventarios_id_variante_id_tienda_unique` (`id_variante`,`id_tienda`),
  KEY `inventarios_id_tienda_foreign` (`id_tienda`),
  CONSTRAINT `inventarios_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `inventarios_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos` (
  `id_movimiento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_variante` bigint(20) NOT NULL,
  `id_tienda` bigint(20) unsigned NOT NULL,
  `tipo` enum('ingreso','venta','transferencia_salida','transferencia_entrada','ajuste') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_referencia` bigint(20) unsigned DEFAULT NULL,
  `id_usuario` bigint(20) unsigned DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `observacion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  KEY `movimientos_id_variante_foreign` (`id_variante`),
  KEY `movimientos_id_tienda_foreign` (`id_tienda`),
  KEY `movimientos_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `movimientos_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `movimientos_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- ============================================================
-- DATOS BASE
-- ============================================================

-- Metodos de pago
INSERT IGNORE INTO metodos_pagos (id_metodo_pago, nombre, codigo, estado, created_at, updated_at) VALUES
(1, 'Efectivo', 'efectivo', 1, NOW(), NOW()),
(2, 'Tarjeta', 'tarjeta', 1, NOW(), NOW()),
(3, 'Transferencia', 'transferencia', 1, NOW(), NOW()),
(4, 'Otro', 'otro', 1, NOW(), NOW());

-- Tienda principal
INSERT IGNORE INTO tiendas (id_tienda, codigo, nombre, direccion, telefono, es_principal, estado, created_at, updated_at) VALUES
(1, 'T01', 'Tienda Principal', NULL, NULL, 1, 1, NOW(), NOW());

-- Cliente generico "Clientes Varios" (id 1 por defecto)
INSERT IGNORE INTO clientes (id_cliente, nombre, numero_documento, telefono, correo, direccion, estado, es_varios, created_at, updated_at) VALUES
(1, 'CLIENTES VARIOS', NULL, NULL, NULL, NULL, 1, 1, NOW(), NOW());
