@extends('layouts.app')

@section('content')


<div class="container-fluid">

    <h2 class="mb-4">
        Random Forest
    </h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="row">

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>
                        Registros Totales
                    </h5>

                    <h2>
                        {{ number_format($total) }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>
                        Fallecio = 1
                    </h5>

                    <h2>
                        {{ number_format($positivos) }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>
                        Vivio = 0
                    </h5>

                    <h2>
                        {{ number_format($negativos) }}
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-4 shadow">

        <div class="card-body">

            <h4>
                Ejecutar Modelo
            </h4>

            <p>
                Analiza la columna resclin y
                actualiza mortalidad.
            </p>

            <form
                action="{{ route('randomforest.run') }}"
                method="POST">

                @csrf

                <button
                    class="btn btn-success">

                    Ejecutar Random Forest

                </button>

            </form>

            <a
                href="{{ route('randomforest.results') }}"
                class="btn btn-primary mt-3">

                Ver Resultados

            </a>

        </div>

    </div>

</div>

@endsection