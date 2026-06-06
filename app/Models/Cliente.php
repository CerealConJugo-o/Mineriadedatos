<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    
    // DEFINIMOS EL TELÉFONO COMO LLAVE PRIMARIA
    protected $primaryKey = 'telefono_movil';
    public $incrementing = false; // Importante: porque no es un ID autoincrementable 1,2,3...
    protected $keyType = 'string'; // O 'int' si lo manejas como número puro, pero string es más seguro para teléfonos largos

    protected $fillable = [
        'telefono_movil',
        'nombre',
        'apellido_p',
        'apellido_m',
        'telefono_fijo',
        'correo_nombre',
        'correo_dominio',
        'sexo'
    ];

    // Accessor para ver el correo completo
    public function getCorreoCompletoAttribute()
    {
        return $this->correo_nombre . $this->correo_dominio;
    }
    
    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido_p . ' ' . $this->apellido_m);
    }
}