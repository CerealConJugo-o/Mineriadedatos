@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <div class="d-flex justify-content-between mb-4">
            <h3 class="mb-0">
                <i class="bi bi-pencil-square"></i> Editar Producto
            </h3>

            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        <form action="{{ route('productos.update', $producto->cod_producto) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ $producto->nombre }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control"
                           value="{{ $producto->cantidad }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Precio</label>
                    <input type="number" name="precio" step="0.01" class="form-control"
                           value="{{ $producto->precio }}" required>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Actualizar Producto
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
