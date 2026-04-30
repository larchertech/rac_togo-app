<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['cena', 'cec', 'cel']);
            $table->string('niveau');
            $table->json('membres');
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};