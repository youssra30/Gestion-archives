<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('bac_infos', function (Blueprint $table) {
         $table->id();
         $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
         $table->string('serie');
         $table->enum('mention', ['TRES_BIEN', 'BIEN', 'ASSEZ_BIEN', 'PASSABLE']);
         $table->integer('anneeObtention');
         $table->string('lycee');
         $table->string('academie');
         $table->string('copieScaneeUrl')->nullable();
         $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bac_infos');
    }
};
