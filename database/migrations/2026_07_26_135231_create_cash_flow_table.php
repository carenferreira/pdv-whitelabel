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
        Schema::create('cash_flow', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('type'); //entrada ou saída
            $table->string('paymant_method'); //dinheiro, débito, crédito, pix
            $table->string('movement_type'); //venda, reenbolso, despesa, dinheiro para troco
            $table->integer('value');
            $table->text('description')->nullable();
            $table->string('source_type'); //pedido, mesa, despesa, caixa
            $table->integer('source_id')->nullable(); //id da mesa, pedido ou caixa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flow');
    }
};
