<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('transfert_externes', function (Blueprint $table) {
         $table->id();
         $table->foreignId('dossier_id')->constrained('dossier_archives')->onDelete('cascade');
         $table->string('ecoleOrigine');
         $table->string('ecoleDestination');
         $table->date('dateDemande');
         $table->date('dateValidation')->nullable();
         $table->enum('statut', [
                  'DEMANDE_ENVOI',
                  'DEMANDE_RECU',
                  'VALIDE',
                  'REFUSE',
                  'EN_COURS',
                  'TERMINE'
                ]);

         $table->json('documentsTransmis')->nullable();
         $table->string('referenceCourrier')->nullable();
         $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('transfert_externes');
    }
};
