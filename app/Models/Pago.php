<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = ['fecha','hora','ventas_fk','monto','tipo_pago'];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'ventas_fk', 'folio');
    }
}
