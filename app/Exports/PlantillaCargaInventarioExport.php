<?php

namespace App\Exports;

use App\Models\ProductoVariante;
use App\Models\Tienda;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlantillaCargaInventarioExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $variantes = ProductoVariante::with(['producto', 'atributos.atributo'])
            ->where('estado', 1)
            ->whereHas('producto', function ($query) {
                $query->where('estado', 1);
            })
            ->orderBy('id_producto')
            ->orderBy('id_variante')
            ->get();

        $tiendas = Tienda::where('estado', 1)->orderBy('nombre')->get();
        $sheets = [new PlantillaCargaInventarioInstruccionesSheet($tiendas)];

        foreach ($tiendas as $tienda) {
            $sheets[] = new PlantillaCargaInventarioTiendaSheet($tienda, $variantes);
        }

        return $sheets;
    }
}
