@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <div class="d-flex justify-content-between mb-4">
            <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Cliente</h3>

            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        <form action="{{ route('clientes.update', $cliente->telefono_movil) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Teléfono Móvil</label>
                    <input type="number" class="form-control" value="{{ $cliente->telefono_movil }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ $cliente->nombre }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_p" class="form-control"
                           value="{{ $cliente->apellido_p }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_m" class="form-control"
                           value="{{ $cliente->apellido_m }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono Fijo</label>
                    <input type="number" name="telefono_fijo" class="form-control"
                           value="{{ $cliente->telefono_fijo }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Correo</label>
                    <input type="text" name="correo_nombre" class="form-control"
                           value="{{ $cliente->correo_nombre }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Dominio</label>
                    <input type="text" name="correo_dominio" class="form-control"
                           value="{{ $cliente->correo_dominio }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sexo</label>
                    <select name="sexo" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="Masculino" {{ $cliente->sexo == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ $cliente->sexo == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Actualizar Cliente
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
