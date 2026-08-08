<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\ProductoVariante;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class ImportarVariantesTest extends TestCase
{
    public function testImportarVariantes()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $producto = Producto::with('variantes')->first();

        $sku = $producto->variantes->first()->sku;

        $token = time();

        $archivo = "test-import-{$token}.xlsx";

        $skuProducto = $sku;

        Excel::store(new class($skuProducto, $token) implements FromArray {
            private $sku;
            private $token;

            public function __construct($sku, $token)
            {
                $this->sku = $sku;
                $this->token = $token;
            }

            public function array(): array
            {
                return [
                    ['producto_sku', 'sku', 'codigo_barras', 'precio', 'precio_oferta', 'costo', 'stock', 'estado', 'atributos'],
                    [$this->sku, "TEST-{$this->token}-A", '111111', '25.50', '', '10', '7', '1', 'Color: Azul'],
                    [$this->sku, "TEST-{$this->token}-B", '222222', '30', '20', '12', '3', '1', ''],
                ];
            }
        }, $archivo, 'local');

        $file = new UploadedFile(
            storage_path("app/{$archivo}"),
            'variantes.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->post(route('admin.variantes.importar'), [
            'archivo' => $file,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('productos_variantes', [
            'sku' => "TEST-{$token}-A",
            'stock' => 7,
        ]);

        $this->assertDatabaseHas('productos_variantes', [
            'sku' => "TEST-{$token}-B",
            'precio_oferta' => 20,
        ]);

        $varianteA = ProductoVariante::where('sku', "TEST-{$token}-A")->first();

        $this->assertTrue($varianteA->atributos->contains(function ($v) {
            return strtolower($v->valor) === 'azul' && strtolower($v->atributo->nombre) === 'color';
        }));

        $varianteB = ProductoVariante::where('sku', "TEST-{$token}-B")->first();

        $this->assertTrue($varianteB->atributos->isEmpty());

        foreach (["TEST-{$token}-A", "TEST-{$token}-B"] as $s) {
            $v = ProductoVariante::where('sku', $s)->first();
            if ($v) {
                $v->atributos()->detach();
                $v->delete();
            }
        }

        @unlink(storage_path("app/{$archivo}"));
    }
}
