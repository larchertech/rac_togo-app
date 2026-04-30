<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone_whatsapp')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->enum('role', ['alumni', 'bla', 'bca', 'ben', 'cena', 'cec', 'cel', 'admin'])->default('alumni');
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->integer('otp_tentatives')->default(0);
            $table->enum('canal_prefere', ['whatsapp', 'email', 'sms'])->default('whatsapp');
            $table->timestamp('derniere_connexion')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
