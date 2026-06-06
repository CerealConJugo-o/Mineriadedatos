@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-clipboard-check"></i> Gestión de Pedidos</h2>

    @if(Auth::user()->hasRole(['vendedor', 'optometrista', 'almacenista', 'dba']))
        <a href="{{ route('pedidos.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Nuevo Pedido
        </a>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <form action="{{ route('pedidos.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por número..."
                       value="{{ $search ?? '' }}">

                <button class="btn btn-primary"><i class="bi bi-search"></i></button>

                @if(!empty($search))
                <a href="{{ route('pedidos.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Número</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Entrega</th>
                        <th>Estado</th>
                        <th style="width: 120px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pedidos as $p)
                    <tr>
                        <td class="fw-bold">#{{ $p->numero_pedido }}</td>
<td>
    {{ \Carbon\Carbon::parse($p->fecha_solicitud)->format('Y-m-d H:i') }}
</td>

<td>
    {{ $p->fecha_entrega 
        ? \Carbon\Carbon::parse($p->fecha_entrega)->format('Y-m-d')
        : '---' 
    }}
</td>

                        <td>
                            @php
                                $badgeClass = match($p->estado_pedido) {
                                    'Entregado', 'Completado' => 'success',
                                    'Cancelado' => 'danger',
                                    'En Proceso' => 'primary',
                                    default => 'warning text-dark'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ $p->estado_pedido }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('pedidos.show', $p->numero_pedido) }}"
                               class="btn btn-info btn-sm text-white" title="Ver Detalles">
                               <i class="bi bi-eye"></i>
                            </a>
                            
                            </td>
                    </tr>
                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox"></i> No se encontraron pedidos registrados.
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
            
            @if(method_exists($pedidos, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $pedidos->links() }}
                </div>
            @endif
            
        </div>

    </div>

</div>

@endsection