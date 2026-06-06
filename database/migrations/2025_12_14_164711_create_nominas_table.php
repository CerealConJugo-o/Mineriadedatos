<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();

            // CORRECCIÓN: Usamos bigInteger para coincidir con tu tabla empleados
            $table->bigInteger('empleado_nss'); 
            
            // Definimos la llave foránea
            $table->foreign('empleado_nss')
                  ->references('nss')
                  ->on('empleados')
                  ->onDelete('cascade');
            
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->string('concepto')->default('Nómina Quincenal');
            $table->text('notas')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};