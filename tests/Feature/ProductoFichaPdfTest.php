<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProductoFichaPdfTest extends TestCase
{
    public function testAdminPuedeSubirFichaPdfYSeMuestraDescargaEnProducto()
    {
        $admin = Usuario::where('estado', 1)->firstOrFail();
        $producto = Producto::with(['variantes', 'categorias'])
            ->whereHas('variantes')
            ->firstOrFail();
        $variante = $producto->variantes->first();
        $rutaAnterior = $producto->ficha_pdf;
        $rutaNueva = null;

        $this->actingAs($admin);

        try {
            $response = $this->put(route('admin.productos.update', $producto->id_producto), [
                'nombre' => $producto->nombre,
                'sku_principal' => $variante->sku,
                'precio_principal' => $variante->precio,
                'precio_mayorista_principal' => $variante->precio_mayorista,
                'codigo_barras' => $variante->codigo_barras,
                'precio_oferta_principal' => $variante->precio_oferta,
                'costo_principal' => $variante->costo,
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
                'ficha_pdf' => UploadedFile::fake()->createWithContent(
                    'manual.pdf',
                    "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF"
                ),
            ]);

            $response->assertSessionHas('success', 'Producto actualizado');

            $producto->refresh();
            $rutaNueva = $producto->ficha_pdf;

            $this->assertNotEmpty($rutaNueva);
            $this->assertFileExists(public_path($rutaNueva));

            $this->get(route('producto.show', $producto->slug))
                ->assertOk()
                ->assertSee('Descargar ficha o manual');
        } finally {
            $producto->refresh();
            $rutaNueva = $producto->ficha_pdf;
            $producto->ficha_pdf = $rutaAnterior;
            $producto->save();

            if ($rutaNueva && $rutaNueva !== $rutaAnterior) {
                $archivo = public_path('uploads/productos/fichas/' . basename($rutaNueva));
                File::delete($archivo);
            }
        }
    }
}
