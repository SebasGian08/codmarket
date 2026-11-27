@php
    $opcionContacto = $config['contacto_tipo_formulario'] ?? 'opcion_1';
@endphp

@if($opcionContacto === 'opcion_2')
    @include('sections.contact.opcion2')
@elseif($opcionContacto === 'opcion_3')
    @include('sections.contact.opcion3')
@elseif($opcionContacto === 'opcion_4')
    @include('sections.contact.opcion4')
@else
    @include('sections.contact.opcion1')
@endif
@include('sections.contact.contact-document-script')
