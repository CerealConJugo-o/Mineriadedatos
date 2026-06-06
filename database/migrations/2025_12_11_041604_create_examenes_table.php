<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenesTable extends Migration
{
    public function up()
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->timestamp('fecha');
            $table->bigInteger('empleados_fk')->unsigned();
            $table->bigInteger('clientes_fk')->unsigned()->nullable();
            $table->string('resultado', 255)->nullable();

            $table->primary(['fecha', 'empleados_fk']);

            $table->foreign('empleados_fk')->references('nss')->on('empleados')->onDelete('cascade');
            $table->foreign('clientes_fk')->references('telefono_movil')->on('clientes')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examenes');
    }
}
