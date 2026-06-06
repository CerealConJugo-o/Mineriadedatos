<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $productos = Producto::where('nombre', 'like', "%$search%")
                ->orWhere('cod_producto', 'like', "%$search%")
                ->paginate(10);
        } else {
            $productos = Producto::paginate(10);
        }

        return view('productos.index', compact('productos', 'search'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDACIONES
        $request->validate([
            // 'unique:productos,nombre' evita nombres duplicados en la BD
            'nombre'   => 'required|string|max:100|unique:productos,nombre', 
            'cantidad' => 'required|integer|min:0',
            'precio'   => 'required|numeric|min:0',
        ], [
            // MENSAJES PERSONALIZADOS
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.unique'   => 'Ya existe un producto registrado con ese nombre.',
            'nombre.max'      => 'El nombre no puede superar los 100 caracteres.',
            'cantidad.min'    => 'El stock no puede ser negativo.',
            'precio.min'      => 'El precio no puede ser negativo.',
        ]);

        // 2. GUARDAR
        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        // Buscamos primero para asegurar que existe
        $producto = Producto::findOrFail($id);

        // 1. VALIDACIONES ESPECIALES PARA EDICIÓN
        $request->validate([
            // OJO AQUÍ: 'unique:tabla,columna,ID_A_IGNORAR,COLUMNA_ID'
            // Esto le dice a Laravel: "Revisa si el nombre está repetido, 
            // pero ignora al producto que tiene ESTE cod_producto actual".
            'nombre'   => 'required|string|max:100|unique:productos,nombre,'.$id.',cod_producto',
            'cantidad' => 'required|integer|min:0',
            'precio'   => 'required|numeric|min:0',
        ], [
            'nombre.unique'   => 'Ya existe otro producto con ese nombre.',
            'cantidad.min'    => 'El stock no puede ser negativo.',
        ]);

        // 2. ACTUALIZAR (Protegiendo la llave primaria)
        // Usamos except para asegurar que nadie intente inyectar un cambio de código
        $producto->update($request->except(['cod_producto']));

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }
}