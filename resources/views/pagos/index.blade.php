@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        
        <h2 class="h3 text-dark mb-0">Gestión de Salarios</h2>

        <form action="{{ route('pagos.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Buscar por nombre..."
                       value="{{ $search ?? '' }}" 
                       style="max-width: 250px;"> <button class="btn btn-primary" type="submit">Buscar</button>
                
                @if(!empty($search))
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary" title="Limpiar">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>NSS</th>
                            <th>Nombre Completo</th>
                            <th>Rol</th>
                            <th>Salario Base</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->nss }}</td>
                                <td>{{ $empleado->nombre_completo ?? $empleado->nombre }}</td> 
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($empleado->rol) }}</span>
                                </td>
                                <td>${{ number_format($empleado->salario, 2) }}</td>
                                
                                <td class="text-end">
                                    @if(Auth::user()->hasRole(['gerente', 'dba']))
        
                                    <a href="{{ route('pagos.show', ['pago' => $empleado->nss]) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-cash-coin me-1"></i> Pagar / Historial
                                    </a>
        
                                    <a href="{{ route('empleados.edit', $empleado->nss) }}" class="btn btn-sm btn-outline-primary ms-1">
                                        <i class="bi bi-pencil"></i> Ajustar Salario
                                    </a>

                        @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($empleados->isEmpty())
                    <div class="text-center p-4">
                        <p class="text-muted">No hay empleados registrados para gestionar pagos.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection