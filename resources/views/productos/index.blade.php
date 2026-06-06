@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row align-items-center mb-4">
        
        <div class="col-md-3 text-start">
            <h2 class="h3 text-dark mb-0">Inventario</h2>
        </div>

        <div class="col-md-6">
            <form action="{{ route('productos.index') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0" 
                           placeholder="Buscar por código..." 
                           value="{{ $search ?? '' }}">
                    
                    <button class="btn btn-primary" type="submit">Buscar</button>
                    
                    @if($search)
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-danger" title="Limpiar búsqueda">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="col-md-3 text-end">
            @if(Auth::user()->hasRole(['almacenista', 'dba']))
                <a href="{{ route('productos.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-2"></i> Nuevo
                </a>
            @endif
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $producto->cod_producto }}
                                    </span>
                                </td>
                                <td>{{ $producto->nombre }}</td>
                                
                                <td>
                                    @if($producto->cantidad < 5)
                                        <span class="text-danger fw-bold">{{ $producto->cantidad }}</span>
                                    @else
                                        {{ $producto->cantidad }}
                                    @endif
                                </td>
                                
                                <td>${{ number_format($producto->precio, 2) }}</td>
                                
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        
                                        <a href="{{ route('productos.show', $producto->cod_producto) }}" class="btn btn-sm btn-info text-white" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if(Auth::user()->hasRole(['almacenista', 'dba']))
                                            
                                            <a href="{{ route('productos.edit', $producto->cod_producto) }}" class="btn btn-sm btn-warning text-white" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('productos.destroy', $producto->cod_producto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No se encontraron productos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($productos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mt-3">
                        {{ $productos->appends(['search' => $search])->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection