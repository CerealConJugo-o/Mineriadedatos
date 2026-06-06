@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Regresión Lineal
    </h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Usar tabla de ventas</h4>
                </div>

                <div class="card-body">
                    <p>
                        Ejecuta la regresión lineal utilizando la tabla
                        <strong>ventas</strong> de MariaDB.
                    </p>

                    <p class="text-muted">
                        El sistema agrupa automáticamente las ventas por mes usando
                        las columnas <strong>fecha</strong> y <strong>total</strong>.
                    </p>

                    <form action="{{ route('linear.ventas') }}" method="POST">
                        @csrf

                        <button class="btn btn-primary">
                            Ejecutar con tabla ventas
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Usar CSV cargado</h4>
                </div>

                <div class="card-body">
                    <p>
                        Ejecuta la regresión lineal utilizando un archivo CSV cargado.
                    </p>

                    <p class="text-muted">
                        El CSV debe tener encabezados y las columnas obligatorias:
                        <strong>fecha</strong> y <strong>total</strong>.
                    </p>

                    <form action="{{ route('linear.csv.run') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Dataset CSV
                            </label>

                            <select name="dataset_id" class="form-control" required>
                                <option value="">Seleccione un CSV</option>

                                @foreach($datasets as $dataset)
                                    <option value="{{ $dataset->id }}">
                                        {{ $dataset->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-success">
                            Ejecutar con CSV
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection