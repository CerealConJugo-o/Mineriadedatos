<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::orderBy('fecha', 'desc')
            ->orderBy('folio', 'desc')
            ->paginate(15);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $siguienteFolio = (Venta::max('folio') ?? 1000) + 1;

        return view('ventas.create', compact('siguienteFolio'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'folio' => 'required|integer|unique:ventas,folio',
            'fecha' => 'required|date',
            'servicios' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
        ]);

        Venta::create($request->only([
            'folio',
            'fecha',
            'servicios',
            'total',
        ]));

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function edit(Venta $venta)
    {
        return view('ventas.edit', compact('venta'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicios' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
        ]);

        $venta->update($request->only([
            'fecha',
            'servicios',
            'total',
        ]));

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta eliminada correctamente.');
    }
}