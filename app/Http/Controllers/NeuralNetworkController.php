<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NeuralNetworkController extends Controller
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

        // La página inicia en blanco: el resultado solo se muestra tras
        // ejecutar (vía flash). El dashboard es quien lee el histórico de la BD.
        return view(
            'algorithms.neural',
            compact(
                'total',
                'murieron',
                'vivieron'
            )
        );
    }

    /**
     * Ejecuta el script de Python que entrena la Red Neuronal (MLPClassifier)
     * y devuelve las métricas a la vista. El script es de SOLO LECTURA.
     */
    public function run()
    {
        set_time_limit(0);

        $python = env('PYTHON_PATH', 'python');
        $script = base_path('python/red_neuronal.py');

        $comando = "\"{$python}\" \"{$script}\"";

        $salida = [];
        $codigo = 0;

        exec($comando . " 2>&1", $salida, $codigo);

        // El script imprime líneas de progreso y, al final, una línea JSON
        // (que empieza con '{'). Buscamos esa línea y la decodificamos.
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

        // Si el script reportó un error interno dentro del JSON.
        if ($resultado && isset($resultado['error'])) {
            return redirect()
                ->route('neural.index')
                ->with('error', $resultado['error']);
        }

        if ($codigo === 0 && $resultado) {
            // Persistimos el resultado para que el dashboard y la vista lo
            // conserven aunque se cambie de sección.
            DB::table('resultados_algoritmos')->insert([
                'algoritmo'  => 'neural',
                'exactitud'  => $resultado['exactitud'] ?? null,
                'f1'         => $resultado['f1'] ?? null,
                'payload'    => json_encode($resultado),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('neural.index')
                ->with('resultado_neural', $resultado)
                ->with('success', 'Red Neuronal entrenada y evaluada correctamente.');
        }

        return redirect()
            ->route('neural.index')
            ->with('error', implode("\n", $salida));
    }
}