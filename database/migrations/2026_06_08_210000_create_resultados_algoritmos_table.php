<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla para PERSISTIR el resultado de cada ejecución de los algoritmos
 * de minería (KDD, Red Neuronal). Permite que el dashboard muestre el
 * estado y las métricas aunque se cambie de sección o se reinicie.
 *
 * Migración ADITIVA: solo crea una tabla nueva, no modifica ni borra nada.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('resultados_algoritmos')) {
            return;
        }

        Schema::create('resultados_algoritmos', function (Blueprint $table) {
            $table->id();
            $table->string('algoritmo', 50)->index();   // 'kdd' | 'neural'
            $table->decimal('exactitud', 5, 2)->nullable();
            $table->decimal('f1', 5, 2)->nullable();
            $table->json('payload')->nullable();         // resultado completo (JSON)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados_algoritmos');
    }
};
