<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Producto;
use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductosExportTest extends TestCase
{
    public function testExportarProductos()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $response = $this->get(route('admin.productos.exportar'));

        $response->assertStatus(200);

        $token = time();

        $path = storage_path("app/test-export-{$token}.xlsx");

        $file = $response->baseResponse->getFile();

        copy($file->getRealPath(), $path);

        $rows = Excel::toArray([], $path);

        $data = $rows[0];

        $this->assertCount(17, $data[0]);

        $this->assertEquals('nombre', $data[0][0]);
        $this->assertEquals('sku', $data[0][8]);
        $this->assertEquals('estado', $data[0][16]);

        $this->assertEquals(Producto::count() + 1, count($data));

        @unlink($path);
    }

    public function testMapeoDeProducto()
    {
        $export = new ProductosExport();

        $producto = $export->query()->first();

        $fila = $export->map($producto);

        $this->assertCount(17, $fila);

        $this->assertEquals($producto->nombre, $fila[0]);

        $variante = $producto->variantes->first();

        $this->assertEquals($variante->sku, $fila[8]);
    }
}
