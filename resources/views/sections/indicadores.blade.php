<section class="about-stats scroll-reveal reveal-scale">
    <div class="stats-container">
        @foreach($indicadores as $indicador)
        <div class="stat">
            <span class="stat-number" data-value="{{ $indicador['valor'] ?? '' }}" aria-label="{{ $indicador['valor'] ?? '' }}">{{ $indicador['valor'] ?? '' }}</span>
            <span class="stat-label">{{ $indicador['titulo'] ?? '' }}</span>
        </div>
        @endforeach
    </div>
</section>
