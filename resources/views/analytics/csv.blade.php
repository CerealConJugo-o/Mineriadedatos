@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Regresión Lineal con CSV
    </h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h4 class="mb-0">
                Seleccionar columnas
            </h4>
        </div>

        <div class="card-body">

            <p>
                Dataset seleccionado:
                <strong>{{ $dataset->nombre }}</strong>
            </p>

            <form action="{{ route('linear.csv.run') }}" method="POST">
                @csrf

                <input type="hidden" name="dataset_id" value="{{ $dataset->id }}">

                <div class="row">

                    <div class="col-md-6">
                        <label class="form-label">
                            Variable X
                        </label>

                        <select name="columna_x" class="form-control" required>
                            <option value="">Seleccione columna X</option>

                            @foreach($columnas as $columna)
                                <option value="{{ $columna }}">
                                    {{ $columna }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">
                            Ejemplo: mes, inversión, cantidad, periodo.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Variable Y
                        </label>

                        <select name="columna_y" class="form-control" required>
                            <option value="">Seleccione columna Y</option>

                            @foreach($columnas as $columna)
                                <option value="{{ $columna }}">
                                    {{ $columna }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">
                            Ejemplo: total, ventas, ingresos.
                        </small>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">
                    Ejecutar Regresión Lineal
                </button>

                <a href="{{ route('linear.index') }}" class="btn btn-secondary mt-4">
                    Volver
                </a>

            </form>

        </div>

    </div>

</div>

@endsection