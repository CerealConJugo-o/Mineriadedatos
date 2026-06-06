<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'registro_a';
    protected $primaryKey = 'id_a';
    public $incrementing = false; // Importante porque tu PK no es ID autoincrementable

    // Aseguramos que se puedan guardar estos datos
    protected $guarded = []; 

    // ==========================================
    // ACCESSORS (Esto hace la magia en la vista)
    // ==========================================

    // 1. Para llamar a: $empleado->nombre_completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido_p} {$this->apellido_m}";
    }

    // 2. Para llamar a: $empleado->curp_completa
    public function getCurpCompletaAttribute()
    {
        // Concatenamos todos los pedacitos de la CURP
        return $this->curp_nombre . 
               $this->curp_fecha . 
               $this->curp_genero . 
               $this->curp_entidad . 
               $this->curp_conso . 
               $this->curp_dif;
    }

    // 3. Para llamar a: $empleado->correo_completo
    // (Nota: Aunque en tu base de datos ya lo guardas separado, 
    // a veces es útil tener el getter por si acaso)
    public function getCorreoCompletoAttribute()
    {
        // Si ya tienes una columna real llamada 'correo_nombre' y 'correo_dominio'
        return $this->correo_nombre . $this->correo_dominio;
    }

    // Relación inversa (Opcional pero recomendada)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}