@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Datos desde MariaDB</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>CURP</th>
                        <th>Correo</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($empleados as $empleado)

                        <tr>

                            <td>{{ $empleado->id_a }}</td>

                            <td>
                                {{ $empleado->nombre_completo }}
                            </td>

                            <td>
                                {{ $empleado->curp_completa }}
                            </td>

                            <td>
                                {{ $empleado->correo_completo }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection