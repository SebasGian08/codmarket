<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Exports\PlantillaVariantesDatosSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class ImportarVariantesTest extends TestCase
{
    public function testPlantillaVariantesIncluyePrecioMayorista()
    {
        $filas = (new PlantillaVariantesDatosSheet())->array();

        $this->assertContains('precio_mayorista', $filas[0]);
        foreach ($filas as $fila) {
            $this->assertCount(count($filas[0]), $fila);
        }
    }

    public function testImportarVariantes()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $producto = Producto::with('variantes')->first();

        $sku = $producto->variantes->first()->sku;

        $token = time();

        $archivo = "test-import-{$token}.xlsx";

        $skuProducto = $sku;

        Excel::store(new class($skuProducto, $token) implements WithMultipleSheets {
            private $sku;
            private $token;

            public function __construct($sku, $token)
            {
                $this->sku = $sku;
                $this->token = $token;
            }

            public function sheets(): array
            {
                $sku = $this->sku;
                $token = $this->token;

                return [
                    new class($sku) implements FromArray, WithTitle {
                        private $sku;

                        public function __construct($sku)
                        {
                            $this->sku = $sku;
                        }

                        public function array(): array
                        {
                            return [
                                ['producto_sku', 'sku', 'codigo_barras', 'precio', 'precio_oferta', 'costo', 'stock', 'estado', 'atributos', 'producto'],
                                [$this->sku, '', '', '', '', '', '', '', '', 'Producto sin variante'],
                            ];
                        }

                        public function title(): string
                        {
                            return 'Hoja1';
                        }
                    },
                    new class($sku, $token) implements FromArray, WithTitle {
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
                                ['producto_sku', 'sku', 'codigo_barras', 'precio', 'precio_oferta', 'precio_mayorista', 'costo', 'stock', 'estado', 'atributos', 'producto'],
                                [$this->sku, "TEST-{$this->token}-A", '111111', '25.50', '', '19.50', '10', '7', '1', 'Color: Azul', 'Producto'],
                                [$this->sku, "TEST-{$this->token}-B", '222222', '30', '20', '', '12', '3', '1', '', 'Producto'],
                                [$this->sku, '', '333333', '40', '', '', '18', '2', '1', '', 'Producto'],
                            ];
                        }

                        public function title(): string
                        {
                            return 'VARIANTES';
                        }
                    },
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
            'precio_mayorista' => 19.50,
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

        $varianteAuto = ProductoVariante::where('codigo_barras', '333333')->first();

        $this->assertNotNull($varianteAuto);

        $this->assertRegExp('/^\d{8}$/', $varianteAuto->sku);

        foreach (["TEST-{$token}-A", "TEST-{$token}-B"] as $s) {
            $v = ProductoVariante::where('sku', $s)->first();
            if ($v) {
                $v->atributos()->detach();
                $v->delete();
            }
        }

        if ($varianteAuto) {
            $varianteAuto->atributos()->detach();
            $varianteAuto->delete();
        }

        @unlink(storage_path("app/{$archivo}"));
    }
}
