<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->date('fecha');
            $table->time('hora');
            $table->integer('ventas_fk')->unsigned();
            $table->decimal('monto', 10, 2)->nullable();
            $table->string('tipo_pago', 50)->nullable();

            $table->primary(['fecha', 'hora', 'ventas_fk']);

            $table->foreign('ventas_fk')->references('folio')->on('ventas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
