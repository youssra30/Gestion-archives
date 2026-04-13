<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->string('cne')->unique();
            $table->string('cin')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->date('dateNaissance');
            $table->string('lieuNaissance');
            $table->string('nationalite');
            $table->enum('sexe', ['MASCULIN', 'FEMININ']);
            $table->string('adresse');
            $table->string('telephone');
            $table->string('email')->unique();
            $table->string('nomPere')->nullable();
            $table->string('nomMere')->nullable();
            $table->string('adresseParents')->nullable();
            $table->string('filiere');
            $table->integer('anneeInscription');
            $table->string('etablissementOrigine')->nullable();
            $table->string('etablissementAccueil')->nullable();
            $table->string('photoUrl')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};