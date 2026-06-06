<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            // PK según tu SQL
            $table->bigInteger('nss')->primary(); 
            
            // Conexión con el Login (CRÍTICO para que funcione tu sistema)
            $table->foreignId('user_id')->nullable()->constrained('users')->unique()->onDelete('cascade');

            $table->string('nombre', 50); // Aumenté un poco el tamaño por seguridad
            $table->string('apellido_p', 50);
            $table->string('apellido_m', 50);
            
            // CURP Desglosado
            $table->string('curp_nombre', 4);
            $table->integer('curp_fecha');
            $table->string('curp_genero', 1); // Check H/M se maneja en lógica o DB raw
            $table->string('curp_entidad', 2);
            $table->string('curp_conso', 3);
            $table->string('curp_dif', 2);

            $table->bigInteger('telefono_movil');
            $table->bigInteger('telefono_fijo');

            // Correo Desglosado
            $table->string('correo_nombre', 45);
            $table->string('correo_dominio', 100); // Aumenté tamaño para dominios largos

            $table->string('sexo', 10);
            
            // Dirección Desglosada (Tu SQL original)
            $table->string('calle', 50);
            $table->integer('numero');
            $table->string('colonia', 25);
            $table->integer('cp');
            $table->string('delegacion', 20);

            $table->string('rol', 50);
            $table->decimal('salario', 10, 2); // (10,2) es mejor para dinero que (7,2)
            
            // Campos opcionales si los necesitas
            $table->string('cedula_profesional')->nullable(); 

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
