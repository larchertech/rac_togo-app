<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections');
            $table->foreignId('alumni_id')->constrained('alumni');
            $table->enum('poste', ['president', 'vice_president', 'sg', 'tresorier', 'conseiller']);
            $table->enum('statut', ['soumis', 'en_examen', 'valide', 'rejete'])->default('soumis');
            $table->json('documents')->nullable();
            $table->text('lettre_motivation')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users');
            $table->timestamp('valide_at')->nullable();
            $table->string('numero_dossier')->unique();
            $table->timestamps();

            $table->unique(['election_id', 'alumni_id', 'poste']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};