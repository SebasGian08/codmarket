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

        $nuevas = [];

        foreach ([1 => 'L', 4 => 'S', 5 => 'M'] as $idValor => $valor) {
            $nuevas[] = ProductoVariante::create([
                'id_producto' => $producto->id_producto,
                'sku' => "TALLA-{$token}-{$valor}",
                'precio' => 10,
                'stock' => 1,
                'estado' => 1,
            ]);
        }

        $nuevas[0]->atributos()->sync([1]); // L
        $nuevas[1]->atributos()->sync([4]); // S
        $nuevas[2]->atributos()->sync([5]); // M

        $varianteActiva = $producto->variantes->first();

        $varianteActiva->atributos()->sync([1]); // la variante activa es talla L

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
            foreach ($nuevas as $var) {
                $var->atributos()->detach();
                $var->delete();
            }

            $varianteActiva->atributos()->detach();
        }
    }
}
