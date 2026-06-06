<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $proveedores = Proveedor::where('nombre', 'like', "%$search%")
                ->orWhere('telefono', 'like', "%$search%")
                ->paginate(10); // Agregamos paginación
        } else {
            $proveedores = Proveedor::paginate(10);
        }

        return view('proveedores.index', compact('proveedores', 'search'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDACIONES
        $request->validate([
            // CRÍTICO: Tu SQL solo acepta 20 caracteres. Si pasas 21, truena.
            'nombre'   => 'required|string|max:20', 
            // El teléfono es la Primary Key
            'telefono' => 'required|numeric|digits_between:7,15|unique:proveedores,telefono',
            'correo_completo' => 'required|email|max:90', 
        ], [
            'nombre.max' => 'El nombre es muy largo (Máximo 20 letras). Usa abreviaciones.',
            'telefono.unique' => 'Este teléfono ya está registrado como proveedor.',
        ]);

        // 2. PROCESAR CORREO (Dividir en dos columnas)
        $parts = explode('@', $request->correo_completo);
        $correo_nombre = $parts[0];
        // Aseguramos que exista el dominio por si acaso
        $correo_dominio = isset($parts[1]) ? '@'.$parts[1] : '';

        // 3. GUARDAR
        Proveedor::create([
            'nombre'   => $request->nombre,
            'telefono' => $request->telefono,
            'correo_nombre' => $correo_nombre,
            'correo_dominio' => $correo_dominio,
        ]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit($id)
    {
        // Buscamos por la PK (telefono)
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        // 1. VALIDAR LOS CAMPOS SEPARADOS (Tal como los envía la vista)
        $request->validate([
            'nombre'         => 'required|string|max:20',
            'correo_nombre'  => 'required|string|max:45', // Mitad del correo
            'correo_dominio' => 'required|string|max:45', // La otra mitad (@algo.com)
        ], [
            'nombre.max' => 'El nombre es muy largo (Máximo 20 letras).',
            'correo_nombre.required' => 'Falta la primera parte del correo.',
            'correo_dominio.required' => 'Falta el dominio del correo.',
        ]);

        // 2. ACTUALIZAR DIRECTAMENTE
        // Como ya vienen separados del formulario, no necesitamos hacer "explode"
        $proveedor->update([
            'nombre'         => $request->nombre,
            'correo_nombre'  => $request->correo_nombre,
            'correo_dominio' => $request->correo_dominio,
        ]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        
        // Opcional: Validar si tiene pedidos antes de borrar
        // if($proveedor->pedidos()->exists()) {
        //    return back()->with('error', 'No puedes borrar este proveedor porque tiene pedidos asociados.');
        // }

        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado.');
    }
}