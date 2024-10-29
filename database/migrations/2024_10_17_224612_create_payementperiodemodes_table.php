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
        Schema::create('payementperiodemodes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('order_id');
            $table->decimal('total_amount', 10, 2);
            $table->integer('period')->default(6); // Période par défaut à 6 mois
            $table->decimal('month_1', 10, 2);
            $table->decimal('month_2', 10, 2)->nullable();
            $table->decimal('month_3', 10, 2)->nullable();
            $table->decimal('month_4', 10, 2)->nullable();
            $table->decimal('month_5', 10, 2)->nullable();
            $table->decimal('month_6', 10, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payementperiodemodes');
    }
};
