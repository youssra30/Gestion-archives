<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('dossier_id')->constrained('dossier_archives')->onDelete('cascade');
    $table->enum('type_document', ['CIN_RECTO', 'CIN_VERSO', 'BAC_ORIGINAL', 'BAC_COPIE', 'DIPLOME_ORIGINAL', 'RELEVE_NOTES', 'ATTESTATION_SCOLARITE', 'CERTIFICAT_RESIDENCE', 'PHOTO_IDENTITE', 'FICHE_INSCRIPTION', 'AUTRE']);
    $table->string('nomFichier');
    $table->string('cheminStockage');
    /*$table->dateTime('dateAjout');*/
    $table->foreignId('ajoute_par')->constrained('utilisateurs');
    $table->bigInteger('taille');
    $table->string('format');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
