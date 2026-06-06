<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- FALTABA ESTA LÍNEA
use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = ['empleado_nss', 'monto', 'fecha_pago', 'concepto', 'notas'];

    // Relación con Empleado
    public function empleado()
    {
        // Conectamos 'empleado_nss' (local) con 'nss' (remoto en empleados)
        return $this->belongsTo(Empleado::class, 'empleado_nss', 'nss');
    }
}