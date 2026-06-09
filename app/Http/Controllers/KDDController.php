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

        // Último resultado guardado (persiste aunque se cambie de sección).
        $fila = DB::table('resultados_algoritmos')
            ->where('algoritmo', 'kdd')
            ->latest('id')
            ->first();

        $resultado   = $fila ? json_decode($fila->payload, true) : null;
        $ejecutadoEn = $fila ? $fila->created_at : null;

        return view(
            'algorithms.kdd',
            compact(
                'total',
                'murieron',
                'vivieron',
                'resultado',
                'ejecutadoEn'
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
            // Persistimos el resultado para que el dashboard y la vista lo
            // conserven aunque se cambie de sección.
            DB::table('resultados_algoritmos')->insert([
                'algoritmo'  => 'kdd',
                'exactitud'  => $resultado['exactitud'] ?? null,
                'f1'         => $resultado['f1'] ?? null,
                'payload'    => json_encode($resultado),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('kdd.index')
                ->with('success', 'Proceso KDD ejecutado correctamente.');
        }

        return redirect()
            ->route('kdd.index')
            ->with('error', implode("\n", $salida));
    }
}