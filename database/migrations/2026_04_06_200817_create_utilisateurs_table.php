<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
         $table->id();
         $table->string('nom');
         $table->string('prenom');
         $table->string('username')->unique();
         $table->string('email')->unique();
         $table->string('password');
         $table->string('telephone')->nullable();
         $table->enum('role', ['ADMIN_SYSTEME', 'RESPONSABLE_ARCHIVES', 'AGENT_ACCUEIL', 'CONSULTANT', 'ETUDIANT'])->default('AGENT_ACCUEIL');
         $table->timestamp('derniereConnexion')->nullable();
         $table->timestamps(); // Hada fih dateCreation (created_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
