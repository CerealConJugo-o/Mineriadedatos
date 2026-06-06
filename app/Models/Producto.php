<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'cod_producto'; // Tu llave primaria

    // IMPORTANTE: Desactivar timestamps porque tu tabla SQL no los tiene
    public $timestamps = false; 

    protected $fillable = [
        'nombre',
        'cantidad',
        'precio'
    ];
}