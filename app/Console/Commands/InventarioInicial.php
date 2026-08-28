<?php

namespace App\Console\Commands;

use App\Models\Inventario;
use App\Models\Movimiento;
use App\Models\ProductoVariante;
use App\Models\Tienda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InventarioInicial extends Command
{
    protected $signature = 'inventario:inicial {--tienda= : ID de la tienda destino (por defecto la principal)}';

    protected $description = 'Carga el stock inicial: crea el inventario por tienda y los movimientos a partir de productos_variantes.stock';

    public function handle()
    {
        $tienda = Tienda::find($this->option('tienda'))
            ?? Tienda::where('es_principal', 1)->first()
            ?? Tienda::first();

        if (!$tienda) {
            $this->error('No hay tiendas registradas.');
            return 1;
        }

        $variantes = ProductoVariante::where('stock', '>', 0)->get();

        $cargados = 0;
        $ajustados = 0;
        $sincronizados = 0;

        DB::beginTransaction();

        try {

            foreach ($variantes as $variante) {

                $inventario = Inventario::firstOrNew([
                    'id_variante' => $variante->id_variante,
                    'id_tienda' => $tienda->id_tienda,
                ]);

                if (!$inventario->exists) {

                    $inventario->cantidad = $variante->stock;
                    $inventario->save();

                    Movimiento::create([
                        'id_variante'        => $variante->id_variante,
                        'id_tienda'          => $tienda->id_tienda,
                        'id_tipo_movimiento' => 1, // ingreso
                        'cantidad'           => $variante->stock,
                        'fecha'              => now(),
                        'observacion'        => 'Carga inicial de stock desde productos_variantes',
                    ]);

                    $cargados++;

                } elseif ((int) $inventario->cantidad !== (int) $variante->stock) {

                    $anterior = (int) $inventario->cantidad;
                    $diferencia = (int) $variante->stock - $anterior;

                    $inventario->cantidad = $variante->stock;
                    $inventario->save();

                    Movimiento::create([
                        'id_variante'        => $variante->id_variante,
                        'id_tienda'          => $tienda->id_tienda,
                        'id_tipo_movimiento' => 5, // ajuste
                        'cantidad'           => $diferencia,
                        'fecha'              => now(),
                        'observacion'        => 'Ajuste de carga inicial: stock global ' . $variante->stock . ', tienda tenía ' . $anterior,
                    ]);

                    $ajustados++;

                } else {

                    $sincronizados++;
                }
            }

            DB::commit();

            $this->info("Tienda: {$tienda->codigo} - {$tienda->nombre}");
            $this->info("Variantes con carga inicial nueva: {$cargados}");
            $this->info("Variantes ajustadas (desfase corregido): {$ajustados}");
            $this->info("Variantes ya sincronizadas: {$sincronizados}");

            return 0;

        } catch (\Exception $e) {

            DB::rollBack();
            $this->error($e->getMessage());
            return 1;
        }
    }
}
