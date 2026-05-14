@extends('admin.layouts.guest')

@section('title', 'Login Admin')

@section('content')

<main class="d-flex flex-column justify-content-center vh-100" style="background: #f5f7fb;">
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 col-12">

                    <!-- * CARD LOGIN -->
                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-body p-5">

                            <!-- * LOGO -->
                            <div class="text-center mb-4">
                                <a href="{{ url('/') }}">
                                    @php
                                    $logo = $empresa->logo_header ?? 'assets/images/logo-2.webp';
                                    @endphp

                                    <img src="{{ asset($logo) }}" alt="Logo" style="max-height: 55px;">
                                </a>
                            </div>

                            <hr class="my-4">

                            <!-- * FORM -->
                            <form action="{{ route('admin.login.post') }}" method="POST" novalidate>
                                @csrf

                                <!-- * EMAIL -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Correo <span class="text-danger">*</span>
                                    </label>
                                    <input type="email"
                                        class="form-control rounded-3 @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus>

                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- * PASSWORD -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Contraseña <span
                                            class="text-danger">*</span></label>

                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control rounded-start-3 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="••••••••" required>

                                        <span class="input-group-text bg-white" id="password-toggle"
                                            style="cursor: pointer;">
                                            <i class="far fa-eye-slash" id="toggleIcon"></i>
                                        </span>

                                        @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- * REMEMBER -->
                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                        <label class="form-check-label small">Recordarme</label>
                                    </div>

                                    <!-- Opcional -->
                                    <!-- <a href="#" class="small text-decoration-none">¿Olvidaste?</a> -->
                                </div>

                                <!-- * BUTTON -->
                                <div class="d-flex justify-content-center">
                                    <button id="btnLogin" class="btn btn-primary rounded-3 py-2 fw-semibold shadow-sm"
                                        type="submit" style="min-width: 200px;">
                                        <span id="textBtn">
                                            <i class="ti ti-login me-1"></i> Iniciar Sesión
                                        </span>
                                        <span id="loadingBtn" class="d-none">
                                            <i class="fas fa-spinner fa-spin me-1"></i> Cargando...
                                        </span>
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                    <!-- * FOOTER -->
                    <p class="text-center text-muted small mt-4 mb-0">
                        © {{ date('Y') }} Todos los derechos reservados
                    </p>

                </div>
            </div>
        </div>
    </section>
</main>

<!-- * SCRIPT PASSWORD -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('password-toggle');
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    toggle.addEventListener('click', () => {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('textBtn').classList.add('d-none');
    document.getElementById('loadingBtn').classList.remove('d-none');
    document.getElementById('btnLogin').disabled = true;
});
</script>

@endsection