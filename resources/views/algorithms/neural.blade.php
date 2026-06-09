@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🧠 Redes Neuronales</h2>

        <form action="{{ route('neural.run') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-success" type="submit">
                Ejecutar Algoritmo
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Ocurrió un error al ejecutar el script:</strong>
            <pre class="mb-0" style="white-space: pre-wrap;">{{ session('error') }}</pre>
        </div>
    @endif

    <div class="row">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h1>{{ $total }}</h1>
                    <p>Total de Registros</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <h1>{{ $vivieron }}</h1>
                    <p>Vivieron</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <h1>{{ $murieron }}</h1>
                    <p>Murieron</p>
                </div>
            </div>
        </div>

    </div>

    {{-- RESULTADOS DEL MODELO (persistido: se conserva al cambiar de sección) --}}
    @if(!empty($resultado))
        @php $r = $resultado; @endphp

        <div class="card mt-4 shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <span>Resultados del modelo: {{ $r['modelo'] ?? 'Red Neuronal' }}</span>
                @if(!empty($ejecutadoEn))
                    <small class="text-white-50">Última ejecución: {{ $ejecutadoEn }}</small>
                @endif
            </div>
            <div class="card-body">

                <div class="row text-center mb-3">
                    <div class="col">
                        <h3 class="text-primary">{{ $r['exactitud'] }}%</h3>
                        <small>Exactitud</small>
                    </div>
                    <div class="col">
                        <h3 class="text-success">{{ $r['precision'] }}%</h3>
                        <small>Precisión</small>
                    </div>
                    <div class="col">
                        <h3 class="text-info">{{ $r['sensibilidad'] }}%</h3>
                        <small>Sensibilidad (Recall)</small>
                    </div>
                    <div class="col">
                        <h3 class="text-warning">{{ $r['f1'] }}%</h3>
                        <small>F1-Score</small>
                    </div>
                </div>

                <p class="text-muted">
                    Entrenado con {{ number_format($r['muestras_total']) }} registros balanceados
                    ({{ number_format($r['muestras_positivas']) }} fallecimientos +
                    {{ number_format($r['muestras_negativas']) }} sobrevivientes).
                    Arquitectura: capas ocultas {{ implode(' → ', $r['capas_ocultas']) }} neuronas.
                </p>

                @if(isset($r['matriz_confusion']))
                    <h6 class="mt-3">Matriz de confusión (conjunto de prueba)</h6>
                    <table class="table table-bordered text-center" style="max-width: 480px;">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>Predijo: Vivió</th>
                                <th>Predijo: Falleció</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="table-light">Real: Vivió</th>
                                <td>{{ $r['matriz_confusion'][0][0] }}</td>
                                <td>{{ $r['matriz_confusion'][0][1] }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Real: Falleció</th>
                                <td>{{ $r['matriz_confusion'][1][0] }}</td>
                                <td>{{ $r['matriz_confusion'][1][1] }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    @endif

    <div class="card mt-4 shadow-sm">
        <div class="card-header">Descripción</div>
        <div class="card-body">
            <p>
                Este módulo entrena un <strong>Perceptrón Multicapa (MLPClassifier)</strong>
                sobre el texto clínico <code>resclin</code> para predecir la
                <strong>mortalidad</strong> de los pacientes. El texto se transforma a
                números con <strong>TF-IDF</strong> y el modelo se evalúa con un
                conjunto de prueba independiente.
            </p>
            <p class="text-muted mb-0">
                El script de Python es de solo lectura: no modifica la base de datos.
            </p>
        </div>
    </div>

</div>

@endsection
