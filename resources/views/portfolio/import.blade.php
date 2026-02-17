@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4">Importar Portfolio</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Card: Importar desde JSON Resume -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-code"></i> JSON Resume
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Importa tu curriculum desde un archivo JSON Resume compatible.
                            </p>

                            <form method="POST" action="{{ route('portfolio.import.json-resume') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="json_resume_url" class="form-label">
                                        URL del JSON Resume
                                    </label>
                                    <input
                                        type="url"
                                        class="form-control @error('json_resume_url') is-invalid @enderror"
                                        id="json_resume_url"
                                        name="json_resume_url"
                                        placeholder="https://ejemplo.com/resume.json"
                                        value="{{ old('json_resume_url') }}"
                                        required
                                    >
                                    @error('json_resume_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Ejemplo: <code>https://gist.githubusercontent.com/username/id/raw/resume.json</code>
                                    </small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-download"></i> Importar desde JSON Resume
                                </button>
                            </form>

                            <hr class="my-3">

                            <div class="alert alert-info mb-0">
                                <strong>ℹ️ ¿Qué es JSON Resume?</strong>
                                <p class="mb-0 small">
                                    JSON Resume es un estándar abierto para currículums.
                                    <a href="https://jsonresume.org/schema/" target="_blank">Ver especificación</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Importar desde GitHub -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-github"></i> GitHub
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Importa tus proyectos desde tu perfil público de GitHub.
                            </p>

                            <form method="POST" action="{{ route('portfolio.import.github') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="github_username" class="form-label">
                                        Usuario de GitHub
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input
                                            type="text"
                                            class="form-control @error('github_username') is-invalid @enderror"
                                            id="github_username"
                                            name="github_username"
                                            placeholder="tu-usuario"
                                            value="{{ old('github_username') }}"
                                            required
                                        >
                                        @error('github_username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Se importarán tus 10 repositorios más recientes
                                    </small>
                                </div>

                                <button type="submit" class="btn btn-dark w-100">
                                    <i class="bi bi-download"></i> Importar desde GitHub
                                </button>
                            </form>

                            <hr class="my-3">

                            <div class="alert alert-warning mb-0">
                                <strong>⚠️ Nota:</strong>
                                <p class="mb-0 small">
                                    Solo se importarán repositorios públicos. La API de GitHub
                                    tiene un límite de 60 peticiones por hora sin autenticación.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ventajas de la Reutilización -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-recycle"></i> Ventajas de la Reutilización de Datos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="bi bi-clock-history text-success"></i> Ahorro de Tiempo</h6>
                            <p class="small">
                                No necesitas volver a introducir información que ya existe en otros servicios.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-check-circle text-success"></i> Consistencia</h6>
                            <p class="small">
                                Mantén tus datos actualizados en múltiples plataformas desde una única fuente.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-gear text-success"></i> Interoperabilidad</h6>
                            <p class="small">
                                Usa estándares abiertos como JSON Resume para facilitar la portabilidad de datos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
