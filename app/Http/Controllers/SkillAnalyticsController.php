<?php

namespace App\Http\Controllers;

use App\Services\SkillAnalyticsService;
use App\Charts\MostDemandedSkills;
use App\Charts\SkillLevelDistribution;
use Illuminate\Http\Request;

class SkillAnalyticsController extends Controller
{
    protected SkillAnalyticsService $analyticsService;

    public function __construct(SkillAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Dashboard de análisis de habilidades
     */
    public function index()
    {
        // Obtener datos
        $mostDemanded = $this->analyticsService->getMostDemandedSkills(10);
        $stats = $this->analyticsService->getGeneralStats();

        // Crear gráfico de barras para habilidades más demandadas
        $chartMostDemanded = new MostDemandedSkills();
        $chartMostDemanded->title('Top 10 Habilidades Más Demandadas');
        $chartMostDemanded->labels($mostDemanded['labels']);
        $chartMostDemanded->dataset('Skills demandadas', 'bar', $mostDemanded['values']);
        $chartMostDemanded->height(300);

        // Ejemplo de habilidad específica para análisis
        $phpDistribution = $this->analyticsService->getSkillLevelDistribution('PHP');

        $chartPhpLevels = new SkillLevelDistribution();
        $chartPhpLevels->title('Distribución de Niveles - PHP');
        $chartPhpLevels->labels($phpDistribution['labels']);
        $chartPhpLevels->dataset('Distribución de Niveles', 'pie', $phpDistribution['values']);
        $chartPhpLevels->height(300);

        return view('analytics.skills', compact(
            'chartMostDemanded',
            'chartPhpLevels',
            'stats'
        ));
    }

    /**
     * API: Obtener datos de tendencias
     */
    public function trends()
    {
        $trends = $this->analyticsService->getSkillTrends(6);

        return response()->json($trends);
    }

    /**
     * API: Obtener habilidades relacionadas
     */
    public function related(Request $request)
    {
        $skill = $request->input('skill');

        if (!$skill) {
            return response()->json(['error' => 'Skill parameter required'], 400);
        }

        $related = $this->analyticsService->getRelatedSkills($skill);

        return response()->json($related);
    }
}
