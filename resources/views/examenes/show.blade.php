@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-eyeglasses"></i> Detalle del Examen</h2>

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <p><strong>Fecha:</strong> {{ $examen->fecha }}</p>
        <p><strong>Empleado:</strong> {{ $examen->empleado->nombre }} {{ $examen->empleado->apellido_p }}</p>
        <p><strong>Cliente:</strong> {{ $examen->cliente->nombre }} {{ $examen->cliente->apellido_p }}</p>
        <p><strong>Resultado:</strong> {{ $examen->resultado }}</p>

    </div>

</div>

@endsection
