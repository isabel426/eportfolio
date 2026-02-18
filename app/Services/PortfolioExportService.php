<?php

namespace App\Services;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PortfolioExportService
{
    /**
     * Exportar portfolio a formato JSON Resume
     *
     * @param User $portfolio
     * @return array
     */
    public function exportToJsonResume(User $portfolio): array
    {
        $user = $portfolio;

        return [
            '$schema' => 'https://raw.githubusercontent.com/jsonresume/resume-schema/v1.0.0/schema.json',
            'basics' => [
                'name' => $user->name,
                'label' => $portfolio->title ?? 'Professional',
                'email' => $user->email,
                'phone' => $portfolio->phone ?? '',
                'summary' => $portfolio->summary ?? '',
                'location' => [
                    'city' => $portfolio->city ?? '',
                    'countryCode' => $portfolio->country ?? '',
                ],
                'profiles' => $this->formatProfiles($portfolio),
            ],
            'work' => $this->formatWorkExperience($portfolio),
            'education' => $this->formatEducation($portfolio),
            'skills' => $this->formatSkills($portfolio),
            'projects' => $this->formatProjects($portfolio),
        ];
    }

    /**
     * Generar PDF del portfolio
     *
     * @param User $portfolio
     * @return \Barryvdh\DomPDF\PDF
     */
    public function exportToPdf(User $portfolio)
    {
        $data = [
            'portfolio' => $portfolio,
            'user' => $portfolio,
            'work_experience' => $portfolio->workExperience ?? [],
            'education' => $portfolio->education ?? [],
            'skills' => $portfolio->skills ?? [],
            'projects' => $portfolio->projects ?? [],
        ];

        return Pdf::loadView('exports.portfolio-pdf', $data)
            ->setPaper('a4', 'portrait');
    }

    /**
     * Formatear perfiles sociales
     */
    protected function formatProfiles(User $portfolio): array
    {
        $profiles = [];

        if ($portfolio->github_url) {
            $profiles[] = [
                'network' => 'GitHub',
                'username' => $this->extractUsername($portfolio->github_url),
                'url' => $portfolio->github_url,
            ];
        }

        if ($portfolio->linkedin_url) {
            $profiles[] = [
                'network' => 'LinkedIn',
                'url' => $portfolio->linkedin_url,
            ];
        }

        return $profiles;
    }

    /**
     * Formatear experiencia laboral
     */
    protected function formatWorkExperience(User $portfolio): array
    {
        $work = [];

        if (!$portfolio->workExperience) {
            return $work;
        }

        foreach ($portfolio->workExperience as $experience) {
            $work[] = [
                'company' => $experience->company,
                'position' => $experience->position,
                'startDate' => $experience->start_date,
                'endDate' => $experience->end_date ?? 'Present',
                'summary' => $experience->description,
            ];
        }

        return $work;
    }

    /**
     * Formatear educación
     */
    protected function formatEducation(User $portfolio): array
    {
        $education = [];

        if (!$portfolio->education) {
            return $education;
        }

        foreach ($portfolio->education as $edu) {
            $education[] = [
                'institution' => $edu->institution,
                'area' => $edu->area,
                'studyType' => $edu->study_type,
                'startDate' => $edu->start_date,
                'endDate' => $edu->end_date,
            ];
        }

        return $education;
    }

    /**
     * Formatear habilidades
     */
    protected function formatSkills(User $portfolio): array
    {
        $skills = [];

        if (!$portfolio->skills) {
            return $skills;
        }

        foreach ($portfolio->skills as $skill) {
            $skills[] = [
                'name' => $skill->name,
                'level' => $skill->level,
                'keywords' => $skill->keywords ?? [],
            ];
        }

        return $skills;
    }

    /**
     * Formatear proyectos
     */
    protected function formatProjects(User $portfolio): array
    {
        $projects = [];

        if (!$portfolio->projects) {
            return $projects;
        }

        foreach ($portfolio->projects as $project) {
            $projects[] = [
                'name' => $project->title,
                'description' => $project->description,
                'url' => $project->url,
                'keywords' => $project->technologies ?? [],
            ];
        }

        return $projects;
    }

    /**
     * Extraer nombre de usuario de URL
     */
    protected function extractUsername(string $url): string
    {
        $parts = explode('/', rtrim($url, '/'));
        return end($parts);
    }
}
