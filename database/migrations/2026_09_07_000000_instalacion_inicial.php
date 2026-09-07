<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstalacionInicial extends Migration
{
    public function up()
    {
        if (Schema::hasTable('roles')) {
            return;
        }

        $sql = file_get_contents(__DIR__ . '/instalacion_inicial.sql');

        if ($sql === false) {
            throw new RuntimeException('No se pudo leer instalacion_inicial.sql');
        }

        DB::unprepared($sql);
    }

    public function down()
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        $tablas = [
            'ventas_detalle',
            'ventas',
            'venta_pagos',
            'vendedores_tiendas',
            'vendedores',
            'variantes_atributos',
            'usuarios',
            'transferencias_dinero',
            'transferencias_detalle',
            'transferencias',
            'trabajos_realizados',
            'tipos_venta',
            'tipos_movimiento_dinero',
            'tipos_ingresos_economicos',
            'tipos_gastos',
            'tipos_descuento',
            'tipo_documento',
            'tiendas',
            'subscriptions',
            'services',
            'service_plans',
            'service_plan_features',
            'service_benefits',
            'seguimiento_tipos',
            'rubros',
            'roles',
            'rol_permiso',
            'reglas_descuento',
            'proveedores',
            'promociones',
            'productos_variantes',
            'productos_imagenes',
            'productos',
            'producto_categorias',
            'priorities',
            'preguntas_frecuentes',
            'portafolios',
            'permisos',
            'movimientos_tipo',
            'movimientos_dinero',
            'movimientos',
            'motivos_descuento',
            'migrations',
            'metodos_pagos',
            'marcas',
            'inventarios',
            'ingresos_economicos',
            'ingresos_detalle',
            'ingresos',
            'gastos',
            'empresa',
            'destinos_pago',
            'cuentas_tipo_cuenta',
            'cuentas_bancarias',
            'contacts_seguimiento',
            'contacts',
            'contact_statuses',
            'contact_sources',
            'configuraciones',
            'clientes',
            'categorias',
            'cajas',
            'blogs_tags',
            'blogs_categories',
            'blogs_blog_tag',
            'blogs',
            'banners_principales',
            'atributos_valores',
            'atributos',
        ];

        DB::unprepared('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tablas as $tabla) {
            DB::unprepared('DROP TABLE IF EXISTS `' . $tabla . '`;');
        }

        DB::unprepared('SET FOREIGN_KEY_CHECKS = 1;');
    }
}