<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProductosTable extends Migration
{
    public function up()
    {
        Schema::create('pedido_productos', function (Blueprint $table) {
            $table->integer('pedidos_fk')->unsigned();
            $table->integer('productos_fk')->unsigned();
            $table->string('especif', 255)->nullable();
            $table->integer('cantidad')->nullable();

            $table->primary(['pedidos_fk', 'productos_fk']);

            $table->foreign('pedidos_fk')->references('numero_pedido')->on('pedidos')->onDelete('cascade');
            $table->foreign('productos_fk')->references('cod_producto')->on('productos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_productos');
    }
}
