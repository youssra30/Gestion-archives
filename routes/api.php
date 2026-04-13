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
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ParametreController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [UtilisateurController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes (auth:sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UtilisateurController::class, 'logout']);


    /*
    |--------------------------------------------------------------------------
    | ADMIN SYSTEME ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:SUPER_ADMIN,ADMIN_SYSTEME')->group(function () {

        // 📊 Statistiques
        Route::get('/statistiques', [StatistiqueController::class, 'index']);

        // 💾 Backup / Restore
        Route::post('/backup', [BackupController::class, 'backup']);
        Route::post('/restore', [BackupController::class, 'restore']);

        // ⚙️ Paramètres
        Route::get('/parametres', [ParametreController::class, 'index']);
        Route::put('/parametres', [ParametreController::class, 'update']);
    });


    /*
    |--------------------------------------------------------------------------
    | SUPER_ADMIN + ADMIN_SYSTEME + RESPONSABLE_ARCHIVES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:SUPER_ADMIN,ADMIN_SYSTEME,RESPONSABLE_ARCHIVES')->group(function () {

        // Export
        Route::get('/utilisateurs/export', [UtilisateurController::class, 'export']);
        Route::get('/etudiants/export', [EtudiantController::class, 'export']);
        Route::get('/dossiers/export', [DossierArchiveController::class, 'export']);
        Route::get('/mouvements/export', [MouvementController::class, 'export']);
        Route::get('/reclamations/export', [ReclamationController::class, 'export']);
        Route::get('/transferts/export', [TransfertExterneController::class, 'export']);

        // Resources
        Route::apiResource('utilisateurs', UtilisateurController::class);
        Route::apiResource('bacinfos', BacInfoController::class);
        Route::apiResource('transferts', TransfertExterneController::class);
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN_SYSTEME + RESPONSABLE_ARCHIVES + AGENT_ACCUEIL
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL')->group(function () {

        Route::apiResource('etudiants', EtudiantController::class);
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN_SYSTEME + RESPONSABLE_ARCHIVES + AGENT_ACCUEIL + CONSULTANT
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT')->group(function () {

        Route::apiResource('dossiers', DossierArchiveController::class);
        Route::apiResource('documents', DocumentController::class);
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN_SYSTEME + AGENT_ACCUEIL
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:ADMIN_SYSTEME,AGENT_ACCUEIL')->group(function () {

        Route::apiResource('mouvements', MouvementController::class);
    });


    /*
    |--------------------------------------------------------------------------
    | ALL USERS
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT')->group(function () {

        Route::apiResource('reclamations', ReclamationController::class);
    });

});