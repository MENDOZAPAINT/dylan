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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nombre del plato
            $table->text('description')->nullable(); // Descripción del plato
            $table->decimal('price', 10, 2); // Precio de venta
            $table->string('image')->nullable(); // Ruta de la imagen
            $table->boolean('is_highlighted')->default(false); // Plato destacado
            $table->boolean('is_available')->default(true); // Disponible
            $table->integer('preparation_time')->nullable(); // Tiempo en minutos
            $table->integer('order')->default(0); // Orden dentro de la categoría
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
