<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KDDController extends Controller
{
    public function index()
    {
        $total = DB::table('registro_a')->count();

        $murieron = DB::table('registro_a')
            ->where('mortalidad', 1)
            ->count();

        $vivieron = DB::table('registro_a')
            ->where('mortalidad', 0)
            ->count();

        return view(
            'algorithms.kdd',
            compact(
                'total',
                'murieron',
                'vivieron'
            )
        );
    }

    /**
     * Ejecuta el proceso KDD (Python) que entrena un Árbol de Decisión y
     * descubre las palabras más asociadas a la mortalidad. SOLO LECTURA.
     */
    public function run()
    {
        set_time_limit(0);

        $python = env('PYTHON_PATH', 'python');
        $script = base_path('python/kdd.py');

        $comando = "\"{$python}\" \"{$script}\"";

        $salida = [];
        $codigo = 0;

        exec($comando . " 2>&1", $salida, $codigo);

        // El script imprime progreso y, al final, una línea JSON (empieza con '{').
        $resultado = null;

        foreach ($salida as $linea) {
            $linea = trim($linea);

            if (str_starts_with($linea, '{')) {
                $decodificado = json_decode($linea, true);

                if (is_array($decodificado)) {
                    $resultado = $decodificado;
                }
            }
        }

        if ($resultado && isset($resultado['error'])) {
            return redirect()
                ->route('kdd.index')
                ->with('error', $resultado['error']);
        }

        if ($codigo === 0 && $resultado) {
            return redirect()
                ->route('kdd.index')
                ->with('resultado_kdd', $resultado)
                ->with('success', 'Proceso KDD ejecutado correctamente.');
        }

        return redirect()
            ->route('kdd.index')
            ->with('error', implode("\n", $salida));
    }
}