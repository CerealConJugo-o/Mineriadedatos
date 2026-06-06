@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">
        Dataset: {{ $dataset->nombre }}
    </h3>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    @foreach($filas as $fila)

                        <tr>

                            @foreach($fila as $celda)

                                <td>
                                    {{ $celda }}
                                </td>

                            @endforeach

                        </tr>

                    @endforeach

                </table>

            </div>

        </div>

    </div>

</div>

@endsection