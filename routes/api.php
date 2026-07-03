<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EleveController;
use App\Http\Controllers\Api\ClasseController;
use App\Http\Controllers\Api\MatiereController;
use App\Http\Controllers\Api\PaiementController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\AnnonceController;
use App\Http\Controllers\Api\NotificationController;
use App\Models\User;
use Kreait\Firebase\Contract\Messaging;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/change-password',[AuthController::class, 'changePassword']);

    Route::post(
        '/user/fcm-token',
        [NotificationController::class, 'saveToken']
    );

    /*
    |--------------------------------------------------------------------------
    | TABLEAU DE BORD (chaque rôle a sa propre route, déjà bien protégée plus bas)
    |--------------------------------------------------------------------------
    */
    Route::get('/classement/trimestriel/{classe}/{trimestre}/{annee}', [DashboardController::class, 'classementTrimestriel']);
    Route::get('/classement/annuel/{classe}/{annee}', [DashboardController::class, 'classementAnnuel']);

    /*
    |--------------------------------------------------------------------------
    | LECTURE PARTAGÉE — accessible à plusieurs rôles, mais filtrée par
    | rôle À L'INTÉRIEUR du Controller (jamais juste par les query params)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:' . User::ROLE_ADMIN . ',' . User::ROLE_ENSEIGNANT)->group(function () {
        Route::get('/eleves', [EleveController::class, 'index']);
        Route::get('/matieres', [MatiereController::class, 'index']);
    });

    Route::middleware('role:' . User::ROLE_ADMIN . ',' . User::ROLE_ENSEIGNANT . ',' . User::ROLE_PARENT)->group(function () {
        Route::get('/notes', [NoteController::class, 'index']);
        Route::get('/absences', [AbsenceController::class, 'index']);
    });

    Route::middleware('role:' . User::ROLE_ADMIN . ',' . User::ROLE_PARENT)->group(function () {
        Route::get('/paiements', [PaiementController::class, 'index']);
        Route::get('/paiements/summary', [PaiementController::class, 'summary']);
    });

    Route::middleware('role:' . User::ROLE_ADMIN . ',' . User::ROLE_ENSEIGNANT . ',' . User::ROLE_PARENT)->group(function () {
        Route::get('/annonces', [AnnonceController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ou GESTIONNAIRE
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:' . User::ROLE_ADMIN)->group(function () {
        
        Route::get('/dashboard/gestionnaire', [DashboardController::class, 'gestionnaire']);
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::patch('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/classes', [ClasseController::class, 'index']);
        Route::post('/classes', [ClasseController::class, 'store']);
        Route::get('/classes/{id}', [ClasseController::class, 'show']);
        Route::put('/classes/{id}', [ClasseController::class, 'update']);
        Route::patch('/classes/{id}', [ClasseController::class, 'update']);
        Route::delete('/classes/{id}', [ClasseController::class, 'destroy']);

        Route::post('/eleves', [EleveController::class, 'store']);
        Route::get('/eleves/{id}', [EleveController::class, 'show']);
        Route::put('/eleves/{id}', [EleveController::class, 'update']);
        Route::patch('/eleves/{id}', [EleveController::class, 'update']);
        Route::delete('/eleves/{id}', [EleveController::class, 'destroy']);

        Route::post('/matieres', [MatiereController::class, 'store']);
        Route::get('/matieres/{id}', [MatiereController::class, 'show']);
        Route::put('/matieres/{id}', [MatiereController::class, 'update']);
        Route::patch('/matieres/{id}', [MatiereController::class, 'update']);
        Route::delete('/matieres/{id}', [MatiereController::class, 'destroy']);

        Route::post('/paiements', [PaiementController::class, 'store']);
        Route::get('/paiements/{id}', [PaiementController::class, 'show']);
        Route::put('/paiements/{id}', [PaiementController::class, 'update']);
        Route::patch('/paiements/{id}', [PaiementController::class, 'update']);
        Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);

        Route::post('/annonces', [AnnonceController::class, 'store']);
        Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
        Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
        Route::patch('/annonces/{id}', [AnnonceController::class, 'update']);
        Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | ENSEIGNANT
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:' . User::ROLE_ENSEIGNANT)->group(function () {
        Route::get('/dashboard/enseignant', [DashboardController::class, 'enseignant']);

        Route::post('/notes', [NoteController::class, 'store']);
        Route::get('/notes/{id}', [NoteController::class, 'show']);
        Route::put('/notes/{id}', [NoteController::class, 'update']);
        Route::patch('/notes/{id}', [NoteController::class, 'update']);
        Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

        Route::post('/absences', [AbsenceController::class, 'store']);
        Route::get('/absences/{id}', [AbsenceController::class, 'show']);
        Route::put('/absences/{id}', [AbsenceController::class, 'update']);
        Route::patch('/absences/{id}', [AbsenceController::class, 'update']);
        Route::delete('/absences/{id}', [AbsenceController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | PARENT
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:' . User::ROLE_PARENT)->group(function () {
        Route::get('/dashboard/parent', [DashboardController::class, 'parent']);
    });
});