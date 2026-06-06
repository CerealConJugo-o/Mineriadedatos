<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    protected $table = 'pedido_productos';

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'pedidos_fk',
        'productos_fk',
        'especif',
        'cantidad',
    ];
}

