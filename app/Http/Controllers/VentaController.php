<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaProducto;
use App\Models\Producto;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('empleado')->orderBy('fecha', 'desc')->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

public function create()
    {
        // CORRECCIÓN: Usamos whereIn para filtrar por múltiples roles
        // Asegúrate de que los nombres coincidan exactamente como están en tu BD (minúsculas o mayúsculas)
        $empleados = Empleado::whereIn('rol', ['vendedor', 'gerente'])->get();

        // Solo mostramos productos que tengan al menos 1 en stock
        $productos = Producto::where('cantidad', '>', 0)->get();

        return view('ventas.create', compact('empleados', 'productos'));
    }

    public function store(Request $request)
    {
        // 1. VALIDACIONES STRICTAS
        $request->validate([
            'empleado_fk' => 'required|exists:empleados,nss',
            'servicios'   => 'nullable|string|max:255',
            
            // Validamos la estructura del array de productos
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,cod_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
        ], [
            'productos.required' => 'Debes agregar al menos un producto a la venta.',
            'productos.*.id.exists' => 'Uno de los productos seleccionados ya no existe.',
            'productos.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);

        DB::beginTransaction();

        try {
            // 2. CREAR LA VENTA (CABECERA)
            // Creamos la venta con total 0 temporalmente.
            // Dejamos que la BD asigne el FOLIO (Auto Increment) para evitar choques.
            $venta = Venta::create([
                // 'folio' => Automático por BD
                'fecha'       => Carbon::now(),
                'servicios'   => $request->servicios,
                'total'       => 0, // Lo calcularemos nosotros abajo por seguridad
                'estado'      => 'Liquidado',
                'empleado_fk' => $request->empleado_fk,
            ]);

            $totalCalculado = 0;

            // 3. PROCESAR DETALLES Y STOCK
            foreach ($request->productos as $p) {
                
                // A) Buscamos el producto REAL en la BD y bloqueamos la fila
                $productoBD = Producto::where('cod_producto', $p['id'])
                    ->lockForUpdate() 
                    ->firstOrFail();

                // B) Validamos Stock Real
                if ($productoBD->cantidad < $p['cantidad']) {
                    throw new \Exception("Stock insuficiente para: {$productoBD->nombre}. Disponibles: {$productoBD->cantidad}");
                }

                // C) Cálculos Financieros (No confiamos en el request)
                $precioUnitario = $productoBD->precio;
                $cantidad       = $p['cantidad'];
                $subtotal       = $precioUnitario * $cantidad;
                $iva            = $subtotal * 0.16; // Ajusta tu tasa de IVA si es diferente
                $totalLinea     = $subtotal + $iva;

                // D) Guardar Detalle
                VentaProducto::create([
                    'ventas_fk'    => $venta->folio, // Usamos el ID asignado por la BD
                    'productos_fk' => $productoBD->cod_producto,
                    'estado'       => 'Entregado',
                    'cantidad'     => $cantidad,
                    'precio'       => $precioUnitario, // Guardamos el precio del momento de la venta
                    'subtotal_p'   => $subtotal,
                    'iva'          => $iva,
                    'total'        => $totalLinea,
                ]);

                // E) Descontar Stock
                $productoBD->decrement('cantidad', $cantidad);

                // F) Acumular al total general
                $totalCalculado += $totalLinea;
            }

            // 4. ACTUALIZAR TOTAL REAL DE LA VENTA
            $venta->update(['total' => $totalCalculado]);

            DB::commit();

            return redirect()
                ->route('ventas.index')
                ->with('success', 'Venta registrada correctamente. Folio: ' . $venta->folio . ' | Total: $' . number_format($totalCalculado, 2));

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al procesar la venta: ' . $e->getMessage())
                ->withInput();
        }
    }

public function show($id)
{
    // Agregamos 'cliente' al array de relaciones
    $venta = Venta::with(['productos', 'empleado', 'cliente'])->findOrFail($id);
    
    return view('ventas.show', compact('venta'));
}
}