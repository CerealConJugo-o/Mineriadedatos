@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        background: #F8FAFC;
    }

    .dashboard-title {
        color: #0F172A;
        font-weight: 800;
    }

    .dashboard-subtitle {
        color: #64748B;
        font-size: 14px;
    }

    .stat-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .stat-card h6 {
        opacity: 0.85;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card h2 {
        font-weight: 800;
    }

    .custom-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .custom-header-dark {
        background: #0F172A;
        color: white;
        font-weight: 600;
    }

    .custom-header-accent {
        background: #14B8A6;
        color: white;
        font-weight: 600;
    }

    .accent-badge {
        background: #14B8A6;
        color: white;
        border-radius: 12px;
        padding: 6px 10px;
    }

    .dark-badge {
        background: #0F172A;
        color: white;
        border-radius: 12px;
        padding: 6px 10px;
    }

    .soft-text {
        color: #64748B;
    }
</style>

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="dashboard-title mb-1">
            Panel de control - Minería de Datos
        </h2>
        <p class="dashboard-subtitle">
            Monitoreo general de registros, ventas, datasets y algoritmos del sistema.
        </p>
    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#0F172A;">
                <div class="card-body">
                    <h6>Registros COVID</h6>
                    <h2>{{ number_format($totalRegistros) }}</h2>
                    <small>Tabla registro_a</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#14B8A6;">
                <div class="card-body">
                    <h6>Ventas registradas</h6>
                    <h2>{{ number_format($ventas) }}</h2>
                    <small>Total: ${{ number_format($totalVentas, 2) }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#0F172A;">
                <div class="card-body">
                    <h6>Fallecidos detectados</h6>
                    <h2>{{ number_format($fallecidos) }}</h2>
                    <small>Mortalidad = 1</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#14B8A6;">
                <div class="card-body">
                    <h6>Datasets cargados</h6>
                    <h2>{{ number_format($datasets) }}</h2>
                    <small>Archivos CSV disponibles</small>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-8 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-dark">
                    Rendimiento mensual de ventas
                </div>

                <div class="card-body">
                    <canvas id="ventasChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-accent">
                    Procesos del sistema
                </div>

                <div class="card-body">
                    @foreach($procesos as $proceso)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>{{ $proceso['nombre'] }}</span>

                            @if($proceso['estado'] === 'Listo' || $proceso['estado'] === 'Ejecutado')
                                <span class="accent-badge">
                                    {{ $proceso['estado'] }}
                                </span>
                            @else
                                <span class="dark-badge">
                                    {{ $proceso['estado'] }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Desempeño de los algoritmos de minería (KDD + Red Neuronal) --}}
    <div class="row mb-4">

        <div class="col-md-7 mb-3">
            <div class="card shadow border-0 custom-card h-100">
                <div class="card-header custom-header-accent">
                    Desempeño de algoritmos de minería
                </div>
                <div class="card-body">
                    @if(count($algoLabels) > 0)
                        <canvas id="algoChart" height="140"></canvas>
                    @else
                        <p class="soft-text mb-0">
                            Ejecuta KDD o Red Neuronal para ver aquí su desempeño.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-3">

            <div class="card shadow border-0 custom-card mb-3">
                <div class="card-header custom-header-dark">
                    KDD - Árbol de Decisión
                </div>
                <div class="card-body">
                    @if($kddRes)
                        <h2 class="mb-0" style="font-weight:800;">{{ $kddRes->exactitud }}%</h2>
                        <small class="soft-text">Exactitud &middot; F1 {{ $kddRes->f1 }}%</small>
                        <div><small class="soft-text">Última ejecución: {{ $kddRes->created_at }}</small></div>
                    @else
                        <span class="dark-badge">Pendiente</span>
                        <p class="soft-text mt-2 mb-0">Aún no se ha ejecutado.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-dark">
                    Red Neuronal - MLPClassifier
                </div>
                <div class="card-body">
                    @if($neuralRes)
                        <h2 class="mb-0" style="font-weight:800;">{{ $neuralRes->exactitud }}%</h2>
                        <small class="soft-text">Exactitud &middot; F1 {{ $neuralRes->f1 }}%</small>
                        <div><small class="soft-text">Última ejecución: {{ $neuralRes->created_at }}</small></div>
                    @else
                        <span class="dark-badge">Pendiente</span>
                        <p class="soft-text mt-2 mb-0">Aún no se ha ejecutado.</p>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-accent">
                    Random Forest - Mortalidad
                </div>

                <div class="card-body">
                    <canvas id="mortalidadChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-dark">
                    Estado general
                </div>

                <div class="card-body">
                    <p>
                        Laravel en Render:
                        <span class="accent-badge">Activo</span>
                    </p>

                    <p>
                        PostgreSQL:
                        <span class="accent-badge">Conectado</span>
                    </p>

                    <p>
                        Python:
                        <span class="accent-badge">Disponible</span>
                    </p>

                    <p>
                        Minería de datos:
                        <span class="dark-badge">Operativa</span>
                    </p>

                    <p class="soft-text mt-3 mb-0">
                        El sistema se encuentra desplegado en la nube y conectado a una base de datos PostgreSQL.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    const labelsVentas = {!! json_encode($labelsVentas) !!};
    const dataVentas = {!! json_encode($dataVentas) !!};

    new Chart(document.getElementById('ventasChart'), {
        type: 'line',
        data: {
            labels: labelsVentas,
            datasets: [{
                label: 'Ventas por mes',
                data: dataVentas,
                fill: true,
                tension: 0.3,
                borderColor: '#14B8A6',
                backgroundColor: 'rgba(20,184,166,0.15)',
                borderWidth: 3,
                pointBackgroundColor: '#0F172A',
                pointBorderColor: '#14B8A6',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#0F172A'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#64748B'
                    }
                },
                y: {
                    ticks: {
                        color: '#64748B'
                    }
                }
            }
        }
    });

    const algoLabels = {!! json_encode($algoLabels) !!};
    const algoExactitud = {!! json_encode($algoExactitud) !!};
    const algoF1 = {!! json_encode($algoF1) !!};

    const algoCanvas = document.getElementById('algoChart');
    if (algoCanvas && algoLabels.length > 0) {
        new Chart(algoCanvas, {
            type: 'bar',
            data: {
                labels: algoLabels,
                datasets: [
                    {
                        label: 'Exactitud (%)',
                        data: algoExactitud,
                        backgroundColor: '#14B8A6'
                    },
                    {
                        label: 'F1-Score (%)',
                        data: algoF1,
                        backgroundColor: '#0F172A'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { color: '#64748B' } },
                    x: { ticks: { color: '#64748B' } }
                },
                plugins: { legend: { labels: { color: '#0F172A' } } }
            }
        });
    }

    new Chart(document.getElementById('mortalidadChart'), {
        type: 'doughnut',
        data: {
            labels: ['Falleció', 'Vivió'],
            datasets: [{
                data: [
                    {{ $fallecidos }},
                    {{ $vivos }}
                ],
                backgroundColor: [
                    '#0F172A',
                    '#14B8A6'
                ],
                borderColor: '#ffffff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#0F172A'
                    }
                }
            }
        }
    });
</script>

@endsection