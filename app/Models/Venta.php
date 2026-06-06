<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $primaryKey = 'folio';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'folio',
        'fecha',
        'servicios',
        'total',
        'estado',
        'empleado_fk',
        'created_at',
        'updated_at'
    ];

    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'venta_productos',
            'ventas_fk',
            'productos_fk'
        )->withPivot([
            'estado',
            'cantidad',
            'precio',
            'subtotal_p',
            'iva',
            'total'
        ]);
    }

    public function empleado()
    {
        return $this->belongsTo(
            Empleado::class,
            'empleado_fk',
            'id_a'
        );
    }
}