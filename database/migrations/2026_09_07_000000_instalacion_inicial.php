<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstalacionInicial extends Migration
{
    private const TABLAS = [
        'atributos', 'atributos_valores', 'banners_principales', 'blogs', 'blogs_blog_tag',
        'blogs_categories', 'blogs_tags', 'cajas', 'categorias', 'clientes', 'configuraciones',
        'contacts', 'contacts_seguimiento', 'contact_sources', 'contact_statuses',
        'cuentas_bancarias', 'cuentas_tipo_cuenta', 'destinos_pago', 'empresa', 'gastos',
        'ingresos', 'ingresos_detalle', 'ingresos_economicos', 'inventarios', 'marcas',
        'metodos_pagos', 'motivos_descuento', 'movimientos', 'movimientos_dinero',
        'movimientos_tipo', 'permisos', 'portafolios', 'preguntas_frecuentes', 'priorities',
        'producto_categorias', 'productos', 'productos_imagenes', 'productos_variantes',
        'promociones', 'proveedores', 'reglas_descuento', 'rol_permiso', 'roles', 'rubros',
        'seguimiento_tipos', 'service_benefits', 'service_plan_features', 'service_plans',
        'services', 'subscriptions', 'tiendas', 'tipo_documento', 'tipos_descuento',
        'tipos_gastos', 'tipos_ingresos_economicos', 'tipos_movimiento_dinero', 'tipos_venta',
        'trabajos_realizados', 'transferencias', 'transferencias_detalle',
        'transferencias_dinero', 'usuarios', 'variantes_atributos', 'vendedores',
        'vendedores_tiendas', 'venta_pagos', 'ventas', 'ventas_detalle',
    ];

    public function up()
    {
        if ($this->instalacionExistente()) {
            return;
        }

        $sql = file_get_contents(__DIR__ . '/instalacion_inicial.sql');

        if ($sql === false) {
            throw new RuntimeException('No se pudo leer instalacion_inicial.sql');
        }

        foreach (preg_split('/;\s*/', trim($sql)) as $sentencia) {
            if (trim($sentencia) === '') {
                continue;
            }

            DB::unprepared($sentencia . ';');
        }
    }

    private function instalacionExistente(): bool
    {
        foreach (self::TABLAS as $tabla) {
            if (Schema::hasTable($tabla)) {
                return true;
            }
        }

        return false;
    }

    public function down()
    {
        if (!$this->instalacionExistente()) {
            return;
        }

        DB::unprepared('SET FOREIGN_KEY_CHECKS = 0;');

        foreach (self::TABLAS as $tabla) {
            DB::unprepared('DROP TABLE IF EXISTS `' . $tabla . '`;');
        }

        DB::unprepared('DROP TABLE IF EXISTS `migrations`;');

        DB::unprepared('SET FOREIGN_KEY_CHECKS = 1;');
    }
}