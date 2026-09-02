<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlantillaCargaInventarioInstruccionesSheet implements FromArray, WithTitle
{
    public function __construct(private $tiendas)
    {
    }

    public function array(): array
    {
        $filas = [
            ['CARGA MASIVA DE INVENTARIO'],
            ['Completa únicamente la columna cantidad en la pestaña de cada tienda.'],
            ['La cantidad se SUMA al stock existente y se registra como ingreso.'],
            ['No cambies los SKU ni los nombres de las pestañas.'],
            ['Las filas con cantidad vacía, cero o negativa se omiten.'],
            ['Pestañas disponibles:'],
        ];

        foreach ($this->tiendas as $tienda) {
            $filas[] = [trim((string) $tienda->codigo), $tienda->nombre];
        }

        return $filas;
    }

    public function title(): string
    {
        return 'INSTRUCCIONES';
    }
}
