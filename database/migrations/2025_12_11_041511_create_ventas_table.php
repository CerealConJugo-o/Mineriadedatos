<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('folio');
            $table->date('fecha')->nullable();
            $table->string('servicios', 255)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('estado', 50)->nullable();
            $table->bigInteger('empleado_fk')->unsigned()->nullable();
            $table->foreign('empleado_fk')->references('nss')->on('empleados')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
