@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <div class="d-flex justify-content-between mb-4">
            <h3 class="mb-0">Registrar Proveedor</h3>
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Regresar</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Nombre Empresa *</label>
                    <input type="text" name="nombre" class="form-control" maxlength="20" required>
                    <div class="form-text">Máximo 20 caracteres.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono (ID) *</label>
                    <input type="number" name="telefono" class="form-control" placeholder="Solo números" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Correo *</label>
                    <input type="email" name="correo_completo" class="form-control" required>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">Guardar Proveedor</button>
            </div>
        </form>
    </div>
</div>
@endsection