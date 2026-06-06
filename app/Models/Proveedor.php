<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    // Configuración para tu Primary Key (Telefono)
    protected $primaryKey = 'telefono';
    public $incrementing = false; // No es un ID autoincrementable
    protected $keyType = 'int';   // Es un número grande (BIGINT)
    
    // ¡IMPORTANTE! Tu tabla SQL no tiene fechas, así que desactivamos esto:
    public $timestamps = false; 

    protected $fillable = [
        'nombre',
        'telefono',
        'correo_nombre',
        'correo_dominio',
    ];

    // Accesores para mostrar datos completos en la vista
    public function getCorreoCompletoAttribute()
    {
        return $this->correo_nombre . $this->correo_dominio;
    }
}