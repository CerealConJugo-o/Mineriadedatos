@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2>
        Resultados Random Forest
    </h2>

    <div class="card">

        <div class="card-body">

            <table class="table table-striped">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Mortalidad</th>

                    </tr>

                </thead>

                <tbody>

                @foreach($datos as $fila)

                    <tr>

                        <td>
                            {{ $fila->nuevos }}
                        </td>

                        <td>

                            @if($fila->mortalidad == 1)

                                <span class="badge bg-danger">
                                    Detectado
                                </span>

                            @else

                                <span class="badge bg-success">
                                    No Detectado
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            {{ $datos->links() }}

        </div>

    </div>

</div>

@endsection