<?php

namespace Tests\Feature\Api;

use App\Models\CriterioEvaluacion;
use App\Models\EvaluacionEvidencia;
use App\Models\User;
use App\Models\Evidencia;
use App\Models\Tarea;
use Database\Factories\ModuloFormativoFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class EvaluacionEvidenciaApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected Evidencia $evidencia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        $this->evidencia = Evidencia::factory()->create();
    }

    public function test_can_list_evaluacionEvidencias()
    {
        // Arrange
        EvaluacionEvidencia::factory()->count(3)->create(['evidencia_id' => $this->evidencia->id, 'estado' => 'pendiente']);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'evidencia_id', 'user_id', 'puntuacion', 'estado', 'observaciones', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_evaluacionEvidencia()
    {
        // Arrange
        $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'puntuacion', 'estado', 'observaciones', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('evaluaciones_evidencias', [
            'puntuacion' => $data['puntuacion'],
            'estado' => $data['estado'],
            'observaciones' => $data['observaciones']
        ]);
    }
    public function test_docente_change_evidencia_estado_when_creating_evaluacion()
    {
        // Arrange
        $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];

        $modulo = ModuloFormativoFactory::new()->create(['docente_id' => $this->user->id]);
        $resultado = $modulo->resultados_aprendizaje()->create(['codigo' => 'RA1', 'descripcion' => 'Descripción del RA1', 'peso_porcentaje' => 100, 'orden' => 1]);
        $criterio = $resultado->criterios_evaluacion()->create(['codigo' => 'CE1', 'descripcion' => 'Descripción del CE1', 'peso_porcentaje' => 50, 'orden' => 1]);
        $tarea = $criterio->tareas()->create(['titulo' => 'Tarea 1', 'descripcion' => 'Descripción de la tarea 1', 'tipo' => 'individual', 'fecha_cierre' => now()->addDays(7), 'fecha_apertura' => now()]);
        $this->evidencia->tarea()->associate($tarea);
        $this->evidencia->save();
        // Act
        $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'puntuacion', 'estado', 'observaciones', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('evaluaciones_evidencias', [
            'puntuacion' => $data['puntuacion'],
            'estado' => $data['estado'],
            'observaciones' => $data['observaciones']
        ]);

        $this->evidencia->refresh();
        if($data['estado'] === 'aprobada') {
            $this->assertEquals('validada', $this->evidencia->estado_validacion);
        } elseif($data['estado'] === 'rechazada') {
            $this->assertEquals('rechazada', $this->evidencia->estado_validacion);
        } else {
            $this->assertEquals('pendiente', $this->evidencia->estado_validacion);
        }
    }

    public function test_estudiante_dont_change_evidencia_estado_when_creating_evaluacion()
    {
        // Arrange
        $this->user = User::factory()->asEstudiante()->create();
        Sanctum::actingAs($this->user);

        $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];
        $estudiante = User::factory()->asEstudiante()->create();
        $this->actingAs($estudiante);

        $modulo = ModuloFormativoFactory::new()->create(['docente_id' => $this->user->id]);
        $resultado = $modulo->resultados_aprendizaje()->create(['codigo' => 'RA1', 'descripcion' => 'Descripción del RA1', 'peso_porcentaje' => 100, 'orden' => 1]);
        $criterio = $resultado->criterios_evaluacion()->create(['codigo' => 'CE1', 'descripcion' => 'Descripción del CE1', 'peso_porcentaje' => 50, 'orden' => 1]);
        $tarea = $criterio->tareas()->create(['titulo' => 'Tarea 1', 'descripcion' => 'Descripción de la tarea 1', 'tipo' => 'individual', 'fecha_cierre' => now()->addDays(7), 'fecha_apertura' => now()]);
        $this->evidencia->tarea()->associate($tarea);
        $this->evidencia->estado_validacion = 'pendiente';
        $this->evidencia->save();

        // Act
        $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'puntuacion', 'estado', 'observaciones', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('evaluaciones_evidencias', [
            'puntuacion' => $data['puntuacion'],
            'estado' => $data['estado'],
            'observaciones' => $data['observaciones']
        ]);

        $this->evidencia->refresh();
        $this->assertEquals('pendiente', $this->evidencia->estado_validacion);
    }

    public function test_can_show_evaluacionEvidencia()
    {
        // Arrange
        $evaluacionEvidencia = EvaluacionEvidencia::factory()->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias/{$evaluacionEvidencia->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'puntuacion', 'estado', 'observaciones', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_evaluacionEvidencia()
    {
        // Arrange
        $evaluacionEvidencia = EvaluacionEvidencia::factory()->create(['evidencia_id' => $this->evidencia->id]);
        $updateData = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->putJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias/{$evaluacionEvidencia->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'puntuacion', 'estado', 'observaciones', 'created_at', 'updated_at']
                 ]);

        $evaluacionEvidencia->refresh();
        $this->assertEquals($updateData['puntuacion'], $evaluacionEvidencia->puntuacion);
        $this->assertEquals($updateData['estado'], $evaluacionEvidencia->estado);
        $this->assertEquals($updateData['observaciones'], $evaluacionEvidencia->observaciones);
    }

    public function test_can_delete_evaluacionEvidencia()
    {
        // Arrange
        $evaluacionEvidencia = EvaluacionEvidencia::factory()->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->deleteJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias/{$evaluacionEvidencia->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'EvaluacionEvidencia eliminado correctamente'
                 ]);
    }

    public function test_can_search_evaluacionEvidencias()
    {
        // Arrange
        $searchTerm = 'test search';
        $evaluacionEvidencia1 = EvaluacionEvidencia::factory()->create([
            'observaciones' => 'Contains test search term',
            'evidencia_id' => $this->evidencia->id
        ]);
        $evaluacionEvidencia2 = EvaluacionEvidencia::factory()->create([
            'observaciones' => 'Different content',
            'evidencia_id' => $this->evidencia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($evaluacionEvidencia1->id, $data[0]['id']);
    }

    public function test_can_paginate_evaluacionEvidencias()
    {
        // Arrange
        EvaluacionEvidencia::factory()->count(25)->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias?per_page=10");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data',
                     'links' => ['first', 'last', 'prev', 'next'],
                     'meta' => ['current_page', 'total', 'per_page']
                 ]);

        $this->assertCount(10, $response->json('data'));
        $this->assertEquals(25, $response->json('meta.total'));
    }
        public function test_requires_puntuacion_field()
        {
            // Arrange
            $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];
            unset($data['puntuacion']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('puntuacion');
        }
        public function test_requires_estado_field()
        {
            // Arrange
            $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];
            unset($data['estado']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('estado');
        }

        public function test_estado_accepts_valid_values()
        {
            foreach (['pendiente', 'aprobada', 'rechazada'] as $value) {
                $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];
                $data['estado'] = $value;

                $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);
                $response->assertCreated();
            }
        }

        public function test_estado_rejects_invalid_values()
        {
            // Arrange
            $data = [
            'puntuacion' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'observaciones' => $this->faker->paragraph()
        ];
            $data['estado'] = 'invalid_value';

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('estado');
        }

        public function test_cannot_access_evaluacionEvidencia_from_wrong_parent()
        {
            // Arrange
            $otherEvidencia = Evidencia::factory()->create();
            $evaluacionEvidencia = EvaluacionEvidencia::factory()->create([
                'evidencia_id' => $this->evidencia->id
            ]);

            // Act
            $response = $this->getJson("/api/v1/evidencias/{$otherEvidencia->id}/evaluaciones-evidencias/{$evaluacionEvidencia->id}");

            // Assert
            $response->assertNotFound();
        }

        public function test_evaluacionEvidencia_belongs_to_correct_parent()
        {
            // Arrange
            $evaluacionEvidencia = EvaluacionEvidencia::factory()->create([
                'evidencia_id' => $this->evidencia->id
            ]);

            // Act
            $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/evaluaciones-evidencias/{$evaluacionEvidencia->id}");

            // Assert
            $response->assertOk();
            $this->assertEquals($this->evidencia->id, $response->json('data.evidencia_id'));
        }
}
