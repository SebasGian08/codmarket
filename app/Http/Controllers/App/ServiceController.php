<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Portafolio;

class ServiceController extends Controller
{
    public function index()
    {
        $service = Service::where('estado', 1)->get();
        return view('pages.servicios.show', compact('service'));
    }

    public function show($slug)
    {
        $service = Service::with(['plans.features', 'benefits'])
            ->where('slug', $slug)
            ->where('estado', 1)
            ->firstOrFail();

        $services = Service::where('estado', 1)
            ->where('slug', '!=', $slug)
            ->get();

        $portafolios = Portafolio::where('estado', 1)
            ->where(function ($q) use ($service) {
                $q->where('service_id', $service->id_service)
                ->orWhereNull('service_id');
            })
            ->latest()
            ->get();

        return view('pages.servicios.show', compact('service', 'services', 'portafolios'));
    }
}