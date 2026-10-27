<?php

namespace App\Exports;

use App\Models\ProductoVariante;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaActualizacionPreciosExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'sku',
            'producto',
            'variante',
            'precio',
            'precio_oferta',
            'precio_mayorista',
            'costo',
        ];
    }

    public function array(): array
    {
        return ProductoVariante::with(['producto', 'atributos.atributo'])
            ->where('estado', 1)
            ->whereHas('producto', function ($query) {
                $query->where('estado', 1);
            })
            ->orderBy('id_producto')
            ->orderBy('id_variante')
            ->get()
            ->map(function (ProductoVariante $variante) {
                $atributos = $variante->atributos
                    ->map(function ($atributo) {
                        return ($atributo->atributo->nombre ?? '') . ': ' . $atributo->valor;
                    })
                    ->filter()
                    ->implode(', ');

                return [
                    $variante->sku,
                    $variante->producto->nombre,
                    $atributos ?: 'Única',
                    $variante->precio,
                    $variante->precio_oferta,
                    $variante->precio_mayorista,
                    $variante->costo,
                ];
            })
            ->all();
    }

    public function title(): string
    {
        return 'PRECIOS';
    }
}
