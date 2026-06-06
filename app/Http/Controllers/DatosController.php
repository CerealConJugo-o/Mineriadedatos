<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dataset;

class DatosController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Pantalla principal (Cargar + Ver datasets)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $datasets = Dataset::latest()->get();

        return view(
            'datos.index',
            compact('datasets')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ver contenido de un dataset
    |--------------------------------------------------------------------------
    */
    public function show(Dataset $dataset)
    {
        $ruta = public_path(
            'datasets/' . $dataset->archivo
        );

        if (!file_exists($ruta)) {
            return redirect()
                ->route('datos.index')
                ->with(
                    'error',
                    'No se encontró el archivo.'
                );
        }

        $archivo = fopen($ruta, 'r');

        $filas = [];

        while (($data = fgetcsv($archivo, 1000, ",")) !== false) {
            $filas[] = $data;
        }

        fclose($archivo);

        return view(
            'datos.show',
            compact('dataset', 'filas')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Subir dataset
    |--------------------------------------------------------------------------
    */
    public function upload(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:csv,txt'
        ]);

        $archivo = $request->file('archivo');

        $nombreArchivo =
            time() . '_' . $archivo->getClientOriginalName();

        $archivo->move(
            public_path('datasets'),
            $nombreArchivo
        );

        Dataset::create([
            'nombre' => pathinfo(
                $archivo->getClientOriginalName(),
                PATHINFO_FILENAME
            ),
            'archivo' => $nombreArchivo,
            'registros' => 0
        ]);

        return redirect()
            ->route('datos.index')
            ->with(
                'success',
                'Dataset cargado correctamente'
            );
    }
}