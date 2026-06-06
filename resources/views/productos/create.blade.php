@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-box-seam"></i> Registrar Nuevo Producto</h3>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        {{-- Mostrar Errores de Validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO CORRECTO APUNTANDO A PRODUCTOS --}}
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                {{-- Campo: Nombre --}}
                <div class="col-md-6">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ej. Lente Antireflejante" required>
                </div>

                {{-- Campo: Cantidad (Stock) --}}
                <div class="col-md-3">
                    <label class="form-label">Cantidad (Stock) *</label>
                    <input type="number" name="cantidad" class="form-control" value="{{ old('cantidad') }}" placeholder="0" min="0" required>
                </div>

                {{-- Campo: Precio --}}
                <div class="col-md-3">
                    <label class="form-label">Precio Unitario *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio') }}" placeholder="0.00" min="0" required>
                    </div>
                </div>

            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Guardar Producto
                </button>
            </div>

        </form>

    </div>

</div>

@endsection