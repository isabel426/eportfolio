<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioExportController;

Route::middleware(['auth'])->group(function () {
    // Exportaciones
    Route::get('/portfolio/{portfolio}/export/json', [PortfolioExportController::class, 'exportJson'])
        ->name('portfolio.export.json');

    Route::get('/portfolio/{portfolio}/export/pdf', [PortfolioExportController::class, 'exportPdf'])
        ->name('portfolio.export.pdf');

    Route::get('/portfolio/{portfolio}/export/pdf/preview', [PortfolioExportController::class, 'previewPdf'])
        ->name('portfolio.export.pdf.preview');
});
