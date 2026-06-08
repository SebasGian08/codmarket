<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaProductosExport implements FromArray
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
                'sku',
                'codigo_barras',
                'precio',
                'precio_oferta',
                'costo',
                'stock',
                'destacado',
                'nuevo',
                'estado'
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
                'LAB001',
                '123456789',
                '29.90',
                '24.90',
                '15',
                '100',
                '1',
                '1',
                '1'
            ]

        ];
    }
}