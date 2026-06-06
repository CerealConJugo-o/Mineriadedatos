@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-person-badge"></i> Gestión de Clientes</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4" style="border-radius: 15px;">

        <div class="d-flex justify-content-between mb-4">
            <h4 class="mb-0">Lista de clientes</h4>

            @if(Auth::user()->hasRole(['vendedor', 'dba']))
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Agregar Cliente
                </a>
            @endif
        </div>

        <form action="{{ route('clientes.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar cliente por nombre, apellido o teléfono..."
                       value="{{ $search ?? '' }}">

                <button class="btn btn-primary"><i class="bi bi-search"></i></button>

                @if(!empty($search))
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                </a>
                @endif
            </div>
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Teléfono</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Sexo</th>
                    <th style="width: 130px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->telefono_movil }}</td>
                    <td>{{ $cliente->nombre }} {{ $cliente->apellido_p }} {{ $cliente->apellido_m }}</td>
                    <td>{{ $cliente->correo_nombre }}</td>
                    <td>{{ $cliente->sexo }}</td>

                    <td>
                        @if(Auth::user()->hasRole(['vendedor', 'dba']))
                            
                            <a href="{{ route('clientes.edit', $cliente->telefono_movil) }}"
                               class="btn btn-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('clientes.destroy', $cliente->telefono_movil) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>

                        @endif
                        
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="bi bi-search"></i> No se encontraron clientes.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection