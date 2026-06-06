@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-receipt"></i> Detalle del Pedido</h2>

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <h4>Información del Pedido</h4>

        <p><strong>Número:</strong> {{ $pedido->numero_pedido }}</p>
        <p><strong>Proveedor:</strong> {{ $pedido->proveedor->nombre }}</p>
        <p><strong>Fecha Solicitud:</strong> {{ $pedido->fecha_solicitud }}</p>
        <p><strong>Fecha Entrega:</strong> {{ $pedido->fecha_entrega ?? 'Sin definir' }}</p>
        <p><strong>Estado:</strong> {{ $pedido->estado_pedido }}</p>

        <hr>

        <h4>Productos solicitados</h4>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Especificación</th>
                </tr>
            </thead>

            <tbody>

                @foreach($pedido->productos as $p)
                <tr>
                    <td>{{ $p->cod_producto }}</td>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->pivot->cantidad }}</td>
                    <td>{{ $p->pivot->especif }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>

</div>

@endsection
