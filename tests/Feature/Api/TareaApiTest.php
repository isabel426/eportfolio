<?php

namespace Tests\Feature\Api;

use App\Models\CriterioEvaluacion;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class TareaApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected CriterioEvaluacion $criterioEvaluacion;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        $this->criterioEvaluacion = CriterioEvaluacion::factory()->create();

    }

    public function test_can_list_tareas()
    {
        // Arrange
        $tareas = Tarea::factory()->count(3)->create();
        $tareasId = $tareas->pluck('id')->toArray();
        $this->criterioEvaluacion->tareas()->sync($tareasId);

        // Act
        $response = $this->getJson("/api/v1/criterios-evaluacion/{$this->criterioEvaluacion->id}/tareas");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'criterios_evaluacion', 'fecha_apertura', 'fecha_cierre', 'activo', 'observaciones', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_tarea()
    {
        // Arrange
        $data = [
            'criterios_evaluacion_id' => [$this->criterioEvaluacion->id],
            'fecha_apertura' => now()->format('Y-m-d H:i:s'),
            'fecha_cierre' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
            'activo' => $this->faker->boolean(),
            'observaciones' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->postJson("/api/v1/tareas", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'criterios_evaluacion', 'fecha_apertura', 'fecha_cierre', 'activo', 'observaciones', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('tareas', [
            'fecha_apertura' => $data['fecha_apertura'],
            'fecha_cierre' => $data['fecha_cierre'],
            'activo' => $data['activo'],
            'observaciones' => $data['observaciones']
        ]);
    }

    public function test_can_show_tarea()
    {
        // Arrange
        $tarea = Tarea::factory()->create();
        $this->criterioEvaluacion->tareas()->attach($tarea->id);

        // Act
        $response = $this->getJson("/api/v1/criterios-evaluacion/{$this->criterioEvaluacion->id}/tareas/{$tarea->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'fecha_apertura', 'fecha_cierre', 'activo', 'observaciones', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_tarea()
    {
        // Arrange
        $tarea = Tarea::factory()->create();
        $this->criterioEvaluacion->tareas()->attach($tarea->id);

        $updateData = [
            'criterios_evaluacion_id' => [$this->criterioEvaluacion->id],
            'fecha_apertura' => now()->format('Y-m-d H:i:s'),
            'fecha_cierre' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
            'activo' => $this->faker->boolean(),
            'observaciones' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->putJson("/api/v1/tareas/{$tarea->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'fecha_apertura', 'fecha_cierre', 'activo', 'observaciones', 'created_at', 'updated_at']
                 ]);

        $tarea->refresh();
        $this->assertEquals($updateData['fecha_apertura'], $tarea->fecha_apertura->format('Y-m-d H:i:s'));
        $this->assertEquals($updateData['fecha_cierre'], $tarea->fecha_cierre->format('Y-m-d H:i:s'));
        $this->assertEquals($updateData['activo'], $tarea->activo);
        $this->assertEquals($updateData['observaciones'], $tarea->observaciones);
    }

    public function test_asignacion_aleatoria_generates_balanced_assignments()
    {
        // Arrange
        $tarea = Tarea::factory()->create();
        $this->criterioEvaluacion->tareas()->attach($tarea->id);

        // Crear 20 usuarios
        $estudiantes = User::factory()->count(20)->create();

        // Crear 15 evidencias, cada una entregada por un estudiante distinto
        $evidencias = [];
        foreach ($estudiantes->take(15) as $estudiante) {
            $evidencias[] = $tarea->evidencias()->create([
                'estudiante_id' => $estudiante->id,
                'url' => $this->faker->url(),
                'descripcion' => $this->faker->sentence(),
                'estado_validacion' => 'pendiente'
            ]);
        }

        // Act: realizar la petición POST al endpoint de asignación aleatoria
        $response = $this->postJson("/api/v1/tareas/{$tarea->id}/asignacion-aleatoria");

        // Assert: la respuesta es exitosa
        $response->assertOk();

        // Comprobar que se han generado al menos 40 asignaciones
        $this->assertGreaterThanOrEqual(40, \App\Models\AsignacionRevision::whereIn('evidencia_id', collect($evidencias)->pluck('id'))->count());

        // Comprobar que cada estudiante que entregó evidencia tiene al menos 3 asignaciones como revisor
        foreach ($estudiantes->take(15) as $estudiante) {
            $asignacionesComoRevisor = \App\Models\AsignacionRevision::where('revisor_id', $estudiante->id)
                ->whereIn('evidencia_id', collect($evidencias)->pluck('id'))
                ->count();
            $this->assertGreaterThanOrEqual(3, $asignacionesComoRevisor, "El estudiante {$estudiante->id} tiene menos de 3 asignaciones.");
            $algunaEvidenciaNoAsignada = \App\Models\Evidencia::where('estado_validacion', 'asignada')
                ->whereIn('evidencia_id', collect($evidencias)->pluck('id'))
                ->count();
            $this->assertEquals(0, $algunaEvidenciaNoAsignada, "Existen evidencias sin el estado asignada.");
        }
    }

    public function test_can_delete_tarea()
    {
        // Arrange
        $tarea = Tarea::factory()->create();

        // Act
        $response = $this->deleteJson("/api/v1/tareas/{$tarea->id}");

        // Assert
        $response->assertOk();
        $this->assertDatabaseMissing('tareas', ['id' => $tarea->id]);
    }

    public function test_can_paginate_tareas()
    {
        // Arrange
        $tareas = Tarea::factory()->count(25)->create();
        $tareasId = $tareas->pluck('id')->toArray();
        $this->criterioEvaluacion->tareas()->sync($tareasId);


        // Act
        $response = $this->getJson("/api/v1/criterios-evaluacion/{$this->criterioEvaluacion->id}/tareas?per_page=10");

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

    public function test_requires_fecha_apertura_field()
    {
        // Arrange
        $data = [
            'criterios_evaluacion_id' => [$this->criterioEvaluacion->id],
            'fecha_apertura' => now()->format('Y-m-d H:i:s'),
            'fecha_cierre' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
            'activo' => $this->faker->boolean(),
            'observaciones' => $this->faker->paragraph()
        ];
        unset($data['fecha_apertura']);

        // Act
        $response = $this->postJson("/api/v1/tareas", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('fecha_apertura');
    }
    public function test_requires_fecha_cierre_field()
    {
        // Arrange
        $data = [
            'criterios_evaluacion_id' => [$this->criterioEvaluacion->id],
            'fecha_apertura' => now()->format('Y-m-d H:i:s'),
            'fecha_cierre' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
            'activo' => $this->faker->boolean(),
            'observaciones' => $this->faker->paragraph()
        ];
        unset($data['fecha_cierre']);

        // Act
        $response = $this->postJson("/api/v1/tareas", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('fecha_cierre');
    }
    public function test_requires_activo_field()
    {
        // Arrange
        $data = [
            'criterios_evaluacion_id' => [$this->criterioEvaluacion->id],
            'fecha_apertura' => now()->format('Y-m-d H:i:s'),
            'fecha_cierre' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
            'activo' => $this->faker->boolean(),
            'observaciones' => $this->faker->paragraph()
        ];
        unset($data['activo']);

        // Act
        $response = $this->postJson("/api/v1/tareas", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('activo');
    }
}
