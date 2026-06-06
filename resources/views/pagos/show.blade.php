@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark">Historial de Pagos</h2>
        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a la lista
        </a>
    </div>

    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Datos del Empleado</h5>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $empleado->nombre_completo ?? $empleado->nombre }}</h4>
                    <p class="text-muted mb-1"><strong>NSS:</strong> {{ $empleado->nss }}</p>
                    <p class="text-muted mb-1"><strong>Rol:</strong> {{ ucfirst($empleado->rol) }}</p>
                    <hr>
                    <h2 class="text-success">${{ number_format($empleado->salario, 2) }}</h2>
                    <small class="text-muted">Salario Base Mensual</small>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('pagos.create', ['empleado_nss' => $empleado->nss]) }}" class="btn btn-success btn-lg">
                            <i class="bi bi-cash-stack me-2"></i> Registrar Pago
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pagos Realizados</h5>
                </div>
                <div class="card-body">
                    
                    @if($historial->isEmpty())
                        <div class="alert alert-light text-center border">
                            No se han registrado pagos para este empleado aún.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historial as $pago)
                                        <tr>
                                            <td>{{ $pago->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $pago->concepto ?? 'Nómina' }}</td>
                                            <td>${{ number_format($pago->monto, 2) }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info text-white">
                                                    <i class="bi bi-printer"></i> Recibo
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection