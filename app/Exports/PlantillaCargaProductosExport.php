<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaCargaProductosExport implements FromArray
{
    public function array(): array
    {
        return [
            [
                'nombre',
                'descripcion',
                'descripcion_corta',
                'marca',
                'proveedor',
                'categorias',
                'peso',
                'dimensiones',
                'producto_sku',
                'sku',
                'codigo_barras',
                'precio',
                'precio_oferta',
                'costo',
                'stock',
                'destacado',
                'nuevo',
                'estado',
                'atributos'
            ],

            [
                'Labial Matte Rojo',
                'Labial de larga duración',
                'Labial rojo',
                'Maybelline',
                'Proveedor Demo',
                'Labiales,Maquillaje',
                '0.1',
                '10x5x2',
                '',
                'LAB001',
                '123456789',
                '29.90',
                '24.90',
                '15',
                '100',
                '1',
                '1',
                '1',
                'Color: Rojo, Talla: M'
            ],

            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'LAB001',
                'LAB001-NEGRO',
                '',
                '29.90',
                '',
                '15',
                '50',
                '',
                '',
                '1',
                'Color: Negro, Talla: M'
            ]
        ];
    }
}
