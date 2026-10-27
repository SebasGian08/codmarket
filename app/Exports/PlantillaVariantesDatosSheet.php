<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaVariantesDatosSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        $filas = [
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
                'producto',
            ],
        ];

        $productos = Producto::with(['variantes' => function ($query) {
            $query->orderBy('id_variante');
        }])
            ->orderBy('nombre')
            ->get();

        foreach ($productos as $producto) {
            $variantePrincipal = $producto->variantes->first();
            $productoSku = $variantePrincipal && $variantePrincipal->sku
                ? $variantePrincipal->sku
                : $producto->nombre;

            $filas[] = [
                $productoSku,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $producto->nombre,
            ];
        }

        return $filas;
    }

    public function title(): string
    {
        return 'VARIANTES';
    }
}
