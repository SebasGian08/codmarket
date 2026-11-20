<div class="contact-brand-location-heading">
    <i class="bi bi-geo-alt-fill"></i>
    <h3>Ubicación</h3>
</div>

@if($empresa->ubicacion ?? false)
<div class="contact-brand-map">
    <iframe
        src="{{ $empresa->ubicacion }}"
        title="Ubicación de {{ $empresa->nombre ?? 'la empresa' }}"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen>
    </iframe>
</div>
@else
<div class="contact-brand-location-empty">
    <i class="bi bi-map"></i>
    <span>Configura la ubicación de Google Maps desde Empresa para mostrar el mapa aquí.</span>
</div>
@endif

@if($empresa->direccion ?? false)
<div class="contact-brand-address">
    <i class="bi bi-pin-map"></i>
    <div>{!! limpiarTextoEditor($empresa->direccion) !!}</div>
</div>
@endif
