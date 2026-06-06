@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="bi bi-clipboard-plus"></i> Registrar Examen</h2>
            <a href="{{ route('examenes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('examenes.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                <div class="col-md-4">
    <label class="form-label">Fecha y Hora *</label>
    <input type="datetime-local" 
           name="fecha" 
           class="form-control @error('fecha') is-invalid @enderror" 
           step="1" 
           min="{{ date('Y-m-d\TH:i') }}" 
           required>
    
    @error('fecha')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                <div class="col-md-4">
                    <label class="form-label">Optometrista *</label>
                    <select name="empleados_fk" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($empleados as $e)
                            <option value="{{ $e->nss }}">{{ $e->nombre }} {{ $e->apellido_p }} ({{ $e->rol }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cliente *</label>
                    <select name="clientes_fk" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->telefono_movil }}">{{ $c->nombre }} {{ $c->apellido_p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Resultado / Diagnóstico *</label>
                    <input type="text" name="resultado" class="form-control" placeholder="Ej: OD -0.50 OI -0.75" required>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-success"><i class="bi bi-check-circle"></i> Guardar Examen</button>
            </div>

        </form>

    </div>

</div>

@endsection