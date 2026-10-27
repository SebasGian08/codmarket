<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaVariantesDatosSheet implements FromArray, WithTitle
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
                'atributos',
            ],
        ];
    }

    public function title(): string
    {
        return 'VARIANTES';
    }
}
