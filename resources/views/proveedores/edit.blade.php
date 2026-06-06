@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <div class="d-flex justify-content-between mb-4">
            <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Proveedor</h3>

            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        {{-- Mostrar errores de validación si existen --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('proveedores.update', $proveedor->telefono) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- 1. SOLUCIÓN: Usar 'readonly' en lugar de 'disabled' --}}
                <div class="col-md-4">
                    <label class="form-label">Teléfono (ID)</label>
                    <input type="number" name="telefono" class="form-control" 
                           value="{{ $proveedor->telefono }}" readonly style="background-color: #e9ecef;">
                    <div class="form-text">El ID no se puede modificar.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre de la Empresa *</label>
                    <input type="text" name="nombre" class="form-control" 
                           value="{{ $proveedor->nombre }}" required>
                </div>

                {{-- Si estás usando correo desglosado (nombre + dominio) --}}
                <div class="col-md-4">
                    <label class="form-label">Correo (Nombre)</label>
                    <input type="text" name="correo_nombre" class="form-control"
                           value="{{ $proveedor->correo_nombre }}">
                </div>

                {{-- He descomentado esto por si tu controlador lo requiere. 
                     Si ya usas 'correo_completo', avísame para ajustar el input --}}
                <div class="col-md-4">
                    <label class="form-label">Dominio (@ejemplo.com)</label>
                    <input type="text" name="correo_dominio" class="form-control"
                           value="{{ $proveedor->correo_dominio }}">
                </div>

            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Actualizar Proveedor
                </button>
            </div>

        </form>

    </div>

</div>

@endsection