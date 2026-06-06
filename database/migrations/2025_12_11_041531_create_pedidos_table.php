<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('numero_pedido');
            $table->timestamp('fecha_entrega')->nullable();
            $table->timestamp('fecha_solicitud')->nullable();
            $table->string('estado_pedido', 50)->nullable();
            $table->bigInteger('proveedores_fk')->unsigned()->nullable();
            $table->foreign('proveedores_fk')->references('telefono')->on('proveedores')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
