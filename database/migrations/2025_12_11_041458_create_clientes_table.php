<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigInteger('telefono_movil')->primary();
            $table->string('nombre', 50)->nullable();
            $table->string('apellido_p', 50)->nullable();
            $table->string('apellido_m', 50)->nullable();
            $table->bigInteger('telefono_fijo')->nullable();
            $table->string('correo_nombre', 45)->nullable();
            $table->string('correo_dominio', 100)->nullable();
            $table->enum('sexo', ['Femenino','Masculino'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
