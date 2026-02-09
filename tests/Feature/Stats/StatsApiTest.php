<?php

namespace Tests\Feature\Stats;

use App\Models\User;
use App\Models\Evidencia;
use App\Models\ModuloFormativo;
use App\Models\Matricula;
use App\Models\CriterioEvaluacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class StatsApiTest extends FeatureTestCase
{

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_can_get_general_stats()
    {
        // Arrange
        User::factory()->count(10)->create();
        ModuloFormativo::factory()->count(5)->create();
        Evidencia::factory()->count(20)->create();
        Matricula::factory()->count(15)->create();

        // Act
        $response = $this->getJson('/api/v1/stats');

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'usuarios' => ['total'],
                     'modulos' => ['total', 'activos'],
                     'matriculas' => ['total', 'activas'],
                     'evidencias' => ['total', 'validadas', 'pendientes']
                 ]);
    }

    public function test_can_get_students_stats()
    {
        // Act
        $response = $this->getJson('/api/v1/stats/estudiantes');

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'total_estudiantes',
                     'con_evidencias',
                 ]);
    }

    public function test_can_get_evidences_stats()
    {
        // Act
        $response = $this->getJson('/api/v1/stats/evidencias');

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'total',
                     'por_estado',
                 ]);
    }

    public function test_can_filter_students_stats_by_module()
    {
        // Arrange
        $modulo = ModuloFormativo::factory()->create();

        // Act
        $response = $this->getJson("/api/v1/stats/estudiantes?modulo_id={$modulo->id}");

        // Assert
        $response->assertOk();
    }

    public function test_can_filter_evidences_stats_by_criteria()
    {
        // Arrange
        $criterio = CriterioEvaluacion::factory()->create();

        // Act
        $response = $this->getJson("/api/v1/stats/evidencias?criterio_id={$criterio->id}");

        // Assert
        $response->assertOk();
    }
}
