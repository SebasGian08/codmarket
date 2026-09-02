<?php

namespace App\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaCargaInventarioTiendaSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private $tienda, private $variantes)
    {
    }

    public function headings(): array
    {
        return ['sku', 'producto', 'variante', 'cantidad'];
    }

    public function array(): array
    {
        return $this->variantes->map(function ($variante) {
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
                '',
            ];
        })->all();
    }

    public function title(): string
    {
        $codigo = trim((string) $this->tienda->codigo);
        $titulo = $codigo ?: 'TIENDA-' . $this->tienda->id_tienda;

        return Str::limit(str_replace([':', '\\', '/', '?', '*', '[', ']'], '-', $titulo), 31, '');
    }
}
