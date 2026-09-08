@php
    $status = 429;
    $titulo = 'Demasiadas solicitudes';
    $mensaje = 'Has realizado demasiadas solicitudes en poco tiempo. Espera un momento y vuelve a intentarlo.';
@endphp

@include('errors.page')