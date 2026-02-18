<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de skills técnicas comunes
        $technicalSkills = [
            'PHP' => ['Laravel', 'Symfony', 'CodeIgniter', 'Backend', 'Web Development'],
            'JavaScript' => ['React', 'Vue.js', 'Node.js', 'Frontend', 'Web Development'],
            'TypeScript' => ['Angular', 'React', 'Type Safety', 'Frontend'],
            'Python' => ['Django', 'Flask', 'Data Science', 'Machine Learning', 'Backend'],
            'Java' => ['Spring Boot', 'Enterprise', 'Backend', 'Android'],
            'C#' => ['.NET', 'ASP.NET', 'Backend', 'Windows'],
            'Ruby' => ['Rails', 'Backend', 'Web Development'],
            'Go' => ['Microservices', 'Backend', 'Performance'],
            'Rust' => ['Systems Programming', 'Performance', 'Safety'],
            'SQL' => ['MySQL', 'PostgreSQL', 'Database', 'Queries'],
            'NoSQL' => ['MongoDB', 'Redis', 'Database', 'Big Data'],
            'HTML' => ['Web', 'Frontend', 'Markup'],
            'CSS' => ['Styling', 'Frontend', 'Design'],
            'React' => ['Frontend', 'SPA', 'JavaScript', 'UI'],
            'Vue.js' => ['Frontend', 'SPA', 'JavaScript', 'Progressive'],
            'Angular' => ['Frontend', 'SPA', 'TypeScript', 'Enterprise'],
            'Laravel' => ['PHP', 'Backend', 'MVC', 'Eloquent'],
            'Django' => ['Python', 'Backend', 'MVC', 'ORM'],
            'Spring Boot' => ['Java', 'Backend', 'Microservices'],
            'Docker' => ['DevOps', 'Containers', 'Deployment'],
            'Kubernetes' => ['DevOps', 'Orchestration', 'Containers'],
            'Git' => ['Version Control', 'Collaboration', 'DevOps'],
            'AWS' => ['Cloud', 'Infrastructure', 'DevOps'],
            'Azure' => ['Cloud', 'Microsoft', 'Infrastructure'],
            'TailwindCSS' => ['CSS', 'Frontend', 'Utility-First'],
            'Bootstrap' => ['CSS', 'Frontend', 'Responsive'],
            'REST API' => ['Backend', 'Web Services', 'HTTP'],
            'GraphQL' => ['API', 'Query Language', 'Backend'],
            'Testing' => ['Quality Assurance', 'TDD', 'BDD'],
            'Agile' => ['Methodology', 'Scrum', 'Project Management'],
        ];

        $skillName = $this->faker->randomElement(array_keys($technicalSkills));

        return [
            'name' => $skillName,
            'keywords' => $technicalSkills[$skillName],
        ];
    }

    /**
     * Crear una skill específica
     *
     * @param string $name
     * @param array $keywords
     * @return Factory
     */
    public function withName(string $name, array $keywords = []): Factory
    {
        return $this->state(function (array $attributes) use ($name, $keywords) {
            return [
                'name' => $name,
                'keywords' => $keywords,
            ];
        });
    }
}
