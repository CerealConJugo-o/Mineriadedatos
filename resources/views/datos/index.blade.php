@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Cargar Dataset</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('datos.upload') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Seleccione un archivo CSV
                    </label>

                    <input type="file"
                           class="form-control"
                           name="archivo"
                           accept=".csv">

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Cargar Archivo
                </button>

            </form>

            <hr>

            <h4>Datasets cargados</h4>

            <table class="table table-striped">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($datasets as $dataset)

                    <tr>

                        <td>{{ $dataset->id }}</td>

                        <td>{{ $dataset->nombre }}</td>

                        <td>{{ $dataset->archivo }}</td>

                        <td>

                            <<div class="d-flex gap-2">
                                <a href="{{ route('datos.show', $dataset->id) }}"
                                class="btn btn-primary btn-sm">
                                Ver datos
                            </a>

    <form action="{{ route('datos.destroy', $dataset->id) }}"
          method="POST"
          onsubmit="return confirm('¿Seguro que deseas eliminar este dataset?')">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-danger btn-sm">
            Eliminar
        </button>

    </form>

</div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center">
                            No hay datasets cargados.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection