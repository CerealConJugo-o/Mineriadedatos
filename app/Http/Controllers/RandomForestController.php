<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class RandomForestController extends Controller
{
    public function index()
    {
        $total = DB::table('registro_a')->count();

        $positivos = DB::table('registro_a')
            ->where('mortalidad', 1)
            ->count();

        $negativos = DB::table('registro_a')
            ->where('mortalidad', 0)
            ->count();

        return view(
            'algorithms.randomforest',
            compact(
                'total',
                'positivos',
                'negativos'
            )
        );
    }

public function run()
{
    // Evita que PHP corte el proceso por tiempo
    set_time_limit(0);

    $python = env('PYTHON_PATH');

    $script = base_path('python/randomforest.py');

    $comando = "\"{$python}\" \"{$script}\"";

    $salida = [];

    $codigo = 0;

    exec(
        $comando . " 2>&1",
        $salida,
        $codigo
    );

    return response(
        "<pre>" .
        "Código de salida: " . $codigo . "\n\n" .
        implode("\n", $salida) .
        "</pre>"
    );
}
    public function results()
    {
        $datos = DB::table('registro_a')
            ->select(
                'nuevos',
                'mortalidad'
            )
            ->paginate(100);

        return view(
            'algorithms.rf_results',
            compact('datos')
        );
    }
}