<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Muestra la lista de clientes
     */
    /**
     * Muestra la lista de clientes con búsqueda avanzada
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Iniciamos el Query Builder
        $query = Cliente::query();

        if ($search) {
            // Agrupamos las condiciones 'OR' dentro de un paréntesis para evitar errores lógicos
            $query->where(function($q) use ($search) {
                $q->where('telefono_movil', 'ilike', "%$search%")  // Busca en Celular
                  ->orWhere('telefono_fijo', 'ilike', "%$search%") // Busca en Fijo (Te faltaba este)
                  ->orWhere('nombre', 'ilike', "%$search%")        // Busca en Nombre
                  ->orWhere('apellido_p', 'ilike', "%$search%")    // Busca en Paterno
                  ->orWhere('apellido_m', 'ilike', "%$search%")    // Busca en Materno
                  // Búsqueda Inteligente: Concatena todo para buscar "Juan Perez" completo
                  ->orWhereRaw("CONCAT(nombre, ' ', apellido_p, ' ', apellido_m) ILIKE ?", ["%{$search}%"]);
            });
        }

        // Ordenamos por apellido para que se vea ordenado y paginamos
        $clientes = $query->orderBy('apellido_p', 'asc')->paginate(10);

        return view('clientes.index', compact('clientes', 'search'));
    }

    /**
     * Muestra el formulario de registro
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guarda el nuevo cliente en la BD
     */
    public function store(Request $request)
    {
        // 1. Validaciones Completas
        $request->validate([
            'telefono_movil' => 'required|numeric|digits_between:10,15|unique:clientes,telefono_movil',
            'nombre'         => 'required|string|max:50',
            'apellido_p'     => 'required|string|max:50',
            'apellido_m'     => 'nullable|string|max:50',
            'telefono_fijo'  => 'nullable|numeric|digits_between:7,15',
            'sexo'           => 'required|string|in:Masculino,Femenino', // Debe coincidir con tu CHECK en SQL
            'correo_completo'=> 'required|email|max:100',
        ], [
            'telefono_movil.unique' => 'Este número de celular ya está registrado.',
            'telefono_movil.digits_between' => 'El celular debe tener entre 10 y 15 dígitos.',
            'sexo.in' => 'Seleccione una opción válida para el sexo (Masculino/Femenino).',
        ]);

        // 2. Preparar datos (quitamos lo que no va directo a la BD)
        $data = $request->except(['correo_completo', '_token']);

        // 3. Separar correo (Lógica de Negocio)
        $emailParts = explode('@', $request->correo_completo);
        if(count($emailParts) == 2){
            $data['correo_nombre']  = $emailParts[0];
            $data['correo_dominio'] = '@' . $emailParts[1];
        } else {
            // Fallback por si acaso pasa un correo raro
            $data['correo_nombre'] = $request->correo_completo;
            $data['correo_dominio'] = ''; 
        }

        // 4. Guardar
        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Muestra un cliente específico
     */
    public function show($id)
    {
        // Redirigimos a edit para simplificar, o podrías hacer una vista 'show' separada
        return redirect()->route('clientes.edit', $id);
    }

    /**
     * Muestra el formulario de edición
     */
    public function edit($id)
    {
        $cliente = Cliente::where('telefono_movil', $id)->firstOrFail();
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza el cliente en la BD
     */
    public function update(Request $request, $id)
    {
        // Buscamos al cliente por su PK (celular)
        $cliente = Cliente::where('telefono_movil', $id)->firstOrFail();

        // 1. VALIDAMOS LOS CAMPOS POR SEPARADO
        // (Asumimos que tu vista edit.blade.php tiene inputs para 'correo_nombre' y 'correo_dominio')
        $request->validate([
            'nombre'          => 'required|string|max:50',
            'apellido_p'      => 'required|string|max:50',
            'apellido_m'      => 'nullable|string|max:50',
            'telefono_fijo'   => 'nullable|numeric|digits_between:7,15',
            'sexo'            => 'required|string|in:Masculino,Femenino',
            
            // VALIDACIÓN DESGLOSADA (Esto arregla el error)
            'correo_nombre'   => 'required|string|max:50',
            'correo_dominio'  => 'required|string|max:50', 
        ], [
            'correo_nombre.required'  => 'El usuario del correo es obligatorio.',
            'correo_dominio.required' => 'El dominio del correo (@gmail.com) es obligatorio.',
        ]);

        // 2. PREPARAR DATOS
        // Protegemos la PK (telefono_movil) para que no la cambien
        $data = $request->except(['telefono_movil', '_token', '_method']);

        // Como ya vienen separados, pasan directos al update
        // (Laravel automáticamente machea 'correo_nombre' del request con la columna de la BD)

        // 3. ACTUALIZAR
        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Elimina el cliente
     */
    public function destroy($id)
    {
        $cliente = Cliente::where('telefono_movil', $id)->firstOrFail();
        
        // Opcional: Validar si tiene ventas/exámenes antes de borrar
        // if($cliente->examenes()->exists()) { return back()->with('error', 'No se puede eliminar...'); }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}