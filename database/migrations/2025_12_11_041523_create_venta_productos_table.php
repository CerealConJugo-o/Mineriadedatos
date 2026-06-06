<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaProductosTable extends Migration
{
    public function up()
    {
        Schema::create('venta_productos', function (Blueprint $table) {
            $table->integer('ventas_fk')->unsigned();
            $table->integer('productos_fk')->unsigned();
            $table->string('estado', 50)->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->decimal('subtotal_p', 10, 2)->nullable();
            $table->decimal('iva', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();

            $table->primary(['ventas_fk', 'productos_fk']);

            $table->foreign('ventas_fk')->references('folio')->on('ventas')->onDelete('cascade');
            $table->foreign('productos_fk')->references('cod_producto')->on('productos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('venta_productos');
    }
}
