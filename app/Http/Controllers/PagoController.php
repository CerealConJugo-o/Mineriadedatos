<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Nomina; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Iniciamos la consulta base
        $query = Empleado::query();

        if ($search) {
            // Aplicamos el filtro avanzado (NSS, Nombres, Apellidos o Nombre Completo)
            $query->where(function($q) use ($search) {
                $q->where('nss', 'ilike', "%$search%")
                  ->orWhere('nombre', 'ilike', "%$search%")
                  ->orWhere('apellido_p', 'ilike', "%$search%")
                  ->orWhere('apellido_m', 'ilike', "%$search%")
                  // Magia para buscar "Juan Perez" completo
                  ->orWhereRaw("CONCAT(nombre, ' ', apellido_p, ' ', apellido_m) ILIKE ?", ["%{$search}%"]);
            });
        }

        // Ordenamos y obtenemos los resultados
        $empleados = $query->orderBy('nombre', 'asc')->get();

        // Pasamos tanto los empleados como la variable $search para mantener el texto en el input
        return view('pagos.index', compact('empleados', 'search'));
    }

    public function show($nss)
    {
        // 1. Buscamos al empleado por su NSS (que actúa como ID)
        $empleado = Empleado::where('nss', $nss)->firstOrFail();

        // 2. Traemos su historial de pagos
        $historial = Nomina::where('empleado_nss', $empleado->nss)
                           ->orderBy('fecha_pago', 'desc')
                           ->get();

        return view('pagos.show', compact('empleado', 'historial'));
    }

    public function create(Request $request)
    {
        $empleado = null;
        
        // Si ya seleccionaron un empleado desde el menú anterior
        if($request->has('empleado_nss')){
            $empleado = Empleado::where('nss', $request->empleado_nss)->first();
            
            // Si el empleado no existe (alguien manipuló la URL), lo regresamos a null
            if (!$empleado) {
                return redirect()->route('pagos.index')->with('error', 'Empleado no encontrado.');
            }
        }
        
        return view('pagos.create', compact('empleado'));
    }

    public function store(Request $request)
    {
        // 1. Validaciones Estrictas (Dinero es dinero 💰)
        $request->validate([
            'empleado_nss' => 'required|exists:empleados,nss',
            'monto'        => 'required|numeric|min:1', // Mínimo 1 peso, nada de pagos en 0
            'concepto'     => 'required|string|max:100', // Ej: "Quincena 1 Nov", "Bono"
            'notas'        => 'nullable|string|max:255',
        ], [
            'empleado_nss.required' => 'Debes seleccionar un empleado.',
            'empleado_nss.exists'   => 'El empleado seleccionado no es válido.',
            'monto.required'        => 'Debes ingresar el monto a pagar.',
            'monto.min'             => 'El monto debe ser mayor a 0.',
            'concepto.required'     => 'El concepto del pago es obligatorio.',
        ]);

        // 2. REGLA DE NEGOCIO: Evitar doble pago el mismo día (Opcional pero recomendado)
        // Verificamos si ya se le pagó HOY a este empleado para evitar "doble click" accidental
        $pagoHoy = Nomina::where('empleado_nss', $request->empleado_nss)
                         ->whereDate('fecha_pago', Carbon::today())
                         ->exists();

        if ($pagoHoy) {
            // Si quieres permitir varios pagos al día, borra este bloque IF.
            // Si quieres ser estricto, déjalo.
            return back()
                ->withErrors(['empleado_nss' => 'Ya se registró un pago para este empleado el día de hoy.'])
                ->withInput();
        }

        // 3. Guardar Nómina
        Nomina::create([
            'empleado_nss' => $request->empleado_nss,
            'monto'        => $request->monto,
            'fecha_pago'   => Carbon::now(), // Fecha y hora exacta del sistema
            'concepto'     => $request->concepto,
            'notas'        => $request->notas
        ]);

        return redirect()->route('pagos.show', $request->empleado_nss)
                         ->with('success', 'Pago registrado exitosamente.');
    }
}