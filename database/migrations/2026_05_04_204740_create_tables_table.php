<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique(); // Número de mesa (único)
            $table->string('zone')->nullable(); // Zona (terraza, interior, VIP)
            $table->integer('capacity')->default(4); // Capacidad en personas
            $table->enum('status', ['free', 'occupied'])->default('free'); // Estado
            $table->foreignId('mozo_id')->nullable()->constrained('users')->nullOnDelete(); // Mozo asignado
            $table->boolean('is_active')->default(true); // Mesa activa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
