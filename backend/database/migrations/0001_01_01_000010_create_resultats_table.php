<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections');
            $table->foreignId('candidature_id')->constrained('candidatures');
            $table->integer('nb_voix')->default(0);
            $table->timestamps();

            $table->unique(['election_id', 'candidature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }
};