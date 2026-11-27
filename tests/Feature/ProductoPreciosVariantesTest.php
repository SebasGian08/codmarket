<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Usuario;
use Tests\TestCase;

class ProductoPreciosVariantesTest extends TestCase
{
    public function testEditarProductoPuedeAplicarPreciosATodasLasVariantes()
    {
        $admin = Usuario::where('estado', 1)->firstOrFail();
        $producto = Producto::with(['variantes', 'categorias'])
            ->whereHas('variantes')
            ->firstOrFail();
        $variantePrincipal = $producto->variantes->first();
        $preciosOriginales = $producto->variantes->mapWithKeys(function ($variante) {
            return [$variante->id_variante => [
                'precio' => $variante->precio,
                'precio_oferta' => $variante->precio_oferta,
                'precio_mayorista' => $variante->precio_mayorista,
                'costo' => $variante->costo,
            ]];
        });

        $otraVariante = ProductoVariante::create([
            'id_producto' => $producto->id_producto,
            'sku' => 'TEST-PRECIOS-' . uniqid(),
            'precio' => 80,
            'precio_oferta' => 70,
            'precio_mayorista' => 60,
            'costo' => 50,
            'stock' => 0,
            'estado' => 1,
        ]);

        $this->actingAs($admin);

        try {
            $response = $this->put(
                route('admin.productos.update', $producto->id_producto),
                $this->datosActualizacion($producto, $variantePrincipal, [
                    'precio_principal' => 11,
                    'precio_oferta_principal' => 10,
                    'precio_mayorista_principal' => 9,
                    'costo_principal' => 8,
                ])
            );

            $response->assertSessionHas('success', 'Producto actualizado');
            $otraVariante->refresh();
            $this->assertSame(80.0, (float) $otraVariante->precio);
            $this->assertSame(70.0, (float) $otraVariante->precio_oferta);
            $this->assertSame(60.0, (float) $otraVariante->precio_mayorista);
            $this->assertSame(50.0, (float) $otraVariante->costo);

            $response = $this->put(
                route('admin.productos.update', $producto->id_producto),
                $this->datosActualizacion($producto, $variantePrincipal, [
                    'precio_principal' => 21,
                    'precio_oferta_principal' => 20,
                    'precio_mayorista_principal' => 19,
                    'costo_principal' => 18,
                    'afectar_todas_variantes' => 1,
                ])
            );

            $response->assertSessionHas('success', 'Producto actualizado');
            foreach ($producto->variantes()->get() as $variante) {
                $this->assertSame(21.0, (float) $variante->precio);
                $this->assertSame(20.0, (float) $variante->precio_oferta);
                $this->assertSame(19.0, (float) $variante->precio_mayorista);
                $this->assertSame(18.0, (float) $variante->costo);
            }
        } finally {
            foreach ($preciosOriginales as $idVariante => $precios) {
                ProductoVariante::where('id_variante', $idVariante)->update($precios);
            }

            $otraVariante->delete();
        }
    }

    private function datosActualizacion(Producto $producto, ProductoVariante $variante, array $precios): array
    {
        return array_merge([
            'nombre' => $producto->nombre,
            'sku_principal' => $variante->sku,
            'codigo_barras' => $variante->codigo_barras,
            'descripcion' => $producto->descripcion,
            'descripcion_corta' => $producto->descripcion_corta,
            'id_marca' => $producto->id_marca,
            'id_proveedor' => $producto->id_proveedor,
            'peso' => $producto->peso,
            'dimensiones' => $producto->dimensiones,
            'destacado' => $producto->destacado,
            'nuevo' => $producto->nuevo,
            'estado' => $producto->estado,
            'categorias' => $producto->categorias->pluck('id_categoria')->all(),
        ], $precios);
    }
}
