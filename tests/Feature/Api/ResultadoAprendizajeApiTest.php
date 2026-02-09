<?php

namespace Tests\Feature\Api;

use App\Models\ModuloFormativo;
use App\Models\ResultadoAprendizaje;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class ResultadoAprendizajeApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected ModuloFormativo $moduloFormativo;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        $this->moduloFormativo = ModuloFormativo::factory()->create();

    }

    public function test_can_list_resultadoAprendizajes()
    {
        // Arrange
        ResultadoAprendizaje::factory()->count(3)->create([
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'modulo_formativo_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_resultadoAprendizaje()
    {
        // Arrange
        $data = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];

        // Act
        $response = $this->postJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'modulo_formativo_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('resultados_aprendizaje', [
            'codigo' => $data['codigo'],
            'descripcion' => $data['descripcion'],
            'peso_porcentaje' => $data['peso_porcentaje'],
            'orden' => $data['orden']
        ]);
    }

    public function test_can_show_resultadoAprendizaje()
    {
        // Arrange
        $resultadoAprendizaje = ResultadoAprendizaje::factory()->create([
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje/{$resultadoAprendizaje->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'modulo_formativo_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_resultadoAprendizaje()
    {
        // Arrange
        $resultadoAprendizaje = ResultadoAprendizaje::factory()->create([
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);
        $updateData = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];

        // Act
        $response = $this->putJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje/{$resultadoAprendizaje->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'modulo_formativo_id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden', 'created_at', 'updated_at']
                 ]);

        $resultadoAprendizaje->refresh();
        $this->assertEquals($updateData['codigo'], $resultadoAprendizaje->codigo);
        $this->assertEquals($updateData['descripcion'], $resultadoAprendizaje->descripcion);
        $this->assertEquals($updateData['peso_porcentaje'], $resultadoAprendizaje->peso_porcentaje);
        $this->assertEquals($updateData['orden'], $resultadoAprendizaje->orden);
    }

    public function test_can_delete_resultadoAprendizaje()
    {
        // Arrange
        $resultadoAprendizaje = ResultadoAprendizaje::factory()->create([
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje/{$resultadoAprendizaje->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'ResultadoAprendizaje eliminado correctamente'
                 ]);
    }

    public function test_can_search_resultadoAprendizajes()
    {
        // Arrange
        $searchTerm = 'test search';
        $resultadoAprendizaje1 = ResultadoAprendizaje::factory()->create([
            'descripcion' => 'Contains test search term',
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);
        $resultadoAprendizaje2 = ResultadoAprendizaje::factory()->create([
            'descripcion' => 'Different content',
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($resultadoAprendizaje1->id, $data[0]['id']);
    }

    public function test_can_paginate_resultadoAprendizajes()
    {
        // Arrange
        ResultadoAprendizaje::factory()->count(25)->create([
            'modulo_formativo_id' => $this->moduloFormativo->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje?per_page=10");

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
            $response = $this->postJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje", $data);

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
            $response = $this->postJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('descripcion');
        }
        public function test_requires_peso_porcentaje_field()
        {
            // Arrange
            $data = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];
            unset($data['peso_porcentaje']);

            // Act
            $response = $this->postJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('peso_porcentaje');
        }
        public function test_requires_orden_field()
        {
            // Arrange
            $data = [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph(),
            'peso_porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'orden' => $this->faker->numberBetween(1, 100)
        ];
            unset($data['orden']);

            // Act
            $response = $this->postJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/resultados-aprendizaje", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('orden');
        }

}
