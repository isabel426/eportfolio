<?php

namespace Tests\Feature\Api;

use App\Models\CriterioEvaluacion;
use App\Models\ResultadoAprendizaje;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class CriteriosEvaluacionApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected ResultadoAprendizaje $resultadoAprendizaje;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->resultadoAprendizaje = ResultadoAprendizaje::factory()->create();
        Sanctum::actingAs($this->user);

    }

    public function test_can_list_criteriosEvaluacions()
    {
        // Arrange
        CriterioEvaluacion::factory()->count(3)->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'resultado_aprendizaje_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_criteriosEvaluacion()
    {
        // Arrange
        $data = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];

        // Act
        $response = $this->postJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'resultado_aprendizaje_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('criterios_evaluacion', [
            'codigo' => $data['codigo'],
            'descripcion' => $data['descripcion'],
            'peso_porcentaje' => $data['peso_porcentaje'],
            'orden' => $data['orden']
        ]);
    }

    public function test_can_show_criteriosEvaluacion()
    {
        // Arrange
        $criteriosEvaluacion = CriterioEvaluacion::factory()->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion/{$criteriosEvaluacion->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'resultado_aprendizaje_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_criteriosEvaluacion()
    {
        // Arrange
        $criteriosEvaluacion = CriterioEvaluacion::factory()->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id
        ]);
        $updateData = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];

        // Act
        $response = $this->putJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion/{$criteriosEvaluacion->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'resultado_aprendizaje_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                 ]);

        $criteriosEvaluacion->refresh();
        $this->assertEquals($updateData['codigo'], $criteriosEvaluacion->codigo);
        $this->assertEquals($updateData['descripcion'], $criteriosEvaluacion->descripcion);
        $this->assertEquals($updateData['peso_porcentaje'], $criteriosEvaluacion->peso_porcentaje);
        $this->assertEquals($updateData['orden'], $criteriosEvaluacion->orden);
    }

    public function test_can_delete_criteriosEvaluacion()
    {
        // Arrange
        $criteriosEvaluacion = CriterioEvaluacion::factory()->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion/{$criteriosEvaluacion->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'Criterio de Evaluación eliminado correctamente'
                 ]);
    }

    public function test_can_search_criteriosEvaluacions()
    {
        // Arrange
        $searchTerm = 'test search';
        $criteriosEvaluacion1 = CriterioEvaluacion::factory()->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id,
            'descripcion' => 'Contains test search term',

        ]);
        $criteriosEvaluacion2 = CriterioEvaluacion::factory()->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id,
            'descripcion' => 'Different content',

        ]);

        // Act
        $response = $this->getJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($criteriosEvaluacion1->id, $data[0]['id']);
    }

    public function test_can_paginate_criteriosEvaluacions()
    {
        // Arrange
        CriterioEvaluacion::factory()->count(25)->create([
            'resultado_aprendizaje_id' => $this->resultadoAprendizaje->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion?per_page=10");

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
        public function test_requires_codigo_field()
        {
            // Arrange
            $data = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];
            unset($data['codigo']);

            // Act
            $response = $this->postJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('codigo');
        }
        public function test_requires_descripcion_field()
        {
            // Arrange
            $data = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];
            unset($data['descripcion']);

            // Act
            $response = $this->postJson("/api/v1/resultados-aprendizaje/{$this->resultadoAprendizaje->id}/criterios-evaluacion", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('descripcion');
        }
}
