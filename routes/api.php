<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\BacInfoController;
use App\Http\Controllers\DossierArchiveController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\TransfertExterneController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\ParametreController;

Route::post('/login', [UtilisateurController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 tentatives par minute max

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UtilisateurController::class, 'logout']);

    Route::middleware('role:SUPER_ADMIN,ADMIN_SYSTEME')->group(function () {

        Route::get('/statistiques', [StatistiqueController::class, 'index']);
        Route::get('/parametres', [ParametreController::class, 'index']);
        Route::put('/parametres', [ParametreController::class, 'update']);
    });


    Route::middleware('role:SUPER_ADMIN,ADMIN_SYSTEME,RESPONSABLE_ARCHIVES')->group(function () {

        Route::get('/utilisateurs/export', [UtilisateurController::class, 'export']);
        Route::post('/utilisateurs/import', [UtilisateurController::class, 'import']);
        Route::get('/etudiants/export', [EtudiantController::class, 'export']);
        Route::post('/etudiants/import', [EtudiantController::class, 'import']);
        Route::get('/dossiers/export', [DossierArchiveController::class, 'export']);
        Route::get('/mouvements/export', [MouvementController::class, 'export']);
        Route::get('/reclamations/export', [ReclamationController::class, 'export']);
        Route::get('/transferts/export', [TransfertExterneController::class, 'export']);
        Route::apiResource('utilisateurs', UtilisateurController::class);
        Route::apiResource('bacinfos', BacInfoController::class);
        Route::apiResource('transferts', TransfertExterneController::class);
    });

    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL')->group(function () {

        Route::delete('/etudiants/delete-all', [EtudiantController::class, 'destroyAll']);
        Route::apiResource('etudiants', EtudiantController::class);
    });


    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT')->group(function () {

        Route::apiResource('dossiers', DossierArchiveController::class);
        Route::apiResource('documents', DocumentController::class);
    });
  
    Route::middleware('role:ADMIN_SYSTEME,AGENT_ACCUEIL')->group(function () {

        Route::apiResource('mouvements', MouvementController::class);
    });

    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT')->group(function () {

        Route::apiResource('reclamations', ReclamationController::class);
    });

});