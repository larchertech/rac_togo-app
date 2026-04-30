<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destinataire_id')->constrained('users');
            $table->enum('canal', ['whatsapp', 'email', 'sms']);
            $table->string('type');
            $table->text('message');
            $table->enum('statut', ['en_attente', 'envoye', 'echec'])->default('en_attente');
            $table->integer('tentatives')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};