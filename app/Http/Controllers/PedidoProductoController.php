<?php

namespace App\Http\Controllers;

use App\Models\PedidoProducto;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoProductoController extends Controller
{
    /**
     * STORE: Agrega un producto a un pedido existente.
     * Ruta sugerida: POST /pedidos/{pedido}/agregar-producto
     */
    public function store(Request $request)
    {
        // 1. Validamos solo lo necesario para EL PRODUCTO
        // No validamos fecha ni proveedor, porque el pedido ya existe.
        $request->validate([
            'pedidos_fk'   => 'required|exists:pedidos,numero_pedido',
            'productos_fk' => 'required|exists:productos,cod_producto',
            'cantidad'     => 'required|integer|min:1',
            'especif'      => 'nullable|string|max:100',
        ]);

        // 2. Verificar estado del pedido (Regla de Negocio)
        // No deberíamos poder agregar cosas si el pedido ya fue "Recibido" o "Cancelado"
        $pedido = Pedido::findOrFail($request->pedidos_fk);
        
        if ($pedido->estado_pedido !== 'Pendiente') {
            return back()->withErrors(['error' => 'No se pueden agregar productos a un pedido cerrado.']);
        }

        // 3. Verificar si el producto ya está en el pedido (para sumar cantidad en vez de duplicar fila)
        $existente = PedidoProducto::where('pedidos_fk', $request->pedidos_fk)
                                   ->where('productos_fk', $request->productos_fk)
                                   ->first();

        if ($existente) {
            // Si ya existe, sumamos la cantidad
            $existente->cantidad += $request->cantidad;
            $existente->save();
        } else {
            // Si no existe, creamos la fila nueva
            PedidoProducto::create([
                'pedidos_fk'   => $request->pedidos_fk,
                'productos_fk' => $request->productos_fk,
                'cantidad'     => $request->cantidad,
                'especif'      => $request->especif,
            ]);
        }

        return back()->with('success', 'Producto agregado al pedido.');
    }

    /**
     * DESTROY: Elimina una fila (un producto) del pedido.
     */
    public function destroy($id)
    {
        // Buscamos la línea del detalle
        $item = PedidoProducto::findOrFail($id);
        
        // Verificamos estado del pedido padre antes de borrar
        if ($item->pedido->estado_pedido !== 'Pendiente') {
            return back()->withErrors(['error' => 'No se puede modificar un pedido cerrado.']);
        }

        $item->delete();

        return back()->with('success', 'Producto eliminado del pedido.');
    }
}