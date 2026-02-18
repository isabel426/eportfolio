<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PortfolioExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class PortfolioExportController extends Controller
{
    protected PortfolioExportService $exportService;

    public function __construct(PortfolioExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Exportar a JSON Resume
     */
    public function exportJson(User $portfolio)
    {
        $jsonResume = $this->exportService->exportToJsonResume($portfolio);

        $filename = 'portfolio-' . $portfolio->name . '-' . date('Y-m-d') . '.json';

        return Response::json($jsonResume, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(User $portfolio)
    {
        $pdf = $this->exportService->exportToPdf($portfolio);

        $filename = 'portfolio-' . $portfolio->name . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Vista previa del PDF
     */
    public function previewPdf(User $portfolio)
    {
        $pdf = $this->exportService->exportToPdf($portfolio);

        return $pdf->stream();
    }
}
