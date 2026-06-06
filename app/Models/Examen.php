<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examenes';

    public $incrementing = false;
    public $timestamps = false;
    
    // Al poner null, le decimos a Laravel: "Yo me encargo de los updates/deletes manuales"
    protected $primaryKey = null; 

    protected $fillable = [
        'fecha',
        'empleados_fk',
        'clientes_fk',
        'resultado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleados_fk', 'nss');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clientes_fk', 'telefono_movil');
    }
}