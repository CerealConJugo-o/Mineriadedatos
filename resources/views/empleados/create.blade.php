@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm p-4" style="border-radius: 15px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-person-plus"></i> Registrar Nuevo Empleado</h3>
            <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill"></i> Error de Validación:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                <h5 class="text-primary mt-3">Información Personal</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">NSS *</label>
                    <input type="number" name="nss" class="form-control @error('nss') is-invalid @enderror" 
                           value="{{ old('nss') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno *</label>
                    <input type="text" name="apellido_p" class="form-control @error('apellido_p') is-invalid @enderror" 
                           value="{{ old('apellido_p') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno *</label>
                    <input type="text" name="apellido_m" class="form-control @error('apellido_m') is-invalid @enderror" 
                           value="{{ old('apellido_m') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">CURP Completa (18 Caracteres) *</label>
                    <input type="text" name="curp" class="form-control @error('curp') is-invalid @enderror" 
                           value="{{ old('curp') }}" 
                           placeholder="EJ: GOMA900101HDFRCN05" 
                           maxlength="18" 
                           style="text-transform: uppercase;"
                           required>
                    <div class="form-text text-muted">Se extraerán datos automáticos de la CURP.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sexo *</label>
                    <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="H" {{ old('sexo') == 'H' ? 'selected' : '' }}>Hombre (H)</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Mujer (M)</option>
                    </select>
                </div>

                <h5 class="text-primary mt-4">Datos de Acceso y Contacto</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Email (Usuario de Acceso) *</label>
                    <input type="email" name="correo_completo" class="form-control @error('correo_completo') is-invalid @enderror" 
                           value="{{ old('correo_completo') }}" placeholder="ejemplo@optics.com" required>
                    <div class="form-text">Este correo se usará para iniciar sesión.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="col-md-4">
                    </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono Móvil *</label>
                    <input type="number" name="telefono_movil" class="form-control @error('telefono_movil') is-invalid @enderror" 
                           value="{{ old('telefono_movil') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono Fijo *</label>
                    <input type="number" name="telefono_fijo" class="form-control @error('telefono_fijo') is-invalid @enderror" 
                           value="{{ old('telefono_fijo') }}" required>
                </div>


                <h5 class="text-primary mt-4">Información Laboral</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Rol en el Sistema *</label>
                    <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="vendedor" {{ old('rol') == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                        <option value="optometrista" {{ old('rol') == 'optometrista' ? 'selected' : '' }}>Optometrista</option>
                        <option value="almacenista" {{ old('rol') == 'almacenista' ? 'selected' : '' }}>Almacenista</option>
                        <option value="gerente" {{ old('rol') == 'gerente' ? 'selected' : '' }}>Gerente</option>
                        <option value="dba" {{ old('rol') == 'dba' ? 'selected' : '' }}>DBA</option>
                    </select>
                    <div class="form-text">Esto definirá los permisos del usuario.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Salario Mensual *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="salario" class="form-control @error('salario') is-invalid @enderror" 
                               value="{{ old('salario') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cédula Profesional (Opcional)</label>
                    <input type="text" name="cedula_profesional" class="form-control @error('cedula_profesional') is-invalid @enderror" 
                           value="{{ old('cedula_profesional') }}">
                </div>

                <h5 class="text-primary mt-4">Dirección</h5>
                <hr>

                <div class="col-md-4">
                    <label class="form-label">Calle *</label>
                    <input type="text" name="calle" class="form-control @error('calle') is-invalid @enderror" 
                           value="{{ old('calle') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Número *</label>
                    <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" 
                           value="{{ old('numero') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Colonia *</label>
                    <input type="text" name="colonia" class="form-control @error('colonia') is-invalid @enderror" 
                           value="{{ old('colonia') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Código Postal *</label>
                    <input type="number" name="cp" class="form-control @error('cp') is-invalid @enderror" 
                           value="{{ old('cp') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Delegación *</label>
                    <input type="text" name="delegacion" class="form-control @error('delegacion') is-invalid @enderror" 
                           value="{{ old('delegacion') }}" required>
                </div>

            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Guardar Empleado y Crear Cuenta
                </button>
            </div>
            
        </form>

    </div>

</div>

@endsection