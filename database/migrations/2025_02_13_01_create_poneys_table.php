<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poneys', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->boolean('disponible')->default(true);
            $table->integer('heures_travail_validee')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poneys');
    }
};
