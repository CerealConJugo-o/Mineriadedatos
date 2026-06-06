@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Registrar Nuevo Pago</h5>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pagos.store') }}" method="POST">
                        @csrf

                        @if($empleado)
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-person-circle fs-4 me-3"></i>
                                <div>
                                    <strong>Pagando a:</strong> {{ $empleado->nombre_completo ?? $empleado->nombre }}<br>
                                    <small>NSS: {{ $empleado->nss }} | Rol: {{ ucfirst($empleado->rol) }}</small>
                                </div>
                            </div>
                            <input type="hidden" name="empleado_nss" value="{{ $empleado->nss }}">
                        @else
                            <div class="mb-3">
                                <label for="empleado_nss" class="form-label">NSS del Empleado</label>
                                <input type="text" name="empleado_nss" class="form-control" required>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="monto" class="form-label">Monto a Pagar ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="monto" id="monto" 
                                           class="form-control" 
                                           value="{{ old('monto', $empleado->salario ?? 0) }}" required>
                                </div>
                                <div class="form-text">Salario base registrado: ${{ number_format($empleado->salario ?? 0, 2) }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Pago</label>
                                <input type="text" class="form-control" value="{{ date('d/m/Y') }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="concepto" class="form-label">Concepto</label>
                            <select name="concepto" id="concepto" class="form-select">
                                <option value="Nómina Quincenal">Nómina Quincenal</option>
                                <option value="Nómina Mensual">Nómina Mensual</option>
                                <option value="Bono de Productividad">Bono de Productividad</option>
                                <option value="Aguinaldo">Aguinaldo</option>
                                <option value="Finiquito">Finiquito</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas Adicionales (Opcional)</label>
                            <textarea name="notas" id="notas" rows="3" class="form-control" placeholder="Detalles extra..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ $empleado ? route('pagos.show', $empleado->nss) : route('pagos.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Guardar Pago
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection