<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pizzas', function (Blueprint $table) {
            $table->id();
            $table->string('sabor');
            $table->text('ingredientes');
            $table->string('tamanho', 1)->default('M'); // P, M ou G
            $table->decimal('preco', 8, 2);
            $table->boolean('disponivel')->default(true);
            $table->boolean('personalizada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pizzas');
    }
};
