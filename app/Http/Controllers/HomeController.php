<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totalRegistros = DB::table('registro_a')->count();

        $fallecidos = DB::table('registro_a')
            ->where('mortalidad', 1)
            ->count();

        $vivos = DB::table('registro_a')
            ->where('mortalidad', 0)
            ->count();

        $datasets = DB::table('datasets')->count();

        $ventas = DB::table('ventas')->count();

        $totalVentas = DB::table('ventas')->sum('total');

        $ventasPorMes = DB::table('ventas')
            ->selectRaw("TO_CHAR(fecha, 'YYYY-MM') as periodo, SUM(total) as total")
            ->whereNotNull('fecha')
            ->whereNotNull('total')
            ->groupByRaw("TO_CHAR(fecha, 'YYYY-MM')")
            ->orderByRaw("TO_CHAR(fecha, 'YYYY-MM')")
            ->get();

        $labelsVentas = $ventasPorMes->pluck('periodo');
        $dataVentas = $ventasPorMes->pluck('total');

        // Últimos resultados persistidos de los algoritmos de minería.
        $kddRes = DB::table('resultados_algoritmos')
            ->where('algoritmo', 'kdd')
            ->latest('id')
            ->first();

        $neuralRes = DB::table('resultados_algoritmos')
            ->where('algoritmo', 'neural')
            ->latest('id')
            ->first();

        $procesos = [
            [
                'nombre' => 'Regresión Lineal',
                'estado' => $ventas > 0 ? 'Listo' : 'Pendiente',
                'color' => $ventas > 0 ? 'success' : 'secondary',
            ],
            [
                'nombre' => 'Random Forest',
                'estado' => ($fallecidos + $vivos) > 0 ? 'Ejecutado' : 'Pendiente',
                'color' => ($fallecidos + $vivos) > 0 ? 'success' : 'secondary',
            ],
            [
                'nombre' => 'KDD',
                'estado' => $kddRes ? 'Ejecutado' : 'Pendiente',
                'color' => $kddRes ? 'success' : 'secondary',
            ],
            [
                'nombre' => 'Red Neuronal',
                'estado' => $neuralRes ? 'Ejecutado' : 'Pendiente',
                'color' => $neuralRes ? 'success' : 'secondary',
            ],
        ];

        // Datos para la gráfica comparativa de desempeño de algoritmos.
        $algoLabels = [];
        $algoExactitud = [];
        $algoF1 = [];

        if ($kddRes) {
            $algoLabels[] = 'KDD';
            $algoExactitud[] = (float) $kddRes->exactitud;
            $algoF1[] = (float) $kddRes->f1;
        }

        if ($neuralRes) {
            $algoLabels[] = 'Red Neuronal';
            $algoExactitud[] = (float) $neuralRes->exactitud;
            $algoF1[] = (float) $neuralRes->f1;
        }

        return view('dashboard', compact(
            'totalRegistros',
            'fallecidos',
            'vivos',
            'datasets',
            'ventas',
            'totalVentas',
            'labelsVentas',
            'dataVentas',
            'procesos',
            'kddRes',
            'neuralRes',
            'algoLabels',
            'algoExactitud',
            'algoF1'
        ));
    }
}