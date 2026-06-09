<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $primaryKey = 'folio';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'folio',
        'fecha',
        'servicios',
        'total',
    ];
}