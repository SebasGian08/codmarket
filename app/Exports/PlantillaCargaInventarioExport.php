<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaCargaInventarioExport implements FromArray
{
    public function array(): array
    {
        return [
            [
                'sku',
                'producto',
                'cantidad'
            ],
            [
                'LAB001-ROJO',
                'Ejemplo: Lámpara Led Roja',
                '25'
            ]
        ];
    }
}
