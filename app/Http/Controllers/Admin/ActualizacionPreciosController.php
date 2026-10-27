<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PlantillaActualizacionPreciosExport;
use App\Http\Controllers\Controller;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ActualizacionPreciosController extends Controller
{
    private const COLUMNAS = [
        'sku',
        'producto',
        'variante',
        'precio',
        'precio_oferta',
        'precio_mayorista',
        'costo',
    ];

    public function index()
    {
        return view('admin.inventarios.precios');
    }

    public function plantilla()
    {
        return Excel::download(
            new PlantillaActualizacionPreciosExport(),
            'Plantilla_Actualizacion_Precios.xlsx'
        );
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $hojas = Excel::toArray([], $request->file('archivo'));
            $filas = $hojas[0] ?? [];

            if (count($filas) < 2) {
                throw new \Exception('El archivo no contiene variantes para actualizar.');
            }

            $encabezados = array_map(function ($valor) {
                return mb_strtolower(trim((string) $valor));
            }, array_slice($filas[0], 0, count(self::COLUMNAS)));

            if ($encabezados !== self::COLUMNAS) {
                throw new \Exception('No cambies los encabezados de la plantilla descargada.');
            }

            $variantes = ProductoVariante::where('estado', 1)
                ->whereHas('producto', function ($query) {
                    $query->where('estado', 1);
                })
                ->get()
                ->keyBy('sku');

            $vistos = [];
            $actualizaciones = [];

            foreach (array_slice($filas, 1, null, true) as $indice => $fila) {
                $fila = array_pad($fila, count(self::COLUMNAS), null);
                $valoresFila = array_slice($fila, 0, count(self::COLUMNAS));
                $filaVacia = count(array_filter($valoresFila, function ($valor) {
                    return $valor !== null && trim((string) $valor) !== '';
                })) === 0;

                if ($filaVacia) {
                    continue;
                }

                $numeroFila = $indice + 1;
                $sku = trim((string) ($fila[0] ?? ''));

                if ($sku === '') {
                    throw new \Exception("Falta el SKU en la fila {$numeroFila}.");
                }

                if (isset($vistos[$sku])) {
                    throw new \Exception("El SKU {$sku} está repetido en la fila {$numeroFila}.");
                }

                $variante = $variantes->get($sku);
                if (!$variante) {
                    throw new \Exception("El SKU {$sku} no corresponde a una variante activa en la fila {$numeroFila}.");
                }

                $precio = $fila[3] ?? null;
                if ($precio === null || trim((string) $precio) === '' || !is_numeric($precio) || (float) $precio < 0) {
                    throw new \Exception("El precio debe ser numérico y no negativo en la fila {$numeroFila}.");
                }

                $datos = ['precio' => $precio];
                foreach ([
                    'precio_oferta' => 4,
                    'precio_mayorista' => 5,
                    'costo' => 6,
                ] as $columna => $posicion) {
                    $valor = $fila[$posicion] ?? null;

                    if ($valor === null || trim((string) $valor) === '') {
                        $datos[$columna] = null;
                        continue;
                    }

                    if (!is_numeric($valor) || (float) $valor < 0) {
                        throw new \Exception("El valor de {$columna} debe ser numérico y no negativo en la fila {$numeroFila}.");
                    }

                    $datos[$columna] = $valor;
                }

                $vistos[$sku] = true;
                $actualizaciones[] = [$variante, $datos];
            }

            if (empty($actualizaciones)) {
                throw new \Exception('No hay filas con variantes para actualizar.');
            }

            DB::transaction(function () use ($actualizaciones) {
                foreach ($actualizaciones as [$variante, $datos]) {
                    $variante->update($datos);
                }
            });

            return back()->with('success', count($actualizaciones) . ' variantes actualizadas correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
