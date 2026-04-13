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
        
        // ⭐ روابط الـ Export
        Route::get('/utilisateurs/export', [UtilisateurController::class, 'export']);
        Route::get('/etudiants/export', [EtudiantController::class, 'export']);
        Route::get('/dossiers/export', [DossierArchiveController::class, 'export']);
        Route::get('/mouvements/export', [MouvementController::class, 'export']);
        Route::get('/reclamations/export', [ReclamationController::class, 'export']);
        Route::get('/transferts/export', [TransfertExterneController::class, 'export']);

        // مسارات الـ Resources
        Route::apiResource('utilisateurs', UtilisateurController::class);
        Route::apiResource('bacinfos', BacInfoController::class);
        Route::apiResource('transferts', TransfertExterneController::class);
    });

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL')->group(function () {
        
        // ⭐ الروابط الجديدة للطلاب (Import & Attestation)
        Route::post('/etudiants/import', [EtudiantController::class, 'import']);
        Route::get('/etudiants/{id}/attestation', [EtudiantController::class, 'generateAttestation']);
        
        Route::apiResource('etudiants', EtudiantController::class);
    });

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL, CONSULTANT -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT')->group(function () {
        
        // ⭐ الروابط الجديدة للملفات (Search & Status)
        Route::get('/dossiers/search', [DossierArchiveController::class, 'search']);
        Route::patch('/dossiers/{id}/status', [DossierArchiveController::class, 'toggleStatus']);
        
        Route::apiResource('dossiers', DossierArchiveController::class);
        Route::apiResource('documents', DocumentController::class);
    });

    // ----------------- ADMIN_SYSTEME, AGENT_ACCUEIL -----------------
    Route::middleware('role:ADMIN_SYSTEME,AGENT_ACCUEIL')->group(function () {
        Route::apiResource('mouvements', MouvementController::class);
    });

    // ----------------- ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL, CONSULTANT, ETUDIANT -----------------
    Route::middleware('role:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT')->group(function () {
        Route::apiResource('reclamations', ReclamationController::class);
    });

});