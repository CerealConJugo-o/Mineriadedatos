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
                'estado' => 'Pendiente',
                'color' => 'secondary',
            ],
            [
                'nombre' => 'Red Neuronal',
                'estado' => 'Pendiente',
                'color' => 'secondary',
            ],
        ];

        return view('dashboard', compact(
            'totalRegistros',
            'fallecidos',
            'vivos',
            'datasets',
            'ventas',
            'totalVentas',
            'labelsVentas',
            'dataVentas',
            'procesos'
        ));
    }
}