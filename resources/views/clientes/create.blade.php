@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow-sm p-4" style="border-radius: 15px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-person-vcard"></i> Registrar Nuevo Cliente</h3>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill"></i> Error:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                <h5 class="text-primary mt-3">Datos de Contacto (Identificadores)</h5>
                <hr>

                <div class="col-md-6">
                    <label class="form-label">Teléfono Móvil (ID del Cliente) *</label>
                    <input type="number" name="telefono_movil" class="form-control @error('telefono_movil') is-invalid @enderror" 
                           value="{{ old('telefono_movil') }}" placeholder="10 dígitos" required>
                    <div class="form-text">Este número servirá como identificador único.</div>
                    @error('telefono_movil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono Fijo</label>
                    <input type="number" name="telefono_fijo" class="form-control" 
                           value="{{ old('telefono_fijo') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" name="correo_completo" class="form-control @error('correo_completo') is-invalid @enderror" 
                           value="{{ old('correo_completo') }}" placeholder="cliente@ejemplo.com" required>
                    @error('correo_completo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <h5 class="text-primary mt-4">Información Personal</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Nombre(s) *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno *</label>
                    <input type="text" name="apellido_p" class="form-control @error('apellido_p') is-invalid @enderror" 
                           value="{{ old('apellido_p') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_m" class="form-control" 
                           value="{{ old('apellido_m') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sexo *</label>
                    <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('sexo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Guardar Cliente
                </button>
            </div>

        </form>
    </div>
</div>
@endsection