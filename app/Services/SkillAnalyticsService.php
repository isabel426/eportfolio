<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SkillAnalyticsService
{
    /**
     * Obtener las habilidades más demandadas
     *
     * @param int $limit
     * @return array
     */
    public function getMostDemandedSkills(int $limit = 10): array
    {
        $skills = DB::table('skill_user')
            ->join('skills', 'skills.id', '=', 'skill_user.skill_id')
            ->select('skills.name', DB::raw('COUNT(*) as count'))
            ->groupBy('skills.name', 'skills.id')
            ->orderBy('count', 'DESC')
            ->limit($limit)
            ->get();

        return [
            'labels' => $skills->pluck('name')->toArray(),
            'values' => $skills->pluck('count')->toArray(),
        ];
    }

    /**
     * Obtener distribución de niveles de competencia
     *
     * @param string $skillName
     * @return array
     */
    public function getSkillLevelDistribution(string $skillName): array
    {
        $distribution = DB::table('skill_user')
            ->join('skills', 'skills.id', '=', 'skill_user.skill_id')
            ->where('skills.name', $skillName)
            ->select('skill_user.level', DB::raw('COUNT(*) as count'))
            ->groupBy('skill_user.level')
            ->get();

        return [
            'labels' => $distribution->pluck('level')->toArray(),
            'values' => $distribution->pluck('count')->toArray(),
        ];
    }

    /**
     * Obtener tendencias de habilidades por mes
     *
     * @param int $months
     * @return array
     */
    public function getSkillTrends(int $months = 6): array
    {
        $trends = DB::table('skill_user')
            ->join('skills', 'skills.id', '=', 'skill_user.skill_id')
            ->select(
                DB::raw('DATE_FORMAT(skill_user.created_at, "%Y-%m") as month'),
                'skills.name',
                DB::raw('COUNT(*) as count')
            )
            ->where('skill_user.created_at', '>=', now()->subMonths($months))
            ->groupBy('month', 'skills.name', 'skills.id')
            ->orderBy('month')
            ->get();

        // Agrupar por habilidad
        $groupedBySkill = $trends->groupBy('name');

        $result = [];
        foreach ($groupedBySkill as $skill => $data) {
            $result[$skill] = [
                'labels' => $data->pluck('month')->toArray(),
                'values' => $data->pluck('count')->toArray(),
            ];
        }

        return $result;
    }

    /**
     * Obtener estadísticas generales
     *
     * @return array
     */
    public function getGeneralStats(): array
    {
        $totalSkillAssignments = DB::table('skill_user')->count();
        $uniqueSkills = DB::table('skills')->count();
        $totalUsers = DB::table('users')->count();
        $avgSkillsPerUser = $totalUsers > 0 ? round($totalSkillAssignments / $totalUsers, 2) : 0;

        return [
            'total_skills' => $totalSkillAssignments,
            'unique_skills' => $uniqueSkills,
            'avg_per_portfolio' => $avgSkillsPerUser,
        ];
    }

    /**
     * Obtener habilidades relacionadas
     *
     * @param string $skillName
     * @param int $limit
     * @return array
     */
    public function getRelatedSkills(string $skillName, int $limit = 5): array
    {
        // Encuentra el ID de la skill especificada
        $skill = DB::table('skills')->where('name', $skillName)->first();

        if (!$skill) {
            return [
                'skill' => $skillName,
                'related' => [],
                'counts' => [],
            ];
        }

        // Encuentra usuarios que tienen la habilidad especificada
        $usersWithSkill = DB::table('skill_user')
            ->where('skill_id', $skill->id)
            ->pluck('user_id');

        // Encuentra otras habilidades en esos usuarios
        $relatedSkills = DB::table('skill_user')
            ->join('skills', 'skills.id', '=', 'skill_user.skill_id')
            ->whereIn('skill_user.user_id', $usersWithSkill)
            ->where('skill_user.skill_id', '!=', $skill->id)
            ->select('skills.name', DB::raw('COUNT(*) as count'))
            ->groupBy('skills.name', 'skills.id')
            ->orderBy('count', 'DESC')
            ->limit($limit)
            ->get();

        return [
            'skill' => $skillName,
            'related' => $relatedSkills->pluck('name')->toArray(),
            'counts' => $relatedSkills->pluck('count')->toArray(),
        ];
    }
}
