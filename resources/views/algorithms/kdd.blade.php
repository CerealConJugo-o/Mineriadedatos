@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🗄️ KDD (Knowledge Discovery in Databases)</h2>

        <button class="btn btn-secondary" disabled>
            Ejecutar Proceso KDD
        </button>
    </div>

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

            <div class="alert alert-info mt-3">
                El algoritmo KDD aún no ha sido implementado.
            </div>

        </div>

    </div>

</div>

@endsection