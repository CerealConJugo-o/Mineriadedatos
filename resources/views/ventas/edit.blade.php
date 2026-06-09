@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Editar venta #{{ $venta->folio }}
    </h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body">

            <form action="{{ route('ventas.update', $venta->folio) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Folio</label>
                        <input type="number"
                               class="form-control"
                               value="{{ $venta->folio }}"
                               disabled>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date"
                               name="fecha"
                               class="form-control"
                               value="{{ old('fecha', $venta->fecha) }}"
                               required>
                    </div>


                    <div class="col-md-8 mb-3">
                        <label class="form-label">Servicios</label>
                        <input type="text"
                               name="servicios"
                               class="form-control"
                               value="{{ old('servicios', $venta->servicios) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total</label>
                        <input type="number"
                               step="0.01"
                               name="total"
                               class="form-control"
                               value="{{ old('total', $venta->total) }}"
                               required>
                    </div>

                </div>

                <div class="text-end">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button class="btn btn-primary">
                        Actualizar venta
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection