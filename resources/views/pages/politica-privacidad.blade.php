@extends('layouts.appweb')

@section('title', 'Política de privacidad y cookies')

@section('content')
<section class="privacy-page">
    <div class="container">
        <nav class="privacy-page__breadcrumb" aria-label="breadcrumb">
            <a href="{{ url('/') }}">Inicio</a>
            <span aria-hidden="true">/</span>
            <span>Política de privacidad</span>
        </nav>

        <article class="privacy-page__content">
            <h1>Política de privacidad y cookies</h1>
            <p>
                Esta política explica qué información se recoge al usar este sitio, para qué se utiliza
                y cómo puedes gestionar las cookies opcionales.
            </p>

            <h2>Responsable y contacto</h2>
            <p>
                El responsable del tratamiento es <strong>{{ $empresa->nombre ?? config('app.name') }}</strong>.
                Puedes comunicarte usando
                @if($empresa->correo ?? false)
                    <a href="mailto:{{ $empresa->correo }}">{{ $empresa->correo }}</a>.
                @else
                    el formulario de <a href="{{ url('/contacto') }}">contacto</a>.
                @endif
            </p>

            <h2>Información que podemos recoger</h2>
            <ul>
                <li>En los formularios de contacto: nombre o razón social, documento cuando corresponda,
                    correo, teléfono, servicio o marca de interés y mensaje.</li>
                <li>En el Libro de Reclamaciones: los datos de identificación y contacto, además de la
                    información necesaria para registrar y atender la reclamación.</li>
                <li>En la suscripción: dirección de correo electrónico.</li>
                <li>Para seguridad y prevención de abusos, algunos envíos registran dirección IP y
                    navegador. Los formularios pueden usar Cloudflare Turnstile para verificar que no
                    sean automatizados.</li>
                <li>Si aceptas analítica y marketing, Google Analytics y las etiquetas publicadas en
                    Google Tag Manager pueden recoger información de uso del sitio.</li>
            </ul>

            <h2>Finalidades</h2>
            <p>
                Usamos la información para responder consultas, gestionar solicitudes y reclamaciones,
                enviar novedades cuando te suscribes, mantener la seguridad del sitio y, solo si lo
                autorizas, medir visitas y administrar etiquetas de analítica o marketing.
            </p>

            <h2>Cookies y preferencias</h2>
            <p>
                El sitio puede utilizar cookies técnicas necesarias para mantener la sesión, proteger
                formularios y recordar preferencias. Estas funciones esenciales no se desactivan.
                Google Analytics y Google Tag Manager solo se cargan después de que aceptes
                «Analítica y marketing». Si rechazas esa categoría, no se cargan esas etiquetas.
            </p>
            <p>
                Tu elección se guarda en el almacenamiento local de este navegador. Puedes cambiarla
                en cualquier momento con el enlace «Preferencias de cookies» del pie de página.
            </p>

            <h2>Servicios de terceros</h2>
            <p>
                Para proteger formularios se puede utilizar Cloudflare Turnstile. Si aceptas
                «Analítica y marketing», se conectarán los servicios de Google configurados en este
                sitio. Algunas páginas también cargan recursos externos como Google Fonts, Google Maps
                o bibliotecas alojadas en CDN; al solicitarlos, esos proveedores pueden recibir datos
                técnicos como la dirección IP y el navegador. Estos proveedores procesan información
                de acuerdo con sus propias políticas.
            </p>

            <h2>Conservación y solicitudes</h2>
            <p>
                Conservamos la información durante el tiempo necesario para atender la finalidad para
                la que fue enviada y cumplir las obligaciones aplicables. Para solicitar acceso,
                rectificación o eliminación de tus datos, o para dejar de recibir novedades, escribe
                usando el medio indicado en «Responsable y contacto».
            </p>

            <h2>Actualizaciones</h2>
            <p>
                Esta política puede actualizarse cuando cambien los servicios o el tratamiento de
                información. La versión vigente estará disponible en esta página.
            </p>
        </article>
    </div>
</section>
@endsection
