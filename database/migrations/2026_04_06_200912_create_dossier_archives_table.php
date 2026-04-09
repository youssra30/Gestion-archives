<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('dossier_archives', function (Blueprint $table) {
         $table->id();
         $table->string('numeroDossier')->unique();
         $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
         $table->enum('typeCas', [
                      'ADMISSION', 
                      'AUTRE_VILLE',
                      'ABANDON_CYCLE',
                      'TRANSFERT_SORTANT',
                      'TRANSFERT_ENTRANT',
                      'LAUREAT',
                      'ABANDON_PREPA',
                      'DEMI_PENSION',
                      'PENSION_COMPLETE'
                    ]);
         $table->enum('statut', [ 
                      'EN_COURS', 
                      'COMPLET', 
                      'INCOMPLET',
                      'ARCHIVE', 
                      'TRANSFERE', 
                      'RETIRE',
                      'DETRUIT'
                    ]);
         $table->date('dateArchivage')->nullable();
         $table->string('localisation')->nullable();
         $table->text('observations')->nullable();
         $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_archives');
    }
};
