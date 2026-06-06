@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-truck"></i> Gestión de Proveedores</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4" style="border-radius: 15px;">

        <div class="d-flex justify-content-between mb-4">
            <h4 class="mb-0">Lista de proveedores</h4>

            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Agregar Proveedor
            </a>
        </div>

        <!-- Buscador -->
        <form action="{{ route('proveedores.index') }}" method="GET" class="mb-4">
            <div class="input-group">

                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por nombre o teléfono..."
                       value="{{ $search ?? '' }}">

                <button class="btn btn-primary"><i class="bi bi-search"></i></button>

                @if(!empty($search))
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                </a>
                @endif
            </div>
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Teléfono</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th style="width: 130px;">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->telefono }}</td>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>{{ $proveedor->correo_nombre }}</td>

                    <td>
                        <a href="{{ route('proveedores.edit', $proveedor->telefono) }}"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form action="{{ route('proveedores.destroy', $proveedor->telefono) }}"
                              method="POST" class="d-inline">
                            @csrf 
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty

                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="bi bi-search"></i> No se encontraron proveedores.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
