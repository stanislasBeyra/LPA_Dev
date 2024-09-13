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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_description');
            $table->integer('stock')->default(0);
            $table->double('price');
            $table->integer('vendor_id')->nullable(); // Correction
            $table->integer('categorie_id')->nullable(); // Correction
            $table->string('product_images1')->nullable();
            $table->string('product_images2')->nullable();
            $table->string('product_images3')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
