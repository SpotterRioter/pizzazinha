<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('pizza_id')->nullable()->constrained('pizzas')->nullOnDelete();
            $table->unsignedSmallInteger('quantidade')->default(1);
            $table->string('status')->default('pendente');
            $table->date('data_pedido');
            $table->date('data_entrega')->nullable();
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index('data_pedido');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
