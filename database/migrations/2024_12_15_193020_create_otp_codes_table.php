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
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->unsignedBigInteger('user_id'); // Référence à l'utilisateur
            $table->string('otp_code', 6); // Code OTP
            $table->timestamp('expires_at'); // Date et heure d'expiration
            $table->boolean('is_used')->default(false); // Statut utilisé/non utilisé
            $table->timestamps(); // Colonnes `created_at` et `updated_at`
            $table->softDeletes(); // Colonne `deleted_at` pour les soft deletes

            // Clé étrangère vers la table `users`
            $table->foreign('user_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
