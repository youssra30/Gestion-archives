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

// ✅ مسار تسجيل الدخول
Route::post('/login', [UtilisateurController::class, 'login']);

// =========================================================
// 🔒 ROUTES PROTÉGÉES (Nécessitent un token valide)
// =========================================================

Route::middleware('auth:sanctum')->group(function () {

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES')->group(function () {
        
        Route::get('/utilisateurs/export', [UtilisateurController::class, 'export']);
        Route::get('/etudiants/export', [EtudiantController::class, 'export']);
        Route::get('/dossiers/export', [DossierArchiveController::class, 'export']);
        Route::get('/mouvements/export', [MouvementController::class, 'export']);
        Route::get('/reclamations/export', [ReclamationController::class, 'export']);
        Route::get('/transferts/export', [TransfertExterneController::class, 'export']);

        Route::apiResource('utilisateurs', UtilisateurController::class);
        Route::apiResource('bacinfos', BacInfoController::class);
        Route::apiResource('transferts', TransfertExterneController::class);
    });

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL')->group(function () {
        Route::apiResource('etudiants', EtudiantController::class);
    });

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL, CONSULTANT -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT')->group(function () {
        Route::apiResource('dossiers', DossierArchiveController::class);
        Route::apiResource('documents', DocumentController::class);
    });

    // ----------------- ADMIN_SYSTEME, AGENT_ACCUEIL -----------------
    Route::middleware('role:ADMIN_SYSTEME,AGENT_ACCUEIL')->group(function () {
        
        // ⭐⭐⭐ Routes pour la gestion des dossiers (Agent Accueil)
        Route::get('/dossiers/rechercher', [DossierArchiveController::class, 'rechercher']);
        Route::get('/dossiers/consulter/{id}', [DossierArchiveController::class, 'consulter']);
        Route::post('/dossiers/receptionner', [DossierArchiveController::class, 'receptionner']);
        
        // ⭐⭐⭐ Routes pour les mouvements
        Route::get('/mouvements/en-cours', [MouvementController::class, 'enCours']);
        Route::get('/mouvements/en-retard', [MouvementController::class, 'enRetard']);
        Route::get('/mouvements/export', [MouvementController::class, 'export']);
        
        Route::post('/mouvements/retrait-temp', [MouvementController::class, 'retraitTemp']);
        Route::put('/mouvements/{id}/retour', [MouvementController::class, 'retour']);
        
        Route::apiResource('mouvements', MouvementController::class);
    });

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL, CONSULTANT, ETUDIANT -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT')->group(function () {
        Route::apiResource('reclamations', ReclamationController::class);
    });

    Route::post('/etudiants/import', [EtudiantController::class, 'import']);

});