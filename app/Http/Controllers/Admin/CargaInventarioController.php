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
        ]);

        try {
            DB::beginTransaction();

            $rows = Excel::toArray([], $request->file('archivo'));

            if (empty($rows)) {
                throw new \Exception('El archivo no contiene datos.');
            }

            $procesadas = 0;
            $sinSku = 0;
            $sinVariante = 0;
            $tiendas = Tienda::where('estado', 1)->get()->keyBy(function ($tienda) {
                return mb_strtolower(trim((string) $tienda->codigo));
            });

            foreach ($rows as $indiceHoja => $datos) {
                if (empty($datos)) {
                    continue;
                }

                // Excel::toArray no expone el nombre de la hoja; se obtiene del lector.
                $nombreHoja = $this->nombreHoja($request->file('archivo'), $indiceHoja);
                $codigoTienda = mb_strtolower(trim($nombreHoja));

                if ($codigoTienda === 'instrucciones') {
                    continue;
                }

                $tienda = $tiendas->get($codigoTienda);
                if (!$tienda) {
                    throw new \Exception('La pestaña "' . $nombreHoja . '" no corresponde a una tienda activa.');
                }

                unset($datos[0]);
                foreach ($datos as $fila) {
                    $sku = trim((string) ($fila[0] ?? ''));
                    $cantidad = (int) ($fila[3] ?? 0);

                    if (!$sku || $cantidad <= 0) {
                        $sinSku++;
                        continue;
                    }

                    $variante = ProductoVariante::where('sku', $sku)->first();
                    if (!$variante) {
                        $sinVariante++;
                        continue;
                    }

                    $this->inventario->aplicar($variante->id_variante, $tienda->id_tienda, 'ingreso', $cantidad, null, auth()->id(), 'Carga masiva de inventario: ' . $sku . ' (' . $tienda->nombre . ')');
                    $procesadas++;
                }
            }

            DB::commit();

            return back()->with('success', "Carga completada: {$procesadas} variantes procesadas en pestañas, {$sinVariante} sin variante, {$sinSku} filas omitidas.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function nombreHoja($archivo, $indice): string
    {
        $lector = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($archivo->getRealPath());
        $lector->setReadDataOnly(true);
        $hojas = $lector->listWorksheetNames($archivo->getRealPath());

        return $hojas[$indice] ?? '';
    }
}
