-- =====================================================
-- INSTALACION INICIAL - ESTRUCTURA COMPLETA DE LA BASE
-- Fuente: dump oficial grupocod_dayannaconfecciones (07-09-2026)
-- IMPORTANTE: toda tabla nueva se agrega aqui (CREATE TABLE IF NOT EXISTS)
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `atributos` (
  `id_atributo` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `atributos_valores` (
  `id_valor` bigint(20) NOT NULL,
  `id_atributo` bigint(20) DEFAULT NULL,
  `valor` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `banners_principales` (
  `id_banner` bigint(20) NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `subtitulo` varchar(150) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `imagen_mobile` varchar(255) DEFAULT NULL,
  `imagen_referencial` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `texto_boton` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `solo_imagen` tinyint(1) DEFAULT 0,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id_blog` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `blogs_blog_tag` (
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `blogs_categories` (
  `id_blogs_categories` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `blogs_tags` (
  `id_blogs_tags` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `cajas` (
  `id_caja` bigint(20) UNSIGNED NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_vendedor` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT 'Caja Principal',
  `monto_apertura` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_cierre` decimal(10,2) DEFAULT NULL,
  `monto_diferencia` decimal(12,2) DEFAULT NULL,
  `fecha_apertura` timestamp NULL DEFAULT NULL,
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` bigint(20) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `slug` varchar(180) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `icono` varchar(255) DEFAULT NULL,
  `id_categoria_padre` bigint(20) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `id_tipo_documento` bigint(20) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `es_varios` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `configuraciones` (
  `id_configuracion` bigint(20) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'text',
  `opciones` text DEFAULT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id_contact` bigint(20) UNSIGNED NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `id_service` bigint(20) UNSIGNED DEFAULT NULL,
  `id_source` bigint(20) UNSIGNED DEFAULT NULL,
  `id_status` bigint(20) UNSIGNED DEFAULT NULL,
  `id_priority` bigint(20) UNSIGNED DEFAULT NULL,
  `mensaje` text NOT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `contacts_seguimiento` (
  `id` bigint(10) UNSIGNED NOT NULL,
  `contact_id` bigint(10) UNSIGNED NOT NULL,
  `tipo_id` bigint(10) UNSIGNED NOT NULL,
  `user_id` bigint(10) UNSIGNED DEFAULT NULL,
  `comentario` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `contact_sources` (
  `id_source` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `contact_statuses` (
  `id_status` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `cuentas_bancarias` (
  `id_cuenta_bancaria` bigint(20) UNSIGNED NOT NULL,
  `nombre_banco` varchar(100) NOT NULL,
  `tipo_cuenta` int(10) UNSIGNED DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  `titular` varchar(150) DEFAULT NULL,
  `saldo_actual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `cuentas_tipo_cuenta` (
  `id_tipo_cuenta` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `destinos_pago` (
  `id_destino_pago` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `empresa` (
  `id_empresa` bigint(20) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `nombre_comercial` varchar(200) DEFAULT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `logo_header` varchar(255) DEFAULT NULL,
  `logo_footer` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `descripcion_empresarial` text DEFAULT NULL,
  `mision_empresarial` text DEFAULT NULL,
  `vision_empresarial` text DEFAULT NULL,
  `valores_empresariales` text DEFAULT NULL,
  `imagen_empresarial` varchar(255) DEFAULT NULL,
  `portada_empresarial` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) UNSIGNED NOT NULL,
  `id_tipo_gasto` int(10) UNSIGNED NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_caja` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cuenta_bancaria` bigint(20) UNSIGNED DEFAULT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'PEN',
  `observacion` text DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ingresos` (
  `id_ingreso` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) UNSIGNED NOT NULL,
  `tipo` enum('compra','ajuste') NOT NULL DEFAULT 'compra',
  `id_proveedor` bigint(20) DEFAULT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ingresos_detalle` (
  `id_ingreso_detalle` bigint(20) UNSIGNED NOT NULL,
  `id_ingreso` bigint(20) UNSIGNED NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ingresos_economicos` (
  `id_ingreso_economico` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) UNSIGNED NOT NULL,
  `id_tipo_ingreso_economico` bigint(20) UNSIGNED NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_caja` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cuenta_bancaria` bigint(20) UNSIGNED DEFAULT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'PEN',
  `observacion` text DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `inventarios` (
  `id_inventario` bigint(20) UNSIGNED NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `marcas` (
  `id_marca` bigint(20) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `slug` varchar(180) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `metodos_pagos` (
  `id_metodo_pago` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(30) DEFAULT NULL,
  `id_destino_pago` int(10) UNSIGNED DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(200) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `motivos_descuento` (
  `id_motivo_descuento` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `aplica_a` enum('ITEM','CABECERA') NOT NULL DEFAULT 'ITEM',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `movimientos` (
  `id_movimiento` bigint(20) UNSIGNED NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_tipo_movimiento` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_referencia` bigint(20) UNSIGNED DEFAULT NULL,
  `id_usuario` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `observacion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `movimientos_dinero` (
  `id_movimiento_dinero` bigint(20) UNSIGNED NOT NULL,
  `id_tipo_movimiento_dinero` bigint(20) UNSIGNED NOT NULL,
  `id_caja` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cuenta_bancaria` bigint(20) UNSIGNED DEFAULT NULL,
  `referencia_tipo` varchar(40) DEFAULT NULL,
  `id_referencia` bigint(20) UNSIGNED DEFAULT NULL,
  `id_metodo_pago` bigint(20) UNSIGNED DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'PEN',
  `fecha` datetime NOT NULL,
  `observacion` text DEFAULT NULL,
  `id_usuario_registro` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `movimientos_tipo` (
  `id_tipo_movimiento` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(60) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `signo` varchar(1) NOT NULL DEFAULT '+',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS `portafolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `cliente` varchar(200) DEFAULT NULL,
  `categoria` varchar(200) NOT NULL,
  `tipo` enum('web','marketing','software','facturacion','app','otros') NOT NULL DEFAULT 'otros',
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `url_demo` varchar(200) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `preguntas_frecuentes` (
  `id_pregunta_frecuente` bigint(20) UNSIGNED NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `respuesta` text NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `priorities` (
  `id_priority` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `nivel` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` bigint(20) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `slug` varchar(220) DEFAULT NULL,
  `descripcion` longtext DEFAULT NULL,
  `descripcion_corta` text DEFAULT NULL,
  `id_marca` bigint(20) DEFAULT NULL,
  `id_proveedor` bigint(20) DEFAULT NULL,
  `peso` decimal(10,2) DEFAULT NULL,
  `dimensiones` varchar(100) DEFAULT NULL,
  `destacado` tinyint(4) DEFAULT 0,
  `nuevo` tinyint(4) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `productos_imagenes` (
  `id_imagen` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `id_variante` bigint(20) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `principal` tinyint(4) DEFAULT 0,
  `orden` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `productos_variantes` (
  `id_variante` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `codigo_barras` varchar(150) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `producto_categorias` (
  `id_producto_categoria` bigint(20) NOT NULL,
  `id_producto` bigint(20) DEFAULT NULL,
  `id_categoria` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `promociones` (
  `id_promocion` bigint(20) NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `subtitulo` varchar(150) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  `imagen_mobile` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `texto_boton` varchar(100) DEFAULT NULL,
  `color_texto` varchar(20) DEFAULT '#ffffff',
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` bigint(20) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `id_tipo_documento` bigint(20) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `contacto` varchar(150) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `reglas_descuento` (
  `id_regla_descuento` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_tipo_descuento` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cantidad_min` int(10) UNSIGNED DEFAULT NULL,
  `cantidad_max` int(10) UNSIGNED DEFAULT NULL,
  `id_tipo_venta` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `rol_permiso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `id_permiso` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS `rubros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `orden` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `seguimiento_tipos` (
  `id` bigint(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `services` (
  `id_service` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `portada` varchar(200) DEFAULT NULL,
  `descripcion_portada` text DEFAULT NULL,
  `descripcion_breve_portada` text DEFAULT NULL,
  `imagen_portada` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `imagen_referencial` varchar(200) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `service_benefits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `service_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `service_plan_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(200) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tiendas` (
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tipos_descuento` (
  `id_tipo_descuento` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tipos_gastos` (
  `id_tipo_gasto` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tipos_ingresos_economicos` (
  `id_tipo_ingreso_economico` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tipos_movimiento_dinero` (
  `id_tipo_movimiento_dinero` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(60) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `signo` varchar(1) NOT NULL DEFAULT '+',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tipos_venta` (
  `id_tipo_venta` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tipo_documento` (
  `id_tipo_documento` bigint(20) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `longitud` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `trabajos_realizados` (
  `id_trabajos_realizados` bigint(20) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(250) DEFAULT NULL,
  `cliente` varchar(200) DEFAULT NULL,
  `descripcion` longtext DEFAULT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `orden` bigint(20) DEFAULT 0,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `transferencias` (
  `id_transferencia` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) UNSIGNED NOT NULL,
  `id_tienda_origen` bigint(20) UNSIGNED NOT NULL,
  `id_tienda_destino` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` enum('pendiente','en_transito','recibida','anulada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `transferencias_detalle` (
  `id_transferencia_detalle` bigint(20) UNSIGNED NOT NULL,
  `id_transferencia` bigint(20) UNSIGNED NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `transferencias_dinero` (
  `id_transferencia_dinero` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) UNSIGNED NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_caja_origen` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cuenta_origen` bigint(20) UNSIGNED DEFAULT NULL,
  `id_caja_destino` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cuenta_destino` bigint(20) UNSIGNED DEFAULT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'PEN',
  `observacion` text DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(200) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `variantes_atributos` (
  `id_variante_atributo` bigint(20) NOT NULL,
  `id_variante` bigint(20) DEFAULT NULL,
  `id_valor` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `vendedores` (
  `id_vendedor` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `vendedores_tiendas` (
  `id_vendedor_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_vendedor` bigint(20) UNSIGNED NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(30) NOT NULL,
  `correlativo` int(10) UNSIGNED NOT NULL,
  `id_caja` bigint(20) UNSIGNED NOT NULL,
  `id_tienda` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_cliente` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre_cliente` varchar(150) DEFAULT NULL,
  `id_metodo_pago` bigint(20) UNSIGNED DEFAULT NULL,
  `id_vendedor` bigint(20) UNSIGNED DEFAULT NULL,
  `id_tipo_venta` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_bruto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento_items_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento_global` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id_motivo_descuento_global` bigint(20) UNSIGNED DEFAULT NULL,
  `total_neto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_recibido` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `estado_cobro` enum('pendiente','cerrado') NOT NULL DEFAULT 'pendiente',
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `usuario_cierre` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ventas_detalle` (
  `id_venta_detalle` bigint(20) UNSIGNED NOT NULL,
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `id_variante` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `id_motivo_descuento` bigint(20) UNSIGNED DEFAULT NULL,
  `id_tipo_descuento` bigint(20) UNSIGNED DEFAULT NULL,
  `valor_descuento_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento_total_item` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_final` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `venta_pagos` (
  `id_venta_pago` bigint(20) UNSIGNED NOT NULL,
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `id_metodo_pago` bigint(20) UNSIGNED NOT NULL,
  `id_cuenta_bancaria` bigint(20) UNSIGNED DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'PEN',
  `id_usuario_registro` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
ALTER TABLE `atributos`
  ADD PRIMARY KEY (`id_atributo`);
ALTER TABLE `atributos_valores`
  ADD PRIMARY KEY (`id_valor`),
  ADD KEY `fk_valor_atributo` (`id_atributo`);
ALTER TABLE `banners_principales`
  ADD PRIMARY KEY (`id_banner`);
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id_blog`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`);
ALTER TABLE `blogs_categories`
  ADD PRIMARY KEY (`id_blogs_categories`),
  ADD UNIQUE KEY `blogs_categories_slug_unique` (`slug`);
ALTER TABLE `blogs_tags`
  ADD PRIMARY KEY (`id_blogs_tags`),
  ADD UNIQUE KEY `blogs_tags_slug_unique` (`slug`);
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id_caja`),
  ADD KEY `cajas_id_tienda_foreign` (`id_tienda`),
  ADD KEY `cajas_id_usuario_foreign` (`id_usuario`),
  ADD KEY `cajas_id_vendedor_foreign` (`id_vendedor`);
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `clientes_id_tipo_documento_foreign` (`id_tipo_documento`);
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id_configuracion`),
  ADD UNIQUE KEY `clave` (`clave`);
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id_contact`),
  ADD KEY `contacts_id_service_foreign` (`id_service`),
  ADD KEY `contacts_id_source_foreign` (`id_source`),
  ADD KEY `contacts_id_status_foreign` (`id_status`),
  ADD KEY `contacts_id_priority_foreign` (`id_priority`);
ALTER TABLE `contacts_seguimiento`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `contact_sources`
  ADD PRIMARY KEY (`id_source`);
ALTER TABLE `contact_statuses`
  ADD PRIMARY KEY (`id_status`);
ALTER TABLE `cuentas_bancarias`
  ADD PRIMARY KEY (`id_cuenta_bancaria`),
  ADD KEY `cuentas_bancarias_tipo_cuenta_foreign` (`tipo_cuenta`);
ALTER TABLE `cuentas_tipo_cuenta`
  ADD PRIMARY KEY (`id_tipo_cuenta`);
ALTER TABLE `destinos_pago`
  ADD PRIMARY KEY (`id_destino_pago`),
  ADD UNIQUE KEY `destinos_pago_codigo_unique` (`codigo`);
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`);
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id_gasto`),
  ADD UNIQUE KEY `gastos_numero_unique` (`numero`),
  ADD KEY `gastos_id_tipo_gasto_foreign` (`id_tipo_gasto`),
  ADD KEY `gastos_id_tienda_foreign` (`id_tienda`),
  ADD KEY `gastos_id_caja_foreign` (`id_caja`),
  ADD KEY `gastos_id_cuenta_bancaria_foreign` (`id_cuenta_bancaria`),
  ADD KEY `gastos_id_usuario_foreign` (`id_usuario`),
  ADD KEY `gastos_fecha_index` (`fecha`),
  ADD KEY `gastos_estado_index` (`estado`);
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD UNIQUE KEY `ingresos_numero_unique` (`numero`),
  ADD KEY `ingresos_id_proveedor_foreign` (`id_proveedor`),
  ADD KEY `ingresos_id_tienda_foreign` (`id_tienda`),
  ADD KEY `ingresos_id_usuario_foreign` (`id_usuario`);
ALTER TABLE `ingresos_detalle`
  ADD PRIMARY KEY (`id_ingreso_detalle`),
  ADD KEY `ingresos_detalle_id_ingreso_foreign` (`id_ingreso`),
  ADD KEY `ingresos_detalle_id_variante_foreign` (`id_variante`);
ALTER TABLE `ingresos_economicos`
  ADD PRIMARY KEY (`id_ingreso_economico`),
  ADD UNIQUE KEY `ingresos_economicos_numero_unique` (`numero`),
  ADD KEY `ingresos_economicos_id_tipo_ingreso_economico_foreign` (`id_tipo_ingreso_economico`),
  ADD KEY `ingresos_economicos_id_tienda_foreign` (`id_tienda`),
  ADD KEY `ingresos_economicos_id_caja_foreign` (`id_caja`),
  ADD KEY `ingresos_economicos_id_cuenta_bancaria_foreign` (`id_cuenta_bancaria`),
  ADD KEY `ingresos_economicos_id_usuario_foreign` (`id_usuario`);
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id_inventario`),
  ADD UNIQUE KEY `inventarios_id_variante_id_tienda_unique` (`id_variante`,`id_tienda`),
  ADD KEY `inventarios_id_tienda_foreign` (`id_tienda`);
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`),
  ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `metodos_pagos`
  ADD PRIMARY KEY (`id_metodo_pago`),
  ADD KEY `metodos_pagos_id_destino_pago_foreign` (`id_destino_pago`);
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `motivos_descuento`
  ADD PRIMARY KEY (`id_motivo_descuento`);
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `movimientos_id_variante_foreign` (`id_variante`),
  ADD KEY `movimientos_id_tienda_foreign` (`id_tienda`),
  ADD KEY `movimientos_id_usuario_foreign` (`id_usuario`),
  ADD KEY `movimientos_id_tipo_movimiento_foreign` (`id_tipo_movimiento`);
ALTER TABLE `movimientos_dinero`
  ADD PRIMARY KEY (`id_movimiento_dinero`),
  ADD KEY `movimientos_dinero_id_tipo_movimiento_dinero_foreign` (`id_tipo_movimiento_dinero`),
  ADD KEY `movimientos_dinero_id_caja_foreign` (`id_caja`),
  ADD KEY `movimientos_dinero_id_cuenta_bancaria_foreign` (`id_cuenta_bancaria`),
  ADD KEY `movimientos_dinero_id_metodo_pago_foreign` (`id_metodo_pago`),
  ADD KEY `movimientos_dinero_id_usuario_registro_foreign` (`id_usuario_registro`);
ALTER TABLE `movimientos_tipo`
  ADD PRIMARY KEY (`id_tipo_movimiento`),
  ADD UNIQUE KEY `movimientos_tipo_codigo_unique` (`codigo`);
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD UNIQUE KEY `codigo` (`codigo`);
ALTER TABLE `portafolios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portafolios_slug_unique` (`slug`),
  ADD KEY `portafolios_service_id_foreign` (`service_id`);
ALTER TABLE `preguntas_frecuentes`
  ADD PRIMARY KEY (`id_pregunta_frecuente`);
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id_priority`);
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_producto_marca` (`id_marca`),
  ADD KEY `fk_producto_proveedor` (`id_proveedor`);
ALTER TABLE `productos_imagenes`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `fk_img_producto` (`id_producto`),
  ADD KEY `fk_img_variante` (`id_variante`);
ALTER TABLE `productos_variantes`
  ADD PRIMARY KEY (`id_variante`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_variante_producto` (`id_producto`);
ALTER TABLE `producto_categorias`
  ADD PRIMARY KEY (`id_producto_categoria`);
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id_promocion`);
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);
ALTER TABLE `reglas_descuento`
  ADD PRIMARY KEY (`id_regla_descuento`),
  ADD KEY `reglas_descuento_id_tipo_descuento_foreign` (`id_tipo_descuento`),
  ADD KEY `reglas_descuento_id_tipo_venta_foreign` (`id_tipo_venta`);
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_rol_permiso` (`id_rol`,`id_permiso`),
  ADD KEY `fk_rol_permiso_permiso` (`id_permiso`);
ALTER TABLE `seguimiento_tipos`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`);
ALTER TABLE `service_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_benefits_service_id_foreign` (`service_id`);
ALTER TABLE `service_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_plans_service_id_foreign` (`service_id`);
ALTER TABLE `service_plan_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_plan_features_plan_id_foreign` (`plan_id`);
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_email_unique` (`email`);
ALTER TABLE `tiendas`
  ADD PRIMARY KEY (`id_tienda`),
  ADD UNIQUE KEY `tiendas_codigo_unique` (`codigo`);
ALTER TABLE `tipos_descuento`
  ADD PRIMARY KEY (`id_tipo_descuento`),
  ADD UNIQUE KEY `tipos_descuento_codigo_unique` (`codigo`);
ALTER TABLE `tipos_gastos`
  ADD PRIMARY KEY (`id_tipo_gasto`);
ALTER TABLE `tipos_ingresos_economicos`
  ADD PRIMARY KEY (`id_tipo_ingreso_economico`);
ALTER TABLE `tipos_movimiento_dinero`
  ADD PRIMARY KEY (`id_tipo_movimiento_dinero`),
  ADD UNIQUE KEY `tipos_movimiento_dinero_codigo_unique` (`codigo`);
ALTER TABLE `tipos_venta`
  ADD PRIMARY KEY (`id_tipo_venta`);
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id_tipo_documento`);
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id_transferencia`),
  ADD UNIQUE KEY `transferencias_numero_unique` (`numero`),
  ADD KEY `transferencias_id_tienda_origen_foreign` (`id_tienda_origen`),
  ADD KEY `transferencias_id_tienda_destino_foreign` (`id_tienda_destino`),
  ADD KEY `transferencias_id_usuario_foreign` (`id_usuario`);
ALTER TABLE `transferencias_detalle`
  ADD PRIMARY KEY (`id_transferencia_detalle`),
  ADD KEY `transferencias_detalle_id_transferencia_foreign` (`id_transferencia`),
  ADD KEY `transferencias_detalle_id_variante_foreign` (`id_variante`);
ALTER TABLE `transferencias_dinero`
  ADD PRIMARY KEY (`id_transferencia_dinero`),
  ADD UNIQUE KEY `transferencias_dinero_numero_unique` (`numero`),
  ADD KEY `transferencias_dinero_id_tienda_foreign` (`id_tienda`),
  ADD KEY `transferencias_dinero_id_caja_origen_foreign` (`id_caja_origen`),
  ADD KEY `transferencias_dinero_id_cuenta_origen_foreign` (`id_cuenta_origen`),
  ADD KEY `transferencias_dinero_id_caja_destino_foreign` (`id_caja_destino`),
  ADD KEY `transferencias_dinero_id_cuenta_destino_foreign` (`id_cuenta_destino`),
  ADD KEY `transferencias_dinero_id_usuario_foreign` (`id_usuario`);
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD KEY `usuarios_id_rol_foreign` (`id_rol`);
ALTER TABLE `variantes_atributos`
  ADD PRIMARY KEY (`id_variante_atributo`),
  ADD KEY `fk_va_valor` (`id_valor`);
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`id_vendedor`),
  ADD KEY `vendedores_id_usuario_foreign` (`id_usuario`);
ALTER TABLE `vendedores_tiendas`
  ADD PRIMARY KEY (`id_vendedor_tienda`),
  ADD KEY `idx_vendedor` (`id_vendedor`),
  ADD KEY `idx_tienda` (`id_tienda`),
  ADD KEY `idx_vendedor_estado` (`id_vendedor`,`estado`);
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD UNIQUE KEY `ventas_numero_unique` (`numero`),
  ADD KEY `ventas_id_caja_foreign` (`id_caja`),
  ADD KEY `ventas_id_tienda_foreign` (`id_tienda`),
  ADD KEY `ventas_id_usuario_foreign` (`id_usuario`),
  ADD KEY `ventas_id_cliente_foreign` (`id_cliente`),
  ADD KEY `ventas_id_metodo_pago_foreign` (`id_metodo_pago`),
  ADD KEY `ventas_id_vendedor_foreign` (`id_vendedor`),
  ADD KEY `fk_ventas_usuario_cierre` (`usuario_cierre`),
  ADD KEY `ventas_id_tipo_venta_foreign` (`id_tipo_venta`),
  ADD KEY `ventas_id_motivo_descuento_global_foreign` (`id_motivo_descuento_global`);
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`id_venta_detalle`),
  ADD KEY `ventas_detalle_id_venta_foreign` (`id_venta`),
  ADD KEY `ventas_detalle_id_variante_foreign` (`id_variante`),
  ADD KEY `ventas_detalle_id_motivo_descuento_foreign` (`id_motivo_descuento`),
  ADD KEY `ventas_detalle_id_tipo_descuento_foreign` (`id_tipo_descuento`);
ALTER TABLE `venta_pagos`
  ADD PRIMARY KEY (`id_venta_pago`),
  ADD KEY `fk_venta_pagos_venta` (`id_venta`),
  ADD KEY `fk_venta_pagos_metodo` (`id_metodo_pago`),
  ADD KEY `fk_venta_pagos_cuenta` (`id_cuenta_bancaria`),
  ADD KEY `fk_venta_pagos_usuario` (`id_usuario_registro`);
ALTER TABLE `atributos`
  MODIFY `id_atributo` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `atributos_valores`
  MODIFY `id_valor` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
ALTER TABLE `banners_principales`
  MODIFY `id_banner` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `blogs`
  MODIFY `id_blog` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `blogs_categories`
  MODIFY `id_blogs_categories` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `blogs_tags`
  MODIFY `id_blogs_tags` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `cajas`
  MODIFY `id_caja` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `categorias`
  MODIFY `id_categoria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `clientes`
  MODIFY `id_cliente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `configuraciones`
  MODIFY `id_configuracion` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
ALTER TABLE `contacts`
  MODIFY `id_contact` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `contacts_seguimiento`
  MODIFY `id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `contact_sources`
  MODIFY `id_source` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `contact_statuses`
  MODIFY `id_status` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `cuentas_bancarias`
  MODIFY `id_cuenta_bancaria` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `cuentas_tipo_cuenta`
  MODIFY `id_tipo_cuenta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `destinos_pago`
  MODIFY `id_destino_pago` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `empresa`
  MODIFY `id_empresa` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `gastos`
  MODIFY `id_gasto` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ingresos`
  MODIFY `id_ingreso` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ingresos_detalle`
  MODIFY `id_ingreso_detalle` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ingresos_economicos`
  MODIFY `id_ingreso_economico` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `inventarios`
  MODIFY `id_inventario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `marcas`
  MODIFY `id_marca` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
ALTER TABLE `metodos_pagos`
  MODIFY `id_metodo_pago` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
ALTER TABLE `motivos_descuento`
  MODIFY `id_motivo_descuento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `movimientos`
  MODIFY `id_movimiento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `movimientos_dinero`
  MODIFY `id_movimiento_dinero` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `movimientos_tipo`
  MODIFY `id_tipo_movimiento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `permisos`
  MODIFY `id_permiso` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;
ALTER TABLE `portafolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `preguntas_frecuentes`
  MODIFY `id_pregunta_frecuente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `priorities`
  MODIFY `id_priority` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `productos`
  MODIFY `id_producto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
ALTER TABLE `productos_imagenes`
  MODIFY `id_imagen` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
ALTER TABLE `productos_variantes`
  MODIFY `id_variante` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844;
ALTER TABLE `producto_categorias`
  MODIFY `id_producto_categoria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
ALTER TABLE `promociones`
  MODIFY `id_promocion` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
ALTER TABLE `reglas_descuento`
  MODIFY `id_regla_descuento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `roles`
  MODIFY `id_rol` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `rol_permiso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;
ALTER TABLE `seguimiento_tipos`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `services`
  MODIFY `id_service` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `service_benefits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
ALTER TABLE `service_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
ALTER TABLE `service_plan_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=331;
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tiendas`
  MODIFY `id_tienda` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE `tipos_descuento`
  MODIFY `id_tipo_descuento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `tipos_gastos`
  MODIFY `id_tipo_gasto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `tipos_ingresos_economicos`
  MODIFY `id_tipo_ingreso_economico` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `tipos_movimiento_dinero`
  MODIFY `id_tipo_movimiento_dinero` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `tipos_venta`
  MODIFY `id_tipo_venta` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `tipo_documento`
  MODIFY `id_tipo_documento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `transferencias`
  MODIFY `id_transferencia` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `transferencias_detalle`
  MODIFY `id_transferencia_detalle` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `transferencias_dinero`
  MODIFY `id_transferencia_dinero` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `usuarios`
  MODIFY `id_usuario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `variantes_atributos`
  MODIFY `id_variante_atributo` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2694;
ALTER TABLE `vendedores`
  MODIFY `id_vendedor` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
ALTER TABLE `vendedores_tiendas`
  MODIFY `id_vendedor_tienda` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
ALTER TABLE `ventas`
  MODIFY `id_venta` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ventas_detalle`
  MODIFY `id_venta_detalle` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `venta_pagos`
  MODIFY `id_venta_pago` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `atributos_valores`
  ADD CONSTRAINT `fk_valor_atributo` FOREIGN KEY (`id_atributo`) REFERENCES `atributos` (`id_atributo`) ON DELETE CASCADE;
ALTER TABLE `cajas`
  ADD CONSTRAINT `cajas_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `cajas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `cajas_id_vendedor_foreign` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE SET NULL;
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_id_tipo_documento_foreign` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`) ON DELETE SET NULL;
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_id_priority_foreign` FOREIGN KEY (`id_priority`) REFERENCES `priorities` (`id_priority`),
  ADD CONSTRAINT `contacts_id_service_foreign` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`),
  ADD CONSTRAINT `contacts_id_source_foreign` FOREIGN KEY (`id_source`) REFERENCES `contact_sources` (`id_source`),
  ADD CONSTRAINT `contacts_id_status_foreign` FOREIGN KEY (`id_status`) REFERENCES `contact_statuses` (`id_status`);
ALTER TABLE `cuentas_bancarias`
  ADD CONSTRAINT `cuentas_bancarias_tipo_cuenta_foreign` FOREIGN KEY (`tipo_cuenta`) REFERENCES `cuentas_tipo_cuenta` (`id_tipo_cuenta`) ON DELETE SET NULL;
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_id_caja_foreign` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id_caja`) ON DELETE SET NULL,
  ADD CONSTRAINT `gastos_id_cuenta_bancaria_foreign` FOREIGN KEY (`id_cuenta_bancaria`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`) ON DELETE SET NULL,
  ADD CONSTRAINT `gastos_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  ADD CONSTRAINT `gastos_id_tipo_gasto_foreign` FOREIGN KEY (`id_tipo_gasto`) REFERENCES `tipos_gastos` (`id_tipo_gasto`),
  ADD CONSTRAINT `gastos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_id_proveedor_foreign` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE SET NULL,
  ADD CONSTRAINT `ingresos_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingresos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
ALTER TABLE `ingresos_detalle`
  ADD CONSTRAINT `ingresos_detalle_id_ingreso_foreign` FOREIGN KEY (`id_ingreso`) REFERENCES `ingresos` (`id_ingreso`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingresos_detalle_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE;
ALTER TABLE `ingresos_economicos`
  ADD CONSTRAINT `ingresos_economicos_id_caja_foreign` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id_caja`) ON DELETE SET NULL,
  ADD CONSTRAINT `ingresos_economicos_id_cuenta_bancaria_foreign` FOREIGN KEY (`id_cuenta_bancaria`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`) ON DELETE SET NULL,
  ADD CONSTRAINT `ingresos_economicos_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  ADD CONSTRAINT `ingresos_economicos_id_tipo_ingreso_economico_foreign` FOREIGN KEY (`id_tipo_ingreso_economico`) REFERENCES `tipos_ingresos_economicos` (`id_tipo_ingreso_economico`),
  ADD CONSTRAINT `ingresos_economicos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventarios_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE;
ALTER TABLE `metodos_pagos`
  ADD CONSTRAINT `metodos_pagos_id_destino_pago_foreign` FOREIGN KEY (`id_destino_pago`) REFERENCES `destinos_pago` (`id_destino_pago`) ON DELETE SET NULL;
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_id_tipo_movimiento_foreign` FOREIGN KEY (`id_tipo_movimiento`) REFERENCES `movimientos_tipo` (`id_tipo_movimiento`),
  ADD CONSTRAINT `movimientos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE;
ALTER TABLE `movimientos_dinero`
  ADD CONSTRAINT `movimientos_dinero_id_caja_foreign` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id_caja`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_dinero_id_cuenta_bancaria_foreign` FOREIGN KEY (`id_cuenta_bancaria`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_dinero_id_metodo_pago_foreign` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pagos` (`id_metodo_pago`),
  ADD CONSTRAINT `movimientos_dinero_id_tipo_movimiento_dinero_foreign` FOREIGN KEY (`id_tipo_movimiento_dinero`) REFERENCES `tipos_movimiento_dinero` (`id_tipo_movimiento_dinero`),
  ADD CONSTRAINT `movimientos_dinero_id_usuario_registro_foreign` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuarios` (`id_usuario`);
ALTER TABLE `portafolios`
  ADD CONSTRAINT `portafolios_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id_service`);
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_marca` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id_marca`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_producto_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE SET NULL;
ALTER TABLE `productos_imagenes`
  ADD CONSTRAINT `fk_img_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_img_variante` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE;
ALTER TABLE `productos_variantes`
  ADD CONSTRAINT `fk_variante_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;
ALTER TABLE `reglas_descuento`
  ADD CONSTRAINT `reglas_descuento_id_tipo_descuento_foreign` FOREIGN KEY (`id_tipo_descuento`) REFERENCES `tipos_descuento` (`id_tipo_descuento`),
  ADD CONSTRAINT `reglas_descuento_id_tipo_venta_foreign` FOREIGN KEY (`id_tipo_venta`) REFERENCES `tipos_venta` (`id_tipo_venta`) ON DELETE SET NULL;
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `fk_rol_permiso_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rol_permiso_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;
ALTER TABLE `service_benefits`
  ADD CONSTRAINT `service_benefits_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id_service`) ON DELETE CASCADE;
ALTER TABLE `service_plans`
  ADD CONSTRAINT `service_plans_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id_service`) ON DELETE CASCADE;
ALTER TABLE `service_plan_features`
  ADD CONSTRAINT `service_plan_features_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `service_plans` (`id`) ON DELETE CASCADE;
ALTER TABLE `transferencias`
  ADD CONSTRAINT `transferencias_id_tienda_destino_foreign` FOREIGN KEY (`id_tienda_destino`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferencias_id_tienda_origen_foreign` FOREIGN KEY (`id_tienda_origen`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferencias_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
ALTER TABLE `transferencias_detalle`
  ADD CONSTRAINT `transferencias_detalle_id_transferencia_foreign` FOREIGN KEY (`id_transferencia`) REFERENCES `transferencias` (`id_transferencia`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferencias_detalle_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE;
ALTER TABLE `transferencias_dinero`
  ADD CONSTRAINT `transferencias_dinero_id_caja_destino_foreign` FOREIGN KEY (`id_caja_destino`) REFERENCES `cajas` (`id_caja`) ON DELETE SET NULL,
  ADD CONSTRAINT `transferencias_dinero_id_caja_origen_foreign` FOREIGN KEY (`id_caja_origen`) REFERENCES `cajas` (`id_caja`) ON DELETE SET NULL,
  ADD CONSTRAINT `transferencias_dinero_id_cuenta_destino_foreign` FOREIGN KEY (`id_cuenta_destino`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`) ON DELETE SET NULL,
  ADD CONSTRAINT `transferencias_dinero_id_cuenta_origen_foreign` FOREIGN KEY (`id_cuenta_origen`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`) ON DELETE SET NULL,
  ADD CONSTRAINT `transferencias_dinero_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  ADD CONSTRAINT `transferencias_dinero_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;
ALTER TABLE `variantes_atributos`
  ADD CONSTRAINT `fk_va_valor` FOREIGN KEY (`id_valor`) REFERENCES `atributos_valores` (`id_valor`) ON DELETE CASCADE;
ALTER TABLE `vendedores`
  ADD CONSTRAINT `vendedores_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;
ALTER TABLE `vendedores_tiendas`
  ADD CONSTRAINT `fk_vendedor_tienda_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`),
  ADD CONSTRAINT `fk_vendedor_tienda_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE CASCADE;
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_usuario_cierre` FOREIGN KEY (`usuario_cierre`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `ventas_id_caja_foreign` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id_caja`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_id_metodo_pago_foreign` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pagos` (`id_metodo_pago`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_id_motivo_descuento_global_foreign` FOREIGN KEY (`id_motivo_descuento_global`) REFERENCES `motivos_descuento` (`id_motivo_descuento`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_id_tienda_foreign` FOREIGN KEY (`id_tienda`) REFERENCES `tiendas` (`id_tienda`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_id_tipo_venta_foreign` FOREIGN KEY (`id_tipo_venta`) REFERENCES `tipos_venta` (`id_tipo_venta`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_id_vendedor_foreign` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE SET NULL;
ALTER TABLE `ventas_detalle`
  ADD CONSTRAINT `ventas_detalle_id_motivo_descuento_foreign` FOREIGN KEY (`id_motivo_descuento`) REFERENCES `motivos_descuento` (`id_motivo_descuento`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_detalle_id_tipo_descuento_foreign` FOREIGN KEY (`id_tipo_descuento`) REFERENCES `tipos_descuento` (`id_tipo_descuento`) ON DELETE SET NULL,
  ADD CONSTRAINT `ventas_detalle_id_variante_foreign` FOREIGN KEY (`id_variante`) REFERENCES `productos_variantes` (`id_variante`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_detalle_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE;
ALTER TABLE `venta_pagos`
  ADD CONSTRAINT `fk_venta_pagos_cuenta` FOREIGN KEY (`id_cuenta_bancaria`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`),
  ADD CONSTRAINT `fk_venta_pagos_metodo` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pagos` (`id_metodo_pago`),
  ADD CONSTRAINT `fk_venta_pagos_usuario` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_venta_pagos_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

SET FOREIGN_KEY_CHECKS = 1;
