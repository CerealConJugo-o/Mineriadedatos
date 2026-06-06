<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Dataset;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $datasets = Dataset::latest()->get();

        return view('analytics.index', compact('datasets'));
    }

    public function prediccionVentas()
    {
        $ventas = Venta::selectRaw("
                DATE_FORMAT(fecha, '%Y-%m') as periodo,
                SUM(total) as total
            ")
            ->whereNotNull('fecha')
            ->whereNotNull('total')
            ->groupByRaw("DATE_FORMAT(fecha, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(fecha, '%Y-%m')")
            ->get();

        $dataParaPython = [];

        foreach ($ventas as $index => $venta) {
            $dataParaPython[] = [
                'mes' => $index + 1,
                'total' => floatval($venta->total),
                'periodo' => $venta->periodo
            ];
        }

        return $this->procesarRegresion($dataParaPython);
    }

    public function prediccionCsv(Request $request)
    {
        $request->validate([
            'dataset_id' => 'required|exists:datasets,id',
        ]);

        $dataset = Dataset::findOrFail($request->dataset_id);

        $ruta = public_path('datasets/' . $dataset->archivo);

        if (!file_exists($ruta)) {
            return back()->with('error', 'No se encontró el archivo CSV.');
        }

        $archivo = fopen($ruta, 'r');

        $headers = fgetcsv($archivo, 1000, ",");

        if (!$headers) {
            return back()->with('error', 'El CSV está vacío o no tiene encabezados.');
        }

        $headers = array_map('trim', $headers);

        $indiceFecha = array_search('fecha', $headers);
        $indiceTotal = array_search('total', $headers);

        if ($indiceFecha === false || $indiceTotal === false) {
            fclose($archivo);

            return back()->with(
                'error',
                'El CSV debe tener las columnas obligatorias: fecha y total.'
            );
        }

        $ventasPorMes = [];

        while (($fila = fgetcsv($archivo, 1000, ",")) !== false) {
            if (!isset($fila[$indiceFecha]) || !isset($fila[$indiceTotal])) {
                continue;
            }

            $fecha = trim($fila[$indiceFecha]);
            $total = trim($fila[$indiceTotal]);

            if (empty($fecha) || !is_numeric($total)) {
                continue;
            }

            $timestamp = strtotime($fecha);

            if ($timestamp === false) {
                continue;
            }

            $periodo = date('Y-m', $timestamp);

            if (!isset($ventasPorMes[$periodo])) {
                $ventasPorMes[$periodo] = 0;
            }

            $ventasPorMes[$periodo] += floatval($total);
        }

        fclose($archivo);

        ksort($ventasPorMes);

        $dataParaPython = [];

        $contador = 1;

        foreach ($ventasPorMes as $periodo => $total) {
            $dataParaPython[] = [
                'mes' => $contador,
                'total' => floatval($total),
                'periodo' => $periodo
            ];

            $contador++;
        }

        if (count($dataParaPython) < 2) {
            return back()->with(
                'error',
                'El CSV necesita ventas en al menos 2 meses diferentes para aplicar regresión lineal.'
            );
        }

        return $this->procesarRegresion($dataParaPython);
    }

    private function procesarRegresion($dataParaPython)
    {
        if (count($dataParaPython) < 2) {
            $dataParaPython = [
                ['mes' => 1, 'total' => 15000, 'periodo' => 'Demo 1'],
                ['mes' => 2, 'total' => 18000, 'periodo' => 'Demo 2'],
                ['mes' => 3, 'total' => 21000, 'periodo' => 'Demo 3'],
                ['mes' => 4, 'total' => 20500, 'periodo' => 'Demo 4'],
            ];
        }

        $totales = array_column($dataParaPython, 'total');
        $promedioVentas = array_sum($totales) / count($totales);

        $jsonInput = json_encode($dataParaPython);

        $pythonPath = env('PYTHON_PATH');
        $script = base_path('analytics/prediccion.py');

        $comando = "\"{$pythonPath}\" \"{$script}\"";

        $descriptorSpec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ];

        $process = proc_open($comando, $descriptorSpec, $pipes);

        if (!is_resource($process)) {
            return "Error: No se pudo iniciar Python.";
        }

        fwrite($pipes[0], $jsonInput);
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $errorOutput = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $codigo = proc_close($process);

        if ($codigo !== 0) {
            return "Error en Python: " . $errorOutput;
        }

        $prediccion = json_decode($output, true);

        if (!$prediccion || !isset($prediccion['venta_estimada'])) {
            return "Error: Python no devolvió una predicción válida. Salida: " . $output;
        }

        $ventaEstimada = $prediccion['venta_estimada'];

        $diferencia = $ventaEstimada - $promedioVentas;

        $porcentajeCambio = ($promedioVentas > 0)
            ? ($diferencia / $promedioVentas) * 100
            : 0;

        if ($ventaEstimada < 0) {
            $diagnostico = "Crítico: La tendencia matemática indica un colapso en ventas debido a una caída constante reciente.";
        } elseif ($diferencia < 0) {
            $diagnostico = "Alerta: Se proyecta un rendimiento " . number_format(abs($porcentajeCambio), 1) . "% inferior a tu promedio histórico.";
        } else {
            $diagnostico = "Positivo: El rendimiento supera tu promedio histórico por un " . number_format($porcentajeCambio, 1) . "%.";
        }

        if ($ventaEstimada < 0) {
            $prescripcion = [
                "Acción Inmediata" => "Revisar precios o costos urgentemente.",
                "Estrategia" => "Liquidar stock antiguo para generar flujo de caja.",
                "Marketing" => "Detener campañas de bajo retorno."
            ];

            $colorEstado = "red";
        } elseif ($diferencia < 0) {
            $prescripcion = [
                "Acción Inmediata" => "Lanzar promoción de fin de mes.",
                "Estrategia" => "Contactar clientes inactivos.",
                "Marketing" => "Aumentar inversión en anuncios un 15%."
            ];

            $colorEstado = "yellow";
        } else {
            $prescripcion = [
                "Acción Inmediata" => "Asegurar stock suficiente.",
                "Estrategia" => "Impulsar productos de alta demanda.",
                "Marketing" => "Fidelizar clientes actuales."
            ];

            $colorEstado = "green";
        }

        $labels = array_column($dataParaPython, 'periodo');
        $data = array_column($dataParaPython, 'total');

        $labels[] = "Siguiente periodo (IA)";
        $data[] = $ventaEstimada;

        return view('analytics.prediccion', compact(
            'prediccion',
            'labels',
            'data',
            'promedioVentas',
            'diagnostico',
            'prescripcion',
            'colorEstado'
        ));
    }
}