<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $pedidos = Pedido::where('numero_pedido', 'like', "%$search%")
                ->orWhere('estado_pedido', 'like', "%$search%")
                ->with('proveedor') // Carga rápida del nombre del proveedor
                ->orderBy('numero_pedido', 'desc')
                ->paginate(10);
        } else {
            // Ordenamos descendente para ver los últimos pedidos primero
            $pedidos = Pedido::with('proveedor')
                ->orderBy('numero_pedido', 'desc')
                ->paginate(10);
        }

        return view('pedidos.index', compact('pedidos', 'search'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $productos   = Producto::all();

        return view('pedidos.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        // =========================
        // 1. VALIDACIÓN BLINDADA
        // =========================
        $request->validate([
            // Validamos que el proveedor exista por su llave primaria (telefono)
            'proveedores_fk' => 'required|exists:proveedores,telefono',

            // Fechas lógicas
            'fecha_entrega' => 'nullable|date|after_or_equal:today',

            // Validar array de productos
            'productos' => 'required|array|min:1',
            // Validar que el producto exista por su llave primaria (cod_producto)
            'productos.*.id' => 'required|exists:productos,cod_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.especif' => 'nullable|string|max:100',
        ], [
            'proveedores_fk.required' => 'Debes seleccionar un proveedor.',
            'proveedores_fk.exists' => 'El proveedor seleccionado no es válido.',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a hoy.',
            'productos.required' => 'Debes agregar al menos un producto.',
            'productos.*.id.exists' => 'Uno de los productos seleccionados ya no existe en el sistema.',
        ]);

        DB::beginTransaction();

        try {
            // =========================
            // 2. CREAR PEDIDO (Dejamos que la BD asigne el ID)
            // =========================
            // Nota: Al usar create(), Laravel devuelve el objeto con el ID ya asignado
            // en $pedido->numero_pedido (o tu primaryKey).
            
            $pedido = Pedido::create([
                // 'numero_pedido' => No lo enviamos, dejamos que sea AUTO INCREMENT (Serial)
                'fecha_entrega'   => $request->fecha_entrega ? Carbon::parse($request->fecha_entrega) : null,
                'fecha_solicitud' => Carbon::now(),
                'estado_pedido'   => 'Pendiente', // Nace pendiente, el stock NO se mueve aún
                'proveedores_fk'  => $request->proveedores_fk,
            ]);

            // =========================
            // 3. GUARDAR PRODUCTOS USANDO EL ID REAL
            // =========================
            foreach ($request->productos as $p) {
                PedidoProducto::create([
                    'pedidos_fk'   => $pedido->numero_pedido, // Usamos el ID recién creado
                    'productos_fk' => $p['id'],
                    'especif'      => $p['especif'] ?? null,
                    'cantidad'     => $p['cantidad'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pedidos.index')
                ->with('success', 'Pedido registrado correctamente No. ' . $pedido->numero_pedido);

        } catch (\Throwable $e) {
            DB::rollBack();
            
            // ESTO ES LO NUEVO: Matamos el proceso y mostramos el error en pantalla
            dd($e->getMessage(), $e->getLine(), $e->getFile());

            /* return back()->withErrors([
                'error' => 'Ocurrió un error inesperado. Intente nuevamente.'
            ])->withInput(); 
            */
        }
    }
    

    public function show($id)
    {
        // Cargamos la relación 'productos' y 'proveedor'
        // findOrFail usa la llave primaria definida en tu Modelo (numero_pedido)
        $pedido = Pedido::with(['proveedor', 'productos'])->findOrFail($id);
        
        return view('pedidos.show', compact('pedido'));
    }
    
    // Opcional: Método para recibir el pedido y aumentar stock
    public function recibir($id)
    {
        // Aquí iría la lógica para cambiar estado a 'Recibido' 
        // y sumar las cantidades al inventario de Productos
    }
}