<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    protected $table = 'venta_productos';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'ventas_fk',
        'productos_fk',
        'estado',
        'cantidad',
        'precio',
        'subtotal_p',
        'iva',
        'total',
    ];
}
