<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        /*Schema::create('mouvements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dossier_id')->constrained('dossier_archives')->onDelete('cascade');
        $table->enum('type_mouvement', ['DEPOT_INITIAL', 'RETRAIT_TEMP', 'RETOUR', 'TRANSFERT_DEF', 'CONSULTATION', 'RESTITUTION']);
        $table->dateTime('dateMouvement');
        $table->string('motif');
        $table->string('provenance')->nullable();
        $table->string('destination')->nullable();
        $table->foreignId('effectuePar')->constrained('utilisateurs');
        $table->enum('statut', ['EN_COURS', 'TERMINE', 'EN_RETARD', 'ANNULE']);
        $table->timestamps();
        });*/
       Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossier_archives')->onDelete('cascade');
            $table->enum('type_mouvement', [
                         'DEPOT_INITIAL',
                         'RETRAIT_TEMP',
                         'RETOUR',
                         'TRANSFERT_DEF',
                         'CONSULTATION',
                         'RESTITUTION'
                     ]);

            $table->dateTime('dateMouvement');
            $table->string('motif');
            $table->string('provenance')->nullable();
            $table->string('destination')->nullable();
            $table->foreignId('effectue_par')->constrained('utilisateurs');
            $table->boolean('documentRetire')->default(false);
            $table->json('documentsRetires')->nullable();
            $table->dateTime('dateRetourPrevu')->nullable();
            $table->dateTime('dateRetourEffectif')->nullable();

            $table->enum('statut', [
                        'EN_COURS',
                        'TERMINE',
                        'EN_RETARD',
                        'ANNULE'
                     ]);
            $table->timestamps();
       });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};
