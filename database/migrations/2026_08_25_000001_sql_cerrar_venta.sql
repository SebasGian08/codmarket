-- =============================================
-- MIGRACIÓN: Cerrar Venta / Pagos Múltiples
-- Fecha: 2026-08-25
-- Ejecutar en orden
-- =============================================

-- 1. Tabla cuentas_bancarias
CREATE TABLE IF NOT EXISTS `cuentas_bancarias` (
  `id_cuenta_bancaria` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `tipo` VARCHAR(50) NOT NULL DEFAULT 'caja',
  `moneda` VARCHAR(10) NOT NULL DEFAULT 'PEN',
  `saldo` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `estado` TINYINT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos iniciales de ejemplo (ajustar según necesidad)
INSERT INTO `cuentas_bancarias` (`nombre`, `tipo`, `moneda`, `saldo`, `estado`, `created_at`, `updated_at`) VALUES
('Caja Principal', 'caja', 'PEN', 0, 1, NOW(), NOW()),
('Caja Secundaria', 'caja', 'PEN', 0, 1, NOW(), NOW()),
('BCP Cuenta Soles', 'banco', 'PEN', 0, 1, NOW(), NOW()),
('Yape Personal', 'billetera_virtual', 'PEN', 0, 1, NOW(), NOW());

-- 2. Agregar campos de cierre a ventas
ALTER TABLE `ventas`
  ADD COLUMN `estado_cobro` ENUM('pendiente','cerrado') NOT NULL DEFAULT 'pendiente' AFTER `estado`,
  ADD COLUMN `fecha_cierre` TIMESTAMP NULL AFTER `estado_cobro`,
  ADD COLUMN `usuario_cierre` BIGINT UNSIGNED NULL AFTER `fecha_cierre`;

ALTER TABLE `ventas`
  ADD INDEX `idx_estado_cobro` (`estado_cobro`);

-- 3. Hacer id_metodo_pago nullable
ALTER TABLE `ventas`
  MODIFY COLUMN `id_metodo_pago` INT UNSIGNED NULL;

-- 4. Tabla venta_pagos
CREATE TABLE IF NOT EXISTS `venta_pagos` (
  `id_venta_pago` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_venta` INT UNSIGNED NOT NULL,
  `id_metodo_pago` INT UNSIGNED NOT NULL,
  `id_cuenta_bancaria` INT UNSIGNED NOT NULL,
  `monto` DECIMAL(12,2) NOT NULL,
  `moneda` VARCHAR(10) NOT NULL DEFAULT 'PEN',
  `id_usuario_registro` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  CONSTRAINT `fk_venta_pagos_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE,
  CONSTRAINT `fk_venta_pagos_metodo` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pagos` (`id_metodo_pago`) ON DELETE RESTRICT,
  CONSTRAINT `fk_venta_pagos_cuenta` FOREIGN KEY (`id_cuenta_bancaria`) REFERENCES `cuentas_bancarias` (`id_cuenta_bancaria`) ON DELETE RESTRICT,
  CONSTRAINT `fk_venta_pagos_usuario` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  INDEX `idx_venta_pago_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Marcar ventas existentes como cerradas (retroactivo)
UPDATE `ventas` SET `estado_cobro` = 'cerrado' WHERE `estado` = 1;
