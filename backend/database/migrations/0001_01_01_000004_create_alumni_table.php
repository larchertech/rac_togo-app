<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cdej_id')->constrained('cdej');
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->enum('niveau_diplome', ['cepe', 'bepc', 'bac', 'bts', 'licence', 'master', 'formation_pro']);
            $table->json('documents')->nullable();
            $table->enum('statut_compte', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            $table->string('numero_membre')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};