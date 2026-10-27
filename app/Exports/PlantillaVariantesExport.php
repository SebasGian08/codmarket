<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlantillaVariantesExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new PlantillaVariantesDatosSheet(),
            new PlantillaVariantesProductosSheet(),
        ];
    }
}
