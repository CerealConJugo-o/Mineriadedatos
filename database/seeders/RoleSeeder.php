<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear Roles
        $rolDBA          = Role::firstOrCreate(['nombre' => 'dba']);
        $rolGerente      = Role::firstOrCreate(['nombre' => 'gerente']);
        $rolVendedor     = Role::firstOrCreate(['nombre' => 'vendedor']);
        $rolAlmacenista  = Role::firstOrCreate(['nombre' => 'almacenista']);
        $rolOptometrista = Role::firstOrCreate(['nombre' => 'optometrista']);

        $userDBA = User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // Correo del DBA
            [
                'name'     => 'System Admin (DBA)',
                'password' => Hash::make('12345'), // Contraseña fuerte
                'role_id'  => $rolDBA->id,
            ]
        );

        // === GERENTE ===
        $userGerente = User::firstOrCreate(
            ['email' => 'admin@optics.com'],
            ['name' => 'Gerente General', 'password' => Hash::make('12345'), 'role_id' => $rolGerente->id]
        );

        Empleado::firstOrCreate(
            ['user_id' => $userGerente->id],
            [
                'nss' => 1000000001, // BIGINT
                'nombre' => 'Gerente',
                'apellido_p' => 'General',
                'apellido_m' => 'Boss',
                'curp_nombre' => 'GGBO',
                'curp_fecha' => 900101,
                'curp_genero' => 'M',
                'curp_entidad' => 'DF',
                'curp_conso' => 'X01',
                'curp_dif' => '01',
                'telefono_movil' => 5512345678,
                'telefono_fijo' => 5556781234,
                'correo_nombre' => 'admin',
                'correo_dominio' => '@optics.com',
                'sexo' => 'Masculino',
                // DIRECCIÓN DESGLOSADA
                'calle' => 'Av. Central',
                'numero' => 101,
                'colonia' => 'Centro',
                'cp' => 57000,
                'delegacion' => 'Nezahualcoyotl',
                'rol' => 'Gerente',
                'salario' => 15000.00
            ]
        );

        // === VENDEDOR ===
        $userVentas = User::firstOrCreate(
            ['email' => 'ventas1@optics.com'],
            ['name' => 'Vendedor Uno', 'password' => Hash::make('12345'), 'role_id' => $rolVendedor->id]
        );

        Empleado::firstOrCreate(
            ['user_id' => $userVentas->id],
            [
                'nss' => 2000000002,
                'nombre' => 'Juan',
                'apellido_p' => 'Perez',
                'apellido_m' => 'Lopez',
                'curp_nombre' => 'JUPL',
                'curp_fecha' => 950505,
                'curp_genero' => 'H',
                'curp_entidad' => 'MX',
                'curp_conso' => 'X02',
                'curp_dif' => '02',
                'telefono_movil' => 5587654321,
                'telefono_fijo' => 5511223344,
                'correo_nombre' => 'ventas1',
                'correo_dominio' => '@optics.com',
                'sexo' => 'Masculino',
                'calle' => 'Calle Ventas',
                'numero' => 20,
                'colonia' => 'Comercial',
                'cp' => 57100,
                'delegacion' => 'Iztacalco',
                'rol' => 'Vendedor',
                'salario' => 6000.00
            ]
        );

        // === OPTOMETRISTA ===
        $userOpto = User::firstOrCreate(
            ['email' => 'opto1@optics.com'],
            ['name' => 'Dra. Ana', 'password' => Hash::make('12345'), 'role_id' => $rolOptometrista->id]
        );

        Empleado::firstOrCreate(
            ['user_id' => $userOpto->id],
            [
                'nss' => 3000000003,
                'nombre' => 'Ana',
                'apellido_p' => 'Rios',
                'apellido_m' => 'Solis',
                'curp_nombre' => 'ANRS',
                'curp_fecha' => 880808,
                'curp_genero' => 'M',
                'curp_entidad' => 'DF',
                'curp_conso' => 'X03',
                'curp_dif' => '03',
                'telefono_movil' => 5599887766,
                'telefono_fijo' => 5544332211,
                'correo_nombre' => 'opto1',
                'correo_dominio' => '@optics.com',
                'sexo' => 'Femenino',
                'calle' => 'Calle Salud',
                'numero' => 45,
                'colonia' => 'Doctores',
                'cp' => 06720,
                'delegacion' => 'Cuauhtemoc',
                'rol' => 'Optometrista',
                'salario' => 8500.00,
                'cedula_profesional' => 'CED12345' // Campo extra
            ]
        );

        Empleado::firstOrCreate(
            ['user_id' => $userDBA->id],
            [
                'nss' => 9999999999,
                'nombre' => 'System',
                'apellido_p' => 'Admin',
                'apellido_m' => 'DBA',
                // ... llena los demás campos obligatorios con datos dummy ...
                'rol' => 'DBA',
                'salario' => 0,
                // Agrega los campos de dirección, curp, etc...
            ]
        );
    }
}