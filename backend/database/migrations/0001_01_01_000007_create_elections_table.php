<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bla', 'bca', 'be']);
            $table->string('niveau');
            $table->enum('statut', ['brouillon', 'preparation', 'candidatures', 'campagne', 'vote', 'depouillement', 'proclame', 'archive'])->default('brouillon');
            $table->foreignId('commission_id')->nullable()->constrained('commissions');
            $table->timestamp('ouverture_candidatures')->nullable();
            $table->timestamp('cloture_candidatures')->nullable();
            $table->timestamp('date_vote')->nullable();
            $table->time('heure_ouverture_vote')->nullable();
            $table->time('heure_cloture_vote')->nullable();
            $table->enum('mode_scrutin', ['uninominal', 'plurinominal', 'majoritaire_simple', 'majoritaire_absolu']);
            $table->json('postes');
            $table->json('config')->nullable();
            $table->timestamp('proclame_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};