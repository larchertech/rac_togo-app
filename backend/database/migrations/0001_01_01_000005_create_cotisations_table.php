<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni');
            $table->integer('annee');
            $table->decimal('montant', 10, 2);
            $table->enum('statut', ['en_attente', 'paye', 'en_retard'])->default('en_attente');
            $table->string('canal_paiement')->nullable();
            $table->string('recu_numero')->unique()->nullable();
            $table->string('reference_externe')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->boolean('exemption')->default(false);
            $table->timestamps();

            $table->unique(['alumni_id', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};