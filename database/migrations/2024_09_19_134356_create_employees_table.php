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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('firstname'); // Correction de 'fistname' en 'firstname'
            $table->string('lastname');
            $table->string('username')->unique();
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('mobile2')->unique();
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(true);
            $table->string('national_id')->nullable();
            $table->double('net_salary')->nullable()->default(0);
            $table->string('ministry_agency')->nullable();
            $table->integer('agencescode')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // 3 = Client
            $table->unsignedBigInteger('role')->nullable()->default(3);
            $table->foreign('role')->references('id')->on('roles')->onDelete('set null');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
