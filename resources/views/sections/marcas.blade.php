<div class="barnd_section sec_ptb_50 clearfix">
    <div class="container">

        <!-- TEXTO / TÍTULO -->
        <div class="section_title text-center mb-4">
            <h4>Marcas que nos acompañan</h4>
            <div class="title_line"></div>
        </div>

        <div class="barnd_carousel clearfix">

            @foreach($marcas as $marca)
            <div class="item">
                <a class="brand_item" target="_blank">
                    <img src="{{ asset($marca->logo) }}" alt="{{ $marca->nombre }}">
                </a>
            </div>
            @endforeach

        </div>
    </div>
</div>