<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Empleado;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $examenes = Examen::where('resultado', 'like', "%$search%")
                ->orWhere('fecha', 'like', "%$search%")
                ->with(['empleado', 'cliente'])
                ->paginate(10); // Agregué paginación
        } else {
            $examenes = Examen::with(['empleado', 'cliente'])->paginate(10);
        }

        return view('examenes.index', compact('examenes', 'search'));
    }

public function create()
    {
        // CORRECCIÓN: Filtramos para traer solo a los optometristas
        $empleados = Empleado::where('rol', 'optometrista')->get();
        
        $clientes = Cliente::all();

        return view('examenes.create', compact('empleados', 'clientes'));
    }

    public function store(Request $request)
    {
        // 1. Validaciones estrictas
        $request->validate([
            // 'after_or_equal:today' permite fechas desde las 00:00 de hoy en adelante.
            // Si quieres ser estricto con la hora actual usa 'after_or_equal:now'
            'fecha' => 'required|date|after_or_equal:today', 
            'empleados_fk' => 'required|exists:empleados,nss',
            'clientes_fk'  => 'required|exists:clientes,telefono_movil',
            'resultado'    => 'required|string|max:50',
        ], [
            'fecha.required' => 'Debes seleccionar una fecha y hora.',
            'fecha.after_or_equal' => 'La fecha no puede ser en el pasado. Debe ser hoy o futura.',
            'empleados_fk.required' => 'Debes seleccionar un optometrista.',
            'clientes_fk.required' => 'Debes seleccionar un cliente.',
        ]);

        // 2. Validación manual de llave primaria duplicada
        // Como 'fecha' es tu ID, no puede haber dos iguales.
        if (Examen::where('fecha', $request->fecha)->exists()) {
            return back()
                ->withErrors(['fecha' => 'Ya existe una cita registrada exactamente en esta fecha y hora.'])
                ->withInput();
        }

        // 3. Guardar
        Examen::create([
            'fecha' => $request->fecha,
            'empleados_fk' => $request->empleados_fk,
            'clientes_fk' => $request->clientes_fk,
            'resultado' => $request->resultado,
        ]);

        return redirect()->route('examenes.index')
            ->with('success', 'Examen registrado correctamente.');
    }

    public function show($fecha)
    {
        $fechaDecodificada = urldecode($fecha); 
        $examen = Examen::where('fecha', $fechaDecodificada)->with(['empleado', 'cliente'])->firstOrFail();
        return view('examenes.show', compact('examen'));
    }

public function edit($fecha)
    {
        $fechaDecodificada = urldecode($fecha);
        $examen = Examen::where('fecha', $fechaDecodificada)->firstOrFail();
        
        // CORRECCIÓN: Aquí también filtramos por si necesitan cambiar al optometrista
        $empleados = Empleado::where('rol', 'optometrista')->get();
        
        $clientes = Cliente::all();

        return view('examenes.edit', compact('examen', 'empleados', 'clientes'));
    }

    public function update(Request $request, $fecha)
    {
        $fechaDecodificada = urldecode($fecha);
        
        // 1. Verificar existencia (Esto asegura el error 404 si la fecha no existe)
        $examen = Examen::where('fecha', $fechaDecodificada)->firstOrFail();

        // 2. Validar
        $request->validate([
            'resultado' => 'required|string|max:50'
            // No validamos fecha porque NO permitimos cambiarla (es la PK)
        ]);

        // 3. Actualizar
        // Usamos la instancia $examen que ya encontramos arriba
        $examen->update([
            'resultado' => $request->resultado
        ]);

        return redirect()->route('examenes.index')
            ->with('success', 'Examen actualizado correctamente.');
    }

    public function destroy($fecha)
    {
        $fechaDecodificada = urldecode($fecha);
        
        // Buscamos primero para asegurar que existe, luego borramos
        $examen = Examen::where('fecha', $fechaDecodificada)->firstOrFail();
        $examen->delete();

        return redirect()->route('examenes.index')
            ->with('success', 'Examen eliminado.');
    }
}