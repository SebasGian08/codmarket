<section class="about-stats scroll-reveal reveal-scale">
    <div class="stats-marquee" aria-hidden="true">
        <div class="stats-marquee__track">
            @for($copy = 0; $copy < 2; $copy++)
            <div class="stats-marquee__group">
                <span>INFUSIONES GALÉS</span>
                <span class="stats-marquee__separator"></span>
                <span>TRADICIÓN</span>
                <span class="stats-marquee__separator"></span>
                <span>CALIDAD</span>
                <span class="stats-marquee__separator"></span>
                <span>BIENESTAR</span>
                <span class="stats-marquee__separator"></span>
            </div>
            @endfor
        </div>
    </div>

    <div class="stats-container">
        @foreach($indicadores as $indicador)
        <div class="stat">
            <span class="stat-number" data-value="{{ $indicador['valor'] ?? '' }}" aria-label="{{ $indicador['valor'] ?? '' }}">{{ $indicador['valor'] ?? '' }}</span>
            <span class="stat-label">{{ $indicador['titulo'] ?? '' }}</span>
        </div>
        @endforeach
    </div>
</section>
