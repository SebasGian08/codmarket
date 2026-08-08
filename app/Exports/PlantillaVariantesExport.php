<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaVariantesExport implements FromArray
{
    public function array(): array
    {
        return [

            [
                'producto_sku',
                'sku',
                'codigo_barras',
                'precio',
                'precio_oferta',
                'costo',
                'stock',
                'estado',
                'atributos'
            ],

            [
                'LAB001',
                'LAB001-ROJO',
                '987654321',
                '32.90',
                '27.90',
                '16',
                '50',
                '1',
                'Color: Rojo, Talla: M'
            ]

        ];
    }
}
