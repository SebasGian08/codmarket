<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Portafolio;

class PortafolioController extends Controller
{
    public function index()
    {
        $portafolios = Portafolio::where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('pages.portafolio.index', compact('portafolios'));
    }
}