<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\PlantillaCargaInventarioExport;
use App\Models\ProductoVariante;
use App\Models\Tienda;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CargaInventarioController extends Controller
{
    protected $inventario;

    public function __construct(InventarioService $inventario)
    {
        $this->inventario = $inventario;
    }

    public function index()
    {
        $tiendas = Tienda::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('admin.inventarios.carga', compact('tiendas'));
    }

    public function plantilla()
    {
        return Excel::download(new PlantillaCargaInventarioExport(), 'Plantilla_Carga_Inventario.xlsx');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls',
            'id_tienda' => 'required|exists:tiendas,id_tienda',
        ]);

        try {
            DB::beginTransaction();

            $rows = Excel::toArray([], $request->file('archivo'));

            if (empty($rows) || empty($rows[0])) {
                throw new \Exception('El archivo no contiene datos.');
            }

            $datos = $rows[0];
            unset($datos[0]); // eliminar cabecera

            $procesadas = 0;
            $sinSku = 0;
            $sinVariante = 0;

            $tienda = Tienda::findOrFail($request->id_tienda);

            foreach ($datos as $fila) {
                $sku = trim($fila[0] ?? '');

                if (!$sku) {
                    $sinSku++;
                    continue;
                }

                $variante = ProductoVariante::where('sku', $sku)->first();

                if (!$variante) {
                    $sinVariante++;
                    continue;
                }

                $cantidad = (int) ($fila[2] ?? 0);

                if ($cantidad <= 0) {
                    $sinSku++;
                    continue;
                }

                $this->inventario->aplicar(
                    $variante->id_variante,
                    $tienda->id_tienda,
                    'ingreso',
                    $cantidad,
                    null,
                    auth()->id(),
                    'Carga masiva de inventario: ' . $sku . ' (' . $tienda->nombre . ')'
                );

                $procesadas++;
            }

            DB::commit();

            return back()->with('success', "Carga completada: {$procesadas} variantes procesadas, {$sinVariante} sin variante, {$sinSku} filas omitidas.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
