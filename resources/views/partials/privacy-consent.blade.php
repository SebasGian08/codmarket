@php
    $consentName = $consentName ?? 'acepta_privacidad';
    $consentText = $consentText ?? 'He leído y acepto el tratamiento de mis datos personales para atender esta solicitud. Consulta nuestra';
@endphp

<label class="privacy-consent">
    <input type="checkbox" name="{{ $consentName }}" value="1" required
        {{ old($consentName) ? 'checked' : '' }}>
    <span>
        {{ $consentText }}
        <a href="{{ url('/politica-privacidad') }}">Política de privacidad</a>.
    </span>
</label>
