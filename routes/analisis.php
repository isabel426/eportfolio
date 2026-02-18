<?php

use App\Http\Controllers\SkillAnalyticsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Dashboard de analytics
    Route::get('/analytics/skills', [SkillAnalyticsController::class, 'index'])
        ->name('analytics.skills');

    // API endpoints
    Route::get('/api/analytics/trends', [SkillAnalyticsController::class, 'trends'])
        ->name('api.analytics.trends');

    Route::get('/api/analytics/related', [SkillAnalyticsController::class, 'related'])
        ->name('api.analytics.related');
});
