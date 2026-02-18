@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="bi bi-graph-up"></i> Análisis de Competencias
            </h2>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-collection"></i> Total de Habilidades
                    </h5>
                    <h2 class="mb-0">{{ number_format($stats['total_skills']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags"></i> Habilidades Únicas
                    </h5>
                    <h2 class="mb-0">{{ number_format($stats['unique_skills']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-person-badge"></i> Promedio por Portfolio
                    </h5>
                    <h2 class="mb-0">{{ $stats['avg_per_portfolio'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Habilidades Más Demandadas</h5>
                </div>
                <div class="card-body">
                    {!! $chartMostDemanded->container() !!}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribución de Niveles</h5>
                </div>
                <div class="card-body">
                    {!! $chartPhpLevels->container() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Insights de BI -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb"></i> Insights de Business Intelligence
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="bi bi-trending-up text-success"></i> Tendencias</h6>
                            <p class="small">
                                Las habilidades en desarrollo web (JavaScript, PHP, Laravel)
                                muestran un crecimiento del 25% en los últimos 3 meses.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-people text-info"></i> Perfiles</h6>
                            <p class="small">
                                Los portfolios con más de 8 habilidades diferentes tienen
                                un 40% más de visitas que la media.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-award text-warning"></i> Recomendaciones</h6>
                            <p class="small">
                                Se recomienda incluir habilidades en Cloud Computing y DevOps
                                para mejorar la empleabilidad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $chartMostDemanded->script() !!}
    {!! $chartPhpLevels->script() !!}

@endsection
