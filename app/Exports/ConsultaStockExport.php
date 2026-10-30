<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsultaStockExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    private Builder $consulta;

    public function __construct(Builder $consulta)
    {
        $this->consulta = $consulta;
    }

    public function query()
    {
        return $this->consulta;
    }

    public function headings(): array
    {
        return ['Producto', 'Variante', 'SKU', 'Tienda', 'Stock', 'Estado'];
    }

    public function map($registro): array
    {
        return [
            $this->textoSeguro($registro->producto),
            $this->textoSeguro($registro->variante),
            $this->textoSeguro($registro->sku),
            $this->textoSeguro($registro->codigo_tienda . ' - ' . $registro->tienda),
            (int) $registro->stock,
            $registro->estado_stock,
        ];
    }

    private function textoSeguro(?string $valor): string
    {
        $valor = (string) $valor;

        return preg_match('/^[=+\-@]/', $valor) ? "'" . $valor : $valor;
    }
}
