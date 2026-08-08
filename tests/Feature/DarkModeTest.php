<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;

class DarkModeTest extends TestCase
{
    public function testDashboardTieneBotonYEstilosDeTema()
    {
        $admin = Usuario::where('estado', 1)->first();

        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $response->assertSee('id="themeToggle"', false);

        $response->assertSee('fa-moon', false);

        $response->assertSee('admin/assets/css/dark-mode.css', false);

        $response->assertSee("localStorage.getItem('admin-theme')", false);

        $response->assertSee("document.documentElement.setAttribute('data-theme', 'dark')", false);

        $response->assertSee("$('.sidebar').attr('data-background-color', oscuro ? 'dark' : 'white');", false);
    }
}
