<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\MetodoPago;
use App\Models\ProductoVariante;
use App\Models\Tienda;
use App\Models\Usuario;
use App\Models\Vendedor;
use App\Models\Venta;

class VentaVendedorTest extends TestCase
{
    public function testPaginaVendedoresMuestraListado()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $response = $this->get(route('admin.vendedores.index'));

        $response->assertStatus(200);

        $vendedor = Vendedor::first();

        if ($vendedor) {
            $response->assertSee($vendedor->nombre, false);
        }
    }

    public function testVistasConVendedorRenderizan()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $this->get(route('admin.cajas.index'))->assertStatus(200);
        $this->get(route('admin.ventas.index'))->assertStatus(200);
        $this->get(route('admin.ventas.historial'))->assertStatus(200);
        $this->get(route('admin.ingresos.index'))->assertStatus(200);
        $this->get(route('admin.transferencias.index'))->assertStatus(200);
        $this->get(route('admin.productos.index'))->assertStatus(200);
    }

    public function testJqueryCargaAntesQueElScriptDeHistorial()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $html = $this->get(route('admin.ventas.historial'))->getContent();

        $posJquery = strpos($html, 'jquery.min.js');
        $posScript = strpos($html, "'click', '.btn-ver-venta'");

        $this->assertNotFalse($posJquery);
        $this->assertNotFalse($posScript);
        $this->assertLessThan($posScript, $posJquery);
    }

    public function testAbrirCajaRequiereVendedor()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $tienda = Tienda::first();

        $response = $this->from(route('admin.cajas.index'))->post(route('admin.cajas.abrir'), [
            'id_tienda' => $tienda->id_tienda,
            'nombre' => 'Caja Test',
            'monto_apertura' => 10,
        ]);

        $response->assertSessionHasErrors('id_vendedor');
    }

    public function testVentaHeredaVendedorDeLaCaja()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $tienda = Tienda::first();
        $vendedor = Vendedor::first();
        $metodoPago = MetodoPago::first();
        $cliente = Cliente::where('es_varios', 1)->first();
        $inventario = Inventario::where('cantidad', '>', 0)->first();

        if (!$vendedor || !$inventario || !$metodoPago) {
            $this->markTestSkipped('No hay datos suficientes en la BD para la prueba');
        }

        $tienda = Tienda::find($inventario->id_tienda);

        $caja = Caja::create([
            'id_tienda' => $tienda->id_tienda,
            'id_usuario' => $admin->id_usuario,
            'id_vendedor' => $vendedor->id_vendedor,
            'nombre' => 'Caja Test Vendedor',
            'monto_apertura' => 20,
            'fecha_apertura' => now(),
            'estado' => 1,
        ]);

        $this->assertDatabaseHas('cajas', [
            'id_caja' => $caja->id_caja,
            'id_vendedor' => $vendedor->id_vendedor,
        ]);

        $response = $this->postJson(route('admin.ventas.guardar'), [
            'id_caja' => $caja->id_caja,
            'id_cliente' => $cliente->id_cliente,
            'nombre_cliente' => 'CLIENTES VARIOS',
            'id_metodo_pago' => $metodoPago->id_metodo_pago,
            'items' => [
                [
                    'id_variante' => $inventario->id_variante,
                    'cantidad' => 1,
                    'precio' => 10,
                ],
            ],
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $venta = Venta::where('id_caja', $caja->id_caja)->latest('id_venta')->first();

        $this->assertNotNull($venta);
        $this->assertEquals($vendedor->id_vendedor, $venta->id_vendedor);

        $caja->update(['estado' => 0, 'fecha_cierre' => now()]);
        $caja->delete();
        $venta->detalle()->delete();
        $venta->delete();
    }
}
