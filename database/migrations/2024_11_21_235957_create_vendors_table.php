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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('vendorname');
            $table->string('contactpersonname');
            $table->string('businessregno');
            $table->string('taxidnumber'); // Tax Identification Number
            $table->string('businesscategory');
            $table->text('businessaddress');
            $table->string('bankname1')->nullable();
            $table->string('bankaccount1')->nullable();
            $table->string('bankname2')->nullable();
            $table->string('bankaccount2')->nullable();
            $table->string('accountholdername')->nullable();
            $table->string('businesscertificate')->nullable(); // Fichier uploadé
            $table->string('taxcertificate')->nullable(); // Fichier uploadé
            $table->string('passportorID')->nullable(); // fichier uploader
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
