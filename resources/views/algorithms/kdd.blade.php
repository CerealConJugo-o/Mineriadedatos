@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🗄️ KDD (Knowledge Discovery in Databases)</h2>

        <form action="{{ route('kdd.run') }}" method="POST" class="m-0"
              onsubmit="if(this.dataset.sent){return false;} this.dataset.sent=1;
                        var b=this.querySelector('button');
                        b.innerHTML='&#9203; Procesando algoritmo, espera...';">
            @csrf
            <button class="btn btn-success" type="submit">
                Ejecutar Proceso KDD
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Ocurrió un error al ejecutar el proceso:</strong>
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

    {{-- RESULTADOS DEL PROCESO KDD (solo aparecen tras pulsar Ejecutar) --}}
    @if(session('resultado_kdd'))
        @php $r = session('resultado_kdd'); @endphp

        <div class="card mt-4 shadow">
            <div class="card-header bg-dark text-white">
                Resultados: {{ $r['modelo'] ?? 'KDD' }}
            </div>
            <div class="card-body">

                <div class="row text-center mb-3">
                    <div class="col">
                        <h3 class="text-primary">{{ $r['exactitud'] }}%</h3>
                        <small>Exactitud del modelo</small>
                    </div>
                    <div class="col">
                        <h3 class="text-warning">{{ $r['f1'] }}%</h3>
                        <small>F1-Score</small>
                    </div>
                    <div class="col">
                        <h3 class="text-info">{{ number_format($r['registros_analizados']) }}</h3>
                        <small>Registros analizados</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Etapas del proceso KDD</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($r['etapas'] as $e)
                                <li class="list-group-item">
                                    <strong>{{ $e['etapa'] }}</strong><br>
                                    <small class="text-muted">{{ $e['detalle'] }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h6>Conocimiento descubierto: palabras más asociadas a la mortalidad</h6>
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr><th>Palabra</th><th class="text-end">Peso</th></tr>
                            </thead>
                            <tbody>
                                @foreach($r['palabras_clave'] as $p)
                                    <tr>
                                        <td>{{ $p['palabra'] }}</td>
                                        <td class="text-end">{{ $p['peso'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    @endif

    <div class="card mt-4 shadow-sm">

        <div class="card-header">
            Descripción del Proceso KDD
        </div>

        <div class="card-body">

            <p>
                El proceso KDD (Knowledge Discovery in Databases)
                permite descubrir conocimiento útil a partir de grandes
                volúmenes de datos mediante etapas de selección,
                limpieza, transformación, minería de datos e
                interpretación de resultados.
            </p>

            <p>
                Este módulo utilizará la información almacenada en la
                tabla <strong>registro_a</strong> para identificar
                patrones, tendencias y relaciones relevantes en los
                registros de pacientes.
            </p>

            <div class="mt-4">

                <h5>Etapas del Proceso KDD</h5>

                <ul>
                    <li>Selección de datos</li>
                    <li>Preprocesamiento</li>
                    <li>Transformación</li>
                    <li>Minería de datos</li>
                    <li>Evaluación e interpretación</li>
                </ul>

            </div>

            <p class="text-muted mb-0">
                La técnica de minería aplicada es un <strong>Árbol de Decisión</strong>
                sobre el texto clínico vectorizado con TF-IDF. El script de Python es
                de solo lectura: no modifica la base de datos.
            </p>

        </div>

    </div>

</div>

@endsection