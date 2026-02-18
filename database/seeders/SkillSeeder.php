<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar datos existentes (opcional, comentar si no quieres borrar)
        DB::table('skill_user')->delete();
        DB::table('skills')->delete();

        // 1. Crear catálogo de skills base
        $skillsData = [
            // Backend
            ['name' => 'PHP', 'keywords' => ['Laravel', 'Symfony', 'Backend', 'Web Development']],
            ['name' => 'Laravel', 'keywords' => ['PHP', 'Framework', 'MVC', 'Eloquent', 'Artisan']],
            ['name' => 'Node.js', 'keywords' => ['JavaScript', 'Backend', 'Express', 'Async']],
            ['name' => 'Python', 'keywords' => ['Django', 'Flask', 'Backend', 'Data Science']],
            ['name' => 'Java', 'keywords' => ['Spring Boot', 'Enterprise', 'JVM', 'Backend']],
            ['name' => 'C#', 'keywords' => ['.NET', 'ASP.NET', 'Backend', 'Microsoft']],

            // Frontend
            ['name' => 'JavaScript', 'keywords' => ['Frontend', 'Web', 'ES6', 'Async']],
            ['name' => 'TypeScript', 'keywords' => ['JavaScript', 'Type Safety', 'Frontend']],
            ['name' => 'React', 'keywords' => ['Frontend', 'SPA', 'Components', 'Hooks']],
            ['name' => 'Vue.js', 'keywords' => ['Frontend', 'SPA', 'Progressive', 'Reactive']],
            ['name' => 'Angular', 'keywords' => ['Frontend', 'SPA', 'TypeScript', 'Enterprise']],
            ['name' => 'HTML', 'keywords' => ['Web', 'Markup', 'Frontend', 'Semantic']],
            ['name' => 'CSS', 'keywords' => ['Styling', 'Frontend', 'Responsive', 'Flexbox']],
            ['name' => 'TailwindCSS', 'keywords' => ['CSS', 'Utility-First', 'Frontend']],
            ['name' => 'Bootstrap', 'keywords' => ['CSS', 'Framework', 'Responsive']],

            // Database
            ['name' => 'MySQL', 'keywords' => ['SQL', 'Database', 'Relational', 'RDBMS']],
            ['name' => 'PostgreSQL', 'keywords' => ['SQL', 'Database', 'ACID', 'Advanced']],
            ['name' => 'MongoDB', 'keywords' => ['NoSQL', 'Database', 'Document', 'JSON']],
            ['name' => 'Redis', 'keywords' => ['NoSQL', 'Cache', 'In-Memory', 'Performance']],

            // DevOps & Tools
            ['name' => 'Docker', 'keywords' => ['Containers', 'DevOps', 'Deployment']],
            ['name' => 'Kubernetes', 'keywords' => ['Orchestration', 'DevOps', 'Containers']],
            ['name' => 'Git', 'keywords' => ['Version Control', 'GitHub', 'GitLab', 'Collaboration']],
            ['name' => 'CI/CD', 'keywords' => ['DevOps', 'Automation', 'Pipeline', 'Jenkins']],
            ['name' => 'AWS', 'keywords' => ['Cloud', 'Infrastructure', 'EC2', 'S3']],
            ['name' => 'Azure', 'keywords' => ['Cloud', 'Microsoft', 'Infrastructure']],

            // APIs & Architecture
            ['name' => 'REST API', 'keywords' => ['Web Services', 'HTTP', 'JSON', 'Backend']],
            ['name' => 'GraphQL', 'keywords' => ['API', 'Query Language', 'Flexible']],
            ['name' => 'Microservices', 'keywords' => ['Architecture', 'Distributed', 'Scalability']],

            // Testing & Methodologies
            ['name' => 'Testing', 'keywords' => ['QA', 'PHPUnit', 'Jest', 'TDD']],
            ['name' => 'Agile', 'keywords' => ['Scrum', 'Kanban', 'Methodology']],
        ];

        foreach ($skillsData as $skillData) {
            Skill::create($skillData);
        }

        $this->command->info('✓ Catálogo de ' . count($skillsData) . ' skills creado correctamente');

        // 2. Crear usuarios de prueba (si no existen)
        $existingUsersCount = User::count();

        if ($existingUsersCount < 25) {
            $usersToCreate = 25 - $existingUsersCount;
            User::factory($usersToCreate)->create();
            $this->command->info("✓ {$usersToCreate} usuarios de prueba creados");
        }

        // 3. Asignar skills a usuarios con distribución realista
        $users = User::all();
        $skills = Skill::all();
        $levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];

        // Distribución de niveles (más realista)
        $levelDistribution = [
            'Beginner' => 30,      // 30%
            'Intermediate' => 40,  // 40%
            'Advanced' => 20,      // 20%
            'Expert' => 10,        // 10%
        ];

        // Crear un array ponderado de niveles
        $weightedLevels = [];
        foreach ($levelDistribution as $level => $percentage) {
            $weightedLevels = array_merge(
                $weightedLevels,
                array_fill(0, $percentage, $level)
            );
        }

        // Distribuir las skills en los últimos 6 meses para ver tendencias
        $sixMonthsAgo = now()->subMonths(6);

        $totalAssignments = 0;

        foreach ($users as $user) {
            // Cada usuario tendrá entre 5 y 12 skills
            $numSkills = rand(5, 12);

            // Seleccionar skills aleatorias sin repetir
            $selectedSkills = $skills->random($numSkills);

            foreach ($selectedSkills as $skill) {
                // Seleccionar nivel con distribución ponderada
                $level = $weightedLevels[array_rand($weightedLevels)];

                // Fecha aleatoria en los últimos 6 meses
                $randomDate = $sixMonthsAgo->copy()->addDays(rand(0, 180));

                // Asignar skill al usuario con nivel y fecha
                $user->skills()->attach($skill->id, [
                    'level' => $level,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);

                $totalAssignments++;
            }
        }

        $this->command->info("✓ {$totalAssignments} relaciones user-skill creadas");

        // 4. Estadísticas finales
        $this->command->info('');
        $this->command->info('📊 Estadísticas de la base de datos:');
        $this->command->info('   - Total de usuarios: ' . User::count());
        $this->command->info('   - Total de skills únicas: ' . Skill::count());
        $this->command->info('   - Total de asignaciones: ' . $totalAssignments);
        $this->command->info('   - Promedio de skills por usuario: ' . round($totalAssignments / User::count(), 2));

        // Mostrar top 5 skills más populares
        $topSkills = DB::table('skill_user')
            ->join('skills', 'skills.id', '=', 'skill_user.skill_id')
            ->select('skills.name', DB::raw('COUNT(*) as count'))
            ->groupBy('skills.name', 'skills.id')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get();

        $this->command->info('');
        $this->command->info('🏆 Top 5 Skills Más Demandadas:');
        foreach ($topSkills as $index => $skill) {
            $this->command->info('   ' . ($index + 1) . '. ' . $skill->name . ' (' . $skill->count . ' usuarios)');
        }
    }
}
