<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\UploadedFile;

class ProductoImagenUploadTest extends TestCase
{
    public function testSubidaAjaxDevuelveJsonDeExito()
    {
        $admin = Usuario::where('estado', 1)->first();
        $this->actingAs($admin);

        $producto = Producto::with('variantes')->first();

        $file = new UploadedFile(
            base_path('uploads/banners/1777682393_69f547d9524e0.webp'),
            'foto.png',
            null,
            null,
            true
        );

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('admin.producto_imagen.store', $producto->id_producto), [
                'id_producto' => $producto->id_producto,
                'id_variante' => $producto->variantes()->first()->id_variante ?? null,
                'principal' => 0,
                'orden' => 0,
                'imagen' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'image' => ['id', 'url', 'sku', 'principal', 'delete_url'],
        ]);

        $imagen = ProductoImagen::where('id_producto', $producto->id_producto)
            ->orderBy('id_imagen', 'desc')
            ->first();

        $this->assertNotNull($imagen);

        $ruta = public_path($imagen->url);

        $imagen->delete();

        if (file_exists($ruta)) {
            @unlink($ruta);
        }
    }

    public function testMarcarPrincipalViaUpdate()
    {
        $admin = Usuario::where('estado', 1)->first();
        $this->actingAs($admin);

        $producto = Producto::with('variantes')->first();

        $file = new UploadedFile(
            base_path('uploads/banners/1777682393_69f547d9524e0.webp'),
            'foto.png',
            null,
            null,
            true
        );

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('admin.producto_imagen.store', $producto->id_producto), [
                'id_producto' => $producto->id_producto,
                'id_variante' => $producto->variantes()->first()->id_variante ?? null,
                'principal' => 0,
                'orden' => 0,
                'imagen' => $file,
            ]);

        $imagen = ProductoImagen::where('id_producto', $producto->id_producto)
            ->orderBy('id_imagen', 'desc')
            ->first();

        $this->assertNotNull($imagen);

        $this->post(route('admin.producto_imagen.update', [
            'producto' => $producto->id_producto,
            'id' => $imagen->id_imagen,
        ]), [
            '_method' => 'PUT',
            'principal' => 1,
            'id_variante' => $producto->variantes()->first()->id_variante ?? '',
            'orden' => 5,
        ]);

        $imagen->refresh();

        $this->assertEquals(1, $imagen->principal);
        $this->assertEquals(5, $imagen->orden);

        $ruta = public_path($imagen->url);
        $imagen->delete();

        if (file_exists($ruta)) {
            @unlink($ruta);
        }
    }
}
