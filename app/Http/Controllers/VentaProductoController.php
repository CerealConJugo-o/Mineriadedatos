<?php

namespace App\Http\Controllers;

use App\Models\VentaProducto;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaProductoController extends Controller
{
    /**
     * STORE: Agrega un producto extra a una venta ya cerrada.
     * (O aumenta la cantidad si ya existía).
     */
    public function store(Request $request)
    {
        // 1. VALIDACIONES
        $request->validate([
            'ventas_fk'    => 'required|exists:ventas,folio',
            'productos_fk' => 'required|exists:productos,cod_producto',
            'cantidad'     => 'required|integer|min:1',
        ], [
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);

        DB::beginTransaction();

        try {
            // A) Obtener la Venta Padre
            $venta = Venta::findOrFail($request->ventas_fk);

            // B) Obtener Producto y Bloquear Stock (Vital para no vender lo que no hay)
            $producto = Producto::where('cod_producto', $request->productos_fk)
                ->lockForUpdate()
                ->firstOrFail();

            // C) Validar Stock Disponible
            if ($producto->cantidad < $request->cantidad) {
                throw new \Exception("No hay suficiente stock. Disponibles: {$producto->cantidad}");
            }

            // D) Calcular Costos (Usamos precio actual de la BD, no del request)
            $precioUnitario = $producto->precio;
            $subtotal       = $precioUnitario * $request->cantidad;
            $iva            = $subtotal * 0.16;
            $totalLinea     = $subtotal + $iva;

            // E) Verificar si el producto ya estaba en la venta
            $itemExistente = VentaProducto::where('ventas_fk', $venta->folio)
                                          ->where('productos_fk', $producto->cod_producto)
                                          ->first();

            if ($itemExistente) {
                // SI YA EXISTE: Sumamos cantidades y dinero
                $itemExistente->cantidad   += $request->cantidad;
                $itemExistente->subtotal_p += $subtotal;
                $itemExistente->iva        += $iva;
                $itemExistente->total      += $totalLinea;
                $itemExistente->save();
            } else {
                // SI ES NUEVO: Creamos la fila
                VentaProducto::create([
                    'ventas_fk'    => $venta->folio,
                    'productos_fk' => $producto->cod_producto,
                    'cantidad'     => $request->cantidad,
                    'precio'       => $precioUnitario,
                    'subtotal_p'   => $subtotal,
                    'iva'          => $iva,
                    'total'        => $totalLinea,
                    'estado'       => 'Entregado'
                ]);
            }

            // F) ACTUALIZAR INVENTARIO (Resta Stock)
            $producto->decrement('cantidad', $request->cantidad);

            // G) ACTUALIZAR TOTAL DE LA VENTA PADRE (Suma Dinero)
            $venta->increment('total', $totalLinea);

            DB::commit();

            return back()->with('success', 'Producto agregado y stock actualizado.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * DESTROY: Elimina un producto de la venta (Devolución).
     * IMPORTANTE: Debe regresar el stock al inventario.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // 1. Buscar el detalle de la venta
            $item = VentaProducto::findOrFail($id);
            $venta = Venta::findOrFail($item->ventas_fk);
            
            // 2. Buscar el producto original para devolverle el stock
            // Usamos lockForUpdate para evitar condiciones de carrera
            $producto = Producto::where('cod_producto', $item->productos_fk)
                ->lockForUpdate()
                ->first();

            // 3. REGRESAR STOCK AL INVENTARIO (Si el producto aún existe en BD)
            if ($producto) {
                $producto->increment('cantidad', $item->cantidad);
            }

            // 4. RESTAR EL DINERO A LA VENTA PADRE
            // (Si borramos el item, la venta vale menos)
            $venta->decrement('total', $item->total);

            // 5. Eliminar la fila
            $item->delete();

            DB::commit();

            return back()->with('success', 'Producto eliminado y stock devuelto al inventario.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    // Los métodos index, create, show, edit, update no suelen usarse 
    // en este controlador auxiliar, así que los puedes dejar vacíos o borrarlos.
}