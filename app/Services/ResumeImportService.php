<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ResumeImportService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0,
            'verify' => true,
        ]);
    }

    /**
     * Importar datos desde una URL de JSON Resume
     *
     * @param string $url URL del JSON Resume
     * @return array|null
     */
    public function importFromJsonResume(string $url): ?array
    {
        try {
            $response = $this->client->get($url);

            if ($response->getStatusCode() !== 200) {
                Log::error('Error al obtener JSON Resume', [
                    'status' => $response->getStatusCode(),
                    'url' => $url
                ]);
                return null;
            }

            $data = json_decode($response->getBody()->getContents(), true);

            // Validar que tiene la estructura básica de JSON Resume
            if (!isset($data['basics'])) {
                Log::error('JSON no tiene formato JSON Resume válido');
                return null;
            }

            return $this->transformJsonResumeData($data);

        } catch (GuzzleException $e) {
            Log::error('Excepción al importar JSON Resume', [
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            return null;
        }
    }

    /**
     * Transforma datos de JSON Resume al formato del ePortfolio
     *
     * @param array $jsonResumeData
     * @return array
     */
    protected function transformJsonResumeData(array $jsonResumeData): array
    {
        $transformed = [
            'personal_info' => [
                'name' => $jsonResumeData['basics']['name'] ?? '',
                'email' => $jsonResumeData['basics']['email'] ?? '',
                'phone' => $jsonResumeData['basics']['phone'] ?? '',
                'summary' => $jsonResumeData['basics']['summary'] ?? '',
                'location' => $jsonResumeData['basics']['location']['city'] ?? '',
            ],
            'work_experience' => [],
            'education' => [],
            'skills' => [],
        ];

        // Transformar experiencia laboral
        if (isset($jsonResumeData['work'])) {
            foreach ($jsonResumeData['work'] as $work) {
                $transformed['work_experience'][] = [
                    'company' => $work['company'] ?? '',
                    'position' => $work['position'] ?? '',
                    'start_date' => $work['startDate'] ?? null,
                    'end_date' => $work['endDate'] ?? null,
                    'description' => $work['summary'] ?? '',
                ];
            }
        }

        // Transformar educación
        if (isset($jsonResumeData['education'])) {
            foreach ($jsonResumeData['education'] as $edu) {
                $transformed['education'][] = [
                    'institution' => $edu['institution'] ?? '',
                    'area' => $edu['area'] ?? '',
                    'study_type' => $edu['studyType'] ?? '',
                    'start_date' => $edu['startDate'] ?? null,
                    'end_date' => $edu['endDate'] ?? null,
                ];
            }
        }

        // Transformar habilidades
        if (isset($jsonResumeData['skills'])) {
            foreach ($jsonResumeData['skills'] as $skill) {
                $transformed['skills'][] = [
                    'name' => $skill['name'] ?? '',
                    'level' => $skill['level'] ?? 'Intermediate',
                    'keywords' => $skill['keywords'] ?? [],
                ];
            }
        }

        return $transformed;
    }

    /**
     * Importar datos desde el perfil público de GitHub
     *
     * @param string $username
     * @return array|null
     */
    public function importFromGitHub(string $username): ?array
    {
        try {
            // Obtener datos del perfil
            $profileResponse = $this->client->get(
                "https://api.github.com/users/{$username}",
                [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'User-Agent' => 'ePortfolio-Laravel-App'
                    ]
                ]
            );

            $profileData = json_decode($profileResponse->getBody()->getContents(), true);

            // Obtener repositorios
            $reposResponse = $this->client->get(
                "https://api.github.com/users/{$username}/repos?sort=updated&per_page=10",
                [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'User-Agent' => 'ePortfolio-Laravel-App'
                    ]
                ]
            );

            $repos = json_decode($reposResponse->getBody()->getContents(), true);

            return $this->transformGitHubData($profileData, $repos);

        } catch (GuzzleException $e) {
            Log::error('Error al importar desde GitHub', [
                'error' => $e->getMessage(),
                'username' => $username
            ]);
            return null;
        }
    }

    /**
     * Transforma datos de GitHub al formato del ePortfolio
     */
    protected function transformGitHubData(array $profile, array $repos): array
    {
        $projects = [];

        foreach ($repos as $repo) {
            $projects[] = [
                'title' => $repo['name'],
                'description' => $repo['description'] ?? 'Sin descripción',
                'url' => $repo['html_url'],
                'technologies' => [$repo['language'] ?? 'N/A'],
                'stars' => $repo['stargazers_count'],
                'updated_at' => $repo['updated_at'],
            ];
        }

        return [
            'profile' => [
                'name' => $profile['name'] ?? $profile['login'],
                'bio' => $profile['bio'] ?? '',
                'location' => $profile['location'] ?? '',
                'avatar_url' => $profile['avatar_url'] ?? '',
                'github_url' => $profile['html_url'],
                'public_repos' => $profile['public_repos'],
            ],
            'projects' => $projects,
        ];
    }
}
