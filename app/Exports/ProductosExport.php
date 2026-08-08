<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductosExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Producto::with(['marca', 'proveedor', 'categorias', 'variantes'])
            ->orderBy('id_producto');
    }

    public function headings(): array
    {
        return [
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
        ];
    }

    public function map($producto): array
    {
        $variante = $producto->variantes->first();

        return [
            $producto->nombre,
            $producto->descripcion,
            $producto->descripcion_corta,
            $producto->marca->nombre ?? null,
            $producto->proveedor->nombre ?? null,
            $producto->categorias->pluck('nombre')->implode(','),
            $producto->peso,
            $producto->dimensiones,
            $variante->sku ?? null,
            $variante->codigo_barras ?? null,
            $variante->precio ?? null,
            $variante->precio_oferta ?? null,
            $variante->costo ?? null,
            $variante->stock ?? null,
            $producto->destacado,
            $producto->nuevo,
            $producto->estado
        ];
    }
}
