@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-receipt"></i> Ventas Registradas</h2>

    @if(Auth::user()->hasRole(['vendedor', 'dba']))
        <a href="{{ route('ventas.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Nueva Venta
        </a>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-3" style="border-radius: 15px;">

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Empleado</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th style="width: 120px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->folio }}</td>
                    <td>{{ $venta->fecha }}</td>
<td>{{ $venta->empleado->nombre ?? 'Empleado Eliminado' }}</td>                    <td>
                        <span class="badge bg-{{ $venta->estado == 'Completada' ? 'success' : 'danger' }}">
                            {{ $venta->estado }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('ventas.show', $venta->folio) }}" 
                           class="btn btn-info btn-sm text-white" title="Ver Detalle">
                           <i class="bi bi-eye"></i>
                        </a>

                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No hay ventas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
        
        @if(method_exists($ventas, 'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $ventas->links() }}
            </div>
        @endif

    </div>
</div>

@endsection