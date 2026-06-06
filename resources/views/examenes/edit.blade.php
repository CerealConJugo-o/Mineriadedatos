@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-pencil-square"></i> Editar Examen</h2>

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <form action="{{ route('examenes.update', $examen->fecha) }}" method="POST">
            @csrf
            @method('PUT')

            <p><strong>Fecha:</strong> {{ $examen->fecha }}</p>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Resultado *</label>
                    <input type="text" name="resultado" class="form-control"
                        value="{{ $examen->resultado }}" required>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar</button>
            </div>

        </form>

    </div>

</div>

@endsection
