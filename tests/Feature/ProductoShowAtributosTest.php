<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\ProductoVariante;

class ProductoShowAtributosTest extends TestCase
{
    public function testProductoMuestraAtributos()
    {
        $producto = Producto::with('variantes')->first();

        $this->assertNotNull($producto);

        $token = time();

        $variantes = [];

        foreach ([1 => 'L', 4 => 'S', 5 => 'M'] as $idValor => $valor) {
            $variantes[] = ProductoVariante::create([
                'id_producto' => $producto->id_producto,
                'sku' => "TALLA-{$token}-{$valor}",
                'precio' => 10,
                'stock' => 1,
                'estado' => 1,
            ])->atributos()->sync([$idValor]);
        }

        $variantes = ProductoVariante::where('sku', 'like', "TALLA-{$token}-%")->get();

        try {
            $response = $this->get(route('producto.show', $producto->slug));

            $response->assertStatus(200);

            $response->assertSee('talla', false);

            $response->assertSee('attribute-chip', false);

            $response->assertSee('attribute-chip-selected', false);

            foreach (['L', 'S', 'M'] as $valor) {
                $response->assertSee($valor, false);
            }

        } finally {
            foreach ($variantes as $var) {
                $var->atributos()->detach();
                $var->delete();
            }
        }
    }
}
