@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm p-4" style="border-radius: 15px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Empleado</h3>
            <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        <form action="{{ route('empleados.update', $empleado->nss) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <h5 class="text-primary mt-3">Información Personal</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">NSS</label>
                    <input type="number" name="nss" class="form-control" value="{{ $empleado->nss }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $empleado->nombre }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_p" class="form-control" value="{{ $empleado->apellido_p }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_m" class="form-control" value="{{ $empleado->apellido_m }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">CURP</label>
                    <input type="text" name="curp_nombre" class="form-control" value="{{ $empleado->curp_nombre }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="number" name="curp_fecha" class="form-control" value="{{ $empleado->curp_fecha }}">
                </div>

                <h5 class="text-primary mt-4">Contacto</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Teléfono Móvil</label>
                    <input type="number" name="telefono_movil" class="form-control" value="{{ $empleado->telefono_movil }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono Fijo</label>
                    <input type="number" name="telefono_fijo" class="form-control" value="{{ $empleado->telefono_fijo }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="text" name="correo_nombre" class="form-control" value="{{ $empleado->correo_nombre }}">
                </div>

                <h5 class="text-primary mt-4">Dirección</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Calle</label>
                    <input type="text" name="calle" class="form-control" value="{{ $empleado->calle }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Número</label>
                    <input type="number" name="numero" class="form-control" value="{{ $empleado->numero }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Colonia</label>
                    <input type="text" name="colonia" class="form-control" value="{{ $empleado->colonia }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Código Postal</label>
                    <input type="number" name="cp" class="form-control" value="{{ $empleado->cp }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Delegación</label>
                    <input type="text" name="delegacion" class="form-control" value="{{ $empleado->delegacion }}">
                </div>

                <h5 class="text-primary mt-4">Información Laboral</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Rol</label>
                    <input type="text" name="rol" class="form-control" value="{{ $empleado->rol }}">
                </div>

                <div class="col-md-4">
    <label class="form-label">Salario</label>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" name="salario" 
               class="form-control @error('salario') is-invalid @enderror" 
               value="{{ old('salario', $empleado->salario) }}" required>
    </div>
</div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Actualizar Empleado
                </button>
            </div>

            {{-- ... resto del formulario ... --}}

<div class="col-md-4">
    <label class="form-label">Sexo *</label>
    {{-- FALTABA ESTE CAMPO --}}
    <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
        <option value="" disabled>Seleccione...</option>
        <option value="H" {{ old('sexo', $empleado->sexo) == 'H' ? 'selected' : '' }}>Hombre (H)</option>
        <option value="M" {{ old('sexo', $empleado->sexo) == 'M' ? 'selected' : '' }}>Mujer (M)</option>
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Email (Usuario) *</label>
    {{-- CORREGIDO: name="correo_completo" y value usa el accessor del modelo --}}
    <input type="email" name="correo_completo" 
           class="form-control @error('correo_completo') is-invalid @enderror" 
           value="{{ old('correo_completo', $empleado->correo_completo) }}" required>
</div>

{{-- ... resto del formulario ... --}}

        </form>

    </div>

</div>

@endsection
