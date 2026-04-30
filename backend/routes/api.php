<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {

    // Auth - public avec rate limit
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
    });

    // Auth - authentifié
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });

    // Alumni
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/alumni', [AlumniController::class, 'index']);
        Route::post('/alumni', [AlumniController::class, 'store']);
        Route::get('/alumni/moi', [AlumniController::class, 'profilConnecte']);
        Route::get('/alumni/{alumni}', [AlumniController::class, 'show']);
        Route::put('/alumni/{alumni}', [AlumniController::class, 'update']);
        Route::post('/alumni/{alumni}/documents', [AlumniController::class, 'uploadDocument']);
        Route::put('/alumni/{alumni}/statut', [AlumniController::class, 'changerStatut']);
        Route::get('/alumni/{alumni}/carte', [AlumniController::class, 'genererCarte']);
    });

    // Clusters & CDEJ
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/clusters', [\App\Http\Controllers\ClusterController::class, 'index']);
        Route::get('/clusters/{cluster}/cdej', [\App\Http\Controllers\ClusterController::class, 'cdejs']);
        Route::get('/cdej', [\App\Http\Controllers\CdejController::class, 'index']);
        Route::get('/cdej/{cdej}/alumni', [\App\Http\Controllers\CdejController::class, 'alumni']);
    });

    // Élections
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/elections', [ElectionController::class, 'index']);
        Route::post('/elections', [ElectionController::class, 'store']);
        Route::get('/elections/{election}', [ElectionController::class, 'show']);
        Route::put('/elections/{election}/statut', [ElectionController::class, 'changerStatut']);
        Route::get('/elections/{election}/candidatures', [ElectionController::class, 'candidatures']);
        Route::post('/elections/{election}/candidatures', [ElectionController::class, 'deposerCandidature']);
        Route::get('/elections/{election}/electeurs', [VoteController::class, 'listeElectorale']);
        Route::post('/elections/{election}/vote', [VoteController::class, 'voter']);
        Route::get('/elections/{election}/participation', [ElectionController::class, 'participation']);
        Route::post('/elections/{election}/proclamer', [VoteController::class, 'proclamer']);
        Route::get('/elections/{election}/resultats', [VoteController::class, 'resultats']);
        Route::get('/elections/{election}/pv', [ElectionController::class, 'pv']);
    });

    // Candidatures
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/candidatures/{candidature}', [CandidatureController::class, 'show']);
        Route::put('/candidatures/{candidature}/valider', [CandidatureController::class, 'valider']);
        Route::put('/candidatures/{candidature}/rejeter', [CandidatureController::class, 'rejeter']);
        Route::post('/candidatures/{candidature}/recours', [CandidatureController::class, 'recours']);
    });

    // Cotisations
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cotisations/statut', [CotisationController::class, 'statut']);
        Route::post('/cotisations/initier', [CotisationController::class, 'initier']);
        Route::get('/cotisations/rapport', [CotisationController::class, 'rapport']);
        Route::post('/cotisations/relances', [CotisationController::class, 'relances']);
    });

    // Webhooks - publics
    Route::post('/cotisations/webhook/flooz', [CotisationController::class, 'webhookFlooz']);
    Route::post('/cotisations/webhook/tmoney', [CotisationController::class, 'webhookTmoney']);

    // Dashboard
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/clusters', [DashboardController::class, 'clusters']);
        Route::get('/dashboard/activite', [DashboardController::class, 'activite']);
        Route::get('/dashboard/electoral', [DashboardController::class, 'electoral']);
        Route::get('/dashboard/alertes', [DashboardController::class, 'alertes']);
    });

    // Commissions
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/commissions', [CommissionController::class, 'index']);
        Route::post('/commissions', [CommissionController::class, 'store']);
        Route::get('/commissions/{commission}', [CommissionController::class, 'show']);
        Route::put('/commissions/{commission}', [CommissionController::class, 'update']);
        Route::get('/commission/dashboard', [CommissionController::class, 'dashboard']);
    });

    // Notifications
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/non-lues', [NotificationController::class, 'nonLues']);
        Route::put('/notifications/{notification}/lue', [NotificationController::class, 'marquerLue']);
    });
});
