@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-clipboard-pulse"></i> Exámenes de Vista</h2>

    @if(Auth::user()->hasRole(['optometrista', 'dba']))
        <a href="{{ route('examenes.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Nuevo Examen
        </a>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por fecha, cliente o resultado..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary"><i class="bi bi-search"></i></button>

                @if(!empty($search))
                <a href="{{ route('examenes.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Cliente</th>
                        <th>Resultado (Diagnóstico)</th>
                        <th style="width: 120px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($examenes as $examen)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($examen->fecha)->format('d/m/Y H:i') }}</td>
                        <td>{{ $examen->empleado->nombre ?? '---' }}</td>
                        <td>{{ $examen->cliente->nombre ?? '---' }}</td>
                        
                        <td title="{{ $examen->resultado }}">
                            {{ Str::limit($examen->resultado, 50) }}
                        </td>

                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('examenes.show', $examen->fecha) }}" 
                                   class="btn btn-info btn-sm text-white" title="Ver Detalle">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if(Auth::user()->hasRole(['optometrista', 'dba']))
                                    <a href="{{ route('examenes.edit', $examen->fecha) }}" 
                                       class="btn btn-warning btn-sm text-white" title="Editar Diagnóstico">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-file-medical"></i> No se encontraron exámenes registrados.
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
            
            @if(method_exists($examenes, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $examenes->links() }}
                </div>
            @endif
        </div>

    </div>

</div>

@endsection