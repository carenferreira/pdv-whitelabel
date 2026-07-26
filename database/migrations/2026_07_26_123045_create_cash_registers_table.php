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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('opening_value'); //valor inicial (em centavos)
            $table->dateTime('opening_date');
            $table->dateTime('closing_date')->nullable();
            $table->integer('expected_value')->nullable(); //valor final esperado (calculado pelo sistema)
            $table->integer('actual_value')->nullable(); //valor real informado
            $table->integer('difference')->nullable();
            $table->string('status')->default('open'); //open | close
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
