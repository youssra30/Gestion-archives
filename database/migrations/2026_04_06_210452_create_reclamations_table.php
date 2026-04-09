<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
       Schema::create('reclamations', function (Blueprint $table) {
         $table->id();
         $table->foreignId('dossier_id')->constrained('dossier_archives')->onDelete('cascade');
         $table->string('demandeur');
         $table->enum('typeDemande', [
                    'COPIE_DOCUMENT',
                    'DUPLICATA_DIPLOME',
                    'ATTESTATION_REUSSITE',
                    'CERTIFICAT_SCOLARITE_ANCIENNE',
                    'COPIE_CIN',
                    'COPIE_BAC',
                    'DOSSIER_COMPLET'
            ]);
        $table->date('dateDemande');
        $table->date('dateTraitement')->nullable();
        $table->enum('statut', [
                     'EN_ATTENTE',
                     'EN_COURS',
                     'TRAITEE',
                     'REJETEE',
                     'ANNULEE'
            ]);

        $table->json('documentsDemandes')->nullable();
        $table->string('motif')->nullable();
        $table->foreignId('traite_par')->nullable()->constrained('utilisateurs');
        $table->text('reponse')->nullable();
        $table->timestamps();
     });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
