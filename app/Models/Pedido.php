<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    

    // 1. DEFINIMOS LA LLAVE PRIMARIA (Esto arregla el error)
    protected $primaryKey = 'numero_pedido';

    // 2. Si tu tabla no usa created_at/updated_at, descomenta esto:
    // public $timestamps = false;

    // 3. Tus campos fillable (Asegúrate de que estén aquí)
    protected $fillable = [
        'fecha_entrega',
        'fecha_solicitud',
        'estado_pedido',
        'proveedores_fk',
        'empleado_nss' // <--- No olvides agregar esto si lo vas a guardar
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedores_fk', 'telefono');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_productos', 'pedidos_fk', 'productos_fk')
            ->withPivot(['especif', 'cantidad']);
    }
}
