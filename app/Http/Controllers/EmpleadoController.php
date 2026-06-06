<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User; // Importamos el modelo User
use App\Models\Role; // Importamos el modelo Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para encriptar la contraseña
use Illuminate\Support\Facades\DB;   // Para transacciones

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $empleados = Empleado::query()
                // Buscamos por NSS
                ->where('nss', 'ilike', "%$search%") // 'ilike' ignora mayúsculas en Postgres
                
                // O buscamos por Nombre solo
                ->orWhere('nombre', 'ilike', "%$search%")
                
                // O buscamos por Apellido Paterno
                ->orWhere('apellido_p', 'ilike', "%$search%")
                
                // O buscamos por Apellido Materno (este te faltaba)
                ->orWhere('apellido_m', 'ilike', "%$search%")
                
                // O buscamos por NOMBRE COMPLETO CONCATENADO
                // Esto permite que si escriben "Juan Perez" lo encuentre
                ->orWhereRaw("CONCAT(nombre, ' ', apellido_p, ' ', apellido_m) ILIKE ?", ["%{$search}%"])
                
                ->paginate(10);
        } else {
            $empleados = Empleado::paginate(10);
        }

        return view('empleados.index', compact('empleados', 'search'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        // 1. Validaciones
        $request->validate([
            // Datos Empleado
            'nss'            => 'required|unique:empleados,nss|max:20',
            'nombre'         => 'required|string|max:100',
            'apellido_p'     => 'required|string|max:100',
            'apellido_m'     => 'required|string|max:100',
            'curp'           => 'required|string|size:18',
            'sexo'           => 'required|string|in:H,M',
            'telefono_movil' => 'required|numeric|digits_between:10,15',
            'telefono_fijo'  => 'required|numeric|digits_between:7,15',
            'calle'          => 'required|string',
            'numero'         => 'required|string',
            'colonia'        => 'required|string',
            'cp'             => 'required|numeric',
            'delegacion'     => 'required|string',
            'rol'            => 'required|string',
            'salario'        => 'required|numeric|min:0',
            'cedula_profesional' => 'nullable|string|max:50',
            
            // Datos de Cuenta (User)
            'correo_completo'=> 'required|email|max:100|unique:users,email', // Unique en users también
            'password'       => 'required|string|min:8', // Nueva validación
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'nss.unique' => 'Este NSS ya está registrado.',
            'correo_completo.unique' => 'Este correo ya está registrado como usuario.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // Usamos una transacción para asegurar que se creen ambos o ninguno
        DB::beginTransaction();

        try {
            // A) Buscar el ID del Rol en la base de datos
            // Buscamos en la tabla 'roles' el rol seleccionado (ej: 'vendedor')
            $rolDB = Role::where('nombre', $request->rol)->first();

            if (!$rolDB) {
                throw new \Exception("El rol seleccionado '{$request->rol}' no existe en la base de datos de roles.");
            }

            // B) Crear el Usuario (Login)
            $newUser = User::create([
                'name'     => $request->nombre . ' ' . $request->apellido_p,
                'email'    => $request->correo_completo,
                'password' => Hash::make($request->password), // Encriptamos
                'role_id'  => $rolDB->id, // Asignamos el ID numérico del rol
            ]);

            // C) Preparar Datos del Empleado
            $data = $request->except(['curp', 'correo_completo', '_token', 'password']);
            
            // Procesar CURP
            $curp = strtoupper($request->curp);
            $data['curp_nombre']  = substr($curp, 0, 4);
            $data['curp_fecha']   = substr($curp, 4, 6);
            $data['curp_genero']  = substr($curp, 10, 1);
            $data['curp_entidad'] = substr($curp, 11, 2);
            $data['curp_conso']   = substr($curp, 13, 3);
            $data['curp_dif']     = substr($curp, 16, 2);

            // Procesar Correo (para campos separados en tabla empleados)
            $emailParts = explode('@', $request->correo_completo);
            if(count($emailParts) == 2){
                $data['correo_nombre']  = $emailParts[0];
                $data['correo_dominio'] = '@' . $emailParts[1];
            } else {
                $data['correo_nombre'] = $request->correo_completo;
                $data['correo_dominio'] = ''; 
            }

            // D) Vincular el Usuario al Empleado
            $data['user_id'] = $newUser->id;

            // E) Crear Empleado
            Empleado::create($data);

            DB::commit(); // Confirmar cambios

            return redirect()->route('empleados.index')
                ->with('success', 'Empleado y Usuario de sistema registrados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer si hay error
            return back()
                ->with('error', 'Error al registrar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        // 1. VALIDACIÓN (Aquí faltaban los mensajes personalizados)
        $request->validate([
            'nombre'         => 'required|string|max:100',
            'apellido_p'     => 'required|string|max:100',
            'sexo'           => 'required|string|in:H,M',
            'telefono_movil' => 'required|numeric',
            // Quitamos 'unique' estricto para permitir que guarde su propio correo
            'correo_completo'=> 'required|email|max:100', 
            'salario'        => 'required|numeric|min:0',
        ], [
            // ESTOS SON LOS MENSAJES QUE FALTABAN
            'required' => 'El campo :attribute es obligatorio.',
            'numeric'  => 'El campo :attribute debe ser un número.',
            'min'      => 'El campo :attribute no puede ser negativo.',
        ]);

        // 2. PREPARACIÓN DE DATOS
        // Excluimos campos que no deben cambiar o que procesamos manual
        $data = $request->except(['nss', 'curp', 'correo_completo', '_token', 'password']); 

        // 3. LÓGICA DEL CORREO (Solo si cambió)
        if ($request->has('correo_completo') && $request->correo_completo != $empleado->correo_completo) {
             $parts = explode('@', $request->correo_completo);
             if(count($parts) == 2){
                $data['correo_nombre']  = $parts[0];
                $data['correo_dominio'] = '@' . $parts[1];
             }
             
             // Actualizar email del usuario vinculado también
             if($empleado->user) {
                 // Verificamos que el nuevo correo no lo tenga OTRO usuario
                 $existeUser = User::where('email', $request->correo_completo)
                                   ->where('id', '!=', $empleado->user_id)
                                   ->exists();
                                   
                 if($existeUser) {
                     return back()->withErrors(['correo_completo' => 'Este correo ya está en uso por otro usuario.'])->withInput();
                 }

                 $empleado->user->update(['email' => $request->correo_completo]);
             }
        }

        // 4. ACTUALIZAR
        $empleado->update($data);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        
        // Opcional: Eliminar también el usuario de login
        if($empleado->user) {
            $empleado->user->delete();
        }

        $empleado->delete();
        
        return redirect()->route('empleados.index')
            ->with('success', 'Empleado y cuenta eliminados.');
    }
}