@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Dashboard de Minería de Datos
    </h2>

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow border-0 bg-primary text-white">
                <div class="card-body">
                    <h6>Registros COVID</h6>
                    <h2>{{ number_format($totalRegistros) }}</h2>
                    <small>Tabla registro_a</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Ventas registradas</h6>
                    <h2>{{ number_format($ventas) }}</h2>
                    <small>Total: ${{ number_format($totalVentas, 2) }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 bg-danger text-white">
                <div class="card-body">
                    <h6>Fallecidos detectados</h6>
                    <h2>{{ number_format($fallecidos) }}</h2>
                    <small>Mortalidad = 1</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 bg-info text-white">
                <div class="card-body">
                    <h6>Datasets cargados</h6>
                    <h2>{{ number_format($datasets) }}</h2>
                    <small>Archivos CSV disponibles</small>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">
                    Rendimiento mensual de ventas
                </div>

                <div class="card-body">
                    <canvas id="ventasChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    Procesos del sistema
                </div>

                <div class="card-body">
                    @foreach($procesos as $proceso)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>{{ $proceso['nombre'] }}</span>
                            <span class="badge bg-{{ $proceso['color'] }}">
                                {{ $proceso['estado'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    Random Forest - Mortalidad
                </div>

                <div class="card-body">
                    <canvas id="mortalidadChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">
                    Estado general
                </div>

                <div class="card-body">
                    <p>Laravel en Render: <span class="badge bg-success">Activo</span></p>
                    <p>PostgreSQL: <span class="badge bg-success">Conectado</span></p>
                    <p>Python: <span class="badge bg-success">Disponible</span></p>
                    <p>Minería de datos: <span class="badge bg-primary">Operativa</span></p>
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
                tension: 0.2
            }]
        },
        options: {
            responsive: true
        }
    });

    new Chart(document.getElementById('mortalidadChart'), {
        type: 'doughnut',
        data: {
            labels: ['Falleció', 'Vivió'],
            datasets: [{
                data: [
                    {{ $fallecidos }},
                    {{ $vivos }}
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

@endsection