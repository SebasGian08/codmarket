@php
    $status = 500;
    $titulo = 'Error interno del servidor';
    $mensaje = 'Algo salió mal en nuestros servidores. Inténtalo de nuevo en unos minutos.';
@endphp

@include('errors.page')