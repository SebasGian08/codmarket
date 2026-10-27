<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaVariantesProductosSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        $filas = [
            ['producto', 'producto_sku para relacionar'],
        ];

        $productos = Producto::with(['variantes' => function ($query) {
            $query->orderBy('id_variante');
        }])
            ->orderBy('nombre')
            ->get();

        foreach ($productos as $producto) {
            if ($producto->variantes->isEmpty()) {
                $filas[] = [$producto->nombre, ''];
                continue;
            }

            foreach ($producto->variantes as $variante) {
                $filas[] = [$producto->nombre, $variante->sku];
            }
        }

        return $filas;
    }

    public function title(): string
    {
        return 'PRODUCTOS';
    }
}
