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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname'); // Correction de 'fistname' en 'firstname'
            $table->string('lastname');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(true);
            $table->double('net_salary')->nullable()->default(0);
            // 1 = Admin, 2 = Vendor, 3 = Client
            $table->integer('role')->default(2);
            $table->integer('agencescode')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
