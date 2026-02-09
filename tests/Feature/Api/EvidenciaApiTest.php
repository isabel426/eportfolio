<?php

namespace Tests\Feature\Api;

use App\Models\Tarea;
use App\Models\Evidencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class EvidenciaApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected Tarea $tarea;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
        $this->tarea = Tarea::factory()->create();

    }

    public function test_can_list_evidencias()
    {
        // Arrange
        Evidencia::factory()->count(3)->create(['tarea_id' => $this->tarea->id]);

        // Act
        $response = $this->getJson("/api/v1/tareas/{$this->tarea->id}/evidencias");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_list_evidencias_filtered_by_estado_validacion()
    {
        // Arrange
        Evidencia::factory()->create(['tarea_id' => $this->tarea->id, 'estado_validacion' => 'pendiente']);
        Evidencia::factory()->create(['tarea_id' => $this->tarea->id, 'estado_validacion' => 'validada']);
        Evidencia::factory()->create(['tarea_id' => $this->tarea->id, 'estado_validacion' => 'rechazada']);

        // Act
        $response = $this->getJson("/api/v1/tareas/{$this->tarea->id}/evidencias?estado_evidencia=validada");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('validada', $data[0]['estado_validacion']);
    }

    public function test_can_list_evidencias_user_filtered_by_estado_validacion()
    {
        // Arrange
        Evidencia::factory()->create(['tarea_id' => $this->tarea->id, 'estado_validacion' => 'pendiente', 'estudiante_id' => $this->user->id]);
        Evidencia::factory()->create(['tarea_id' => $this->tarea->id, 'estado_validacion' => 'validada', 'estudiante_id' => $this->user->id]);
        Evidencia::factory()->create(['tarea_id' => $this->tarea->id, 'estado_validacion' => 'rechazada', 'estudiante_id' => $this->user->id]);

        // Act
        $response = $this->getJson("/api/v1/users/{$this->user->id}/evidencias?estado_evidencia=validada");
        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('validada', $data[0]['estado_validacion']);
        $this->assertEquals($this->user->id, $data[0]['estudiante_id']);
    }

    public function test_can_list_evidencias_of_user()
    {
        $estudiante = User::factory()->create();
        $otroEstudiante = User::factory()->create();
        // Arrange
        Evidencia::factory()->count(3)->create([
                'tarea_id' => $this->tarea->id,
                'estudiante_id' => $estudiante->id
            ]);
        // Arrange
        Evidencia::factory()->count(3)->create([
                'tarea_id' => $this->tarea->id,
                'estudiante_id' => $otroEstudiante->id
            ]);

        // Act
        $response = $this->getJson("/api/v1/users/{$estudiante->id}/evidencias");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_evidencia()
    {
        // Arrange
        $data = [
            'url' => $this->faker->url(),
            'descripcion' => $this->faker->paragraph(),
            'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
        ];

        // Act
        $response = $this->postJson("/api/v1/tareas/{$this->tarea->id}/evidencias", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('evidencias', [
            'url' => $data['url'],
            'descripcion' => $data['descripcion'],
            'estado_validacion' => $data['estado_validacion']
        ]);
    }

    public function test_can_show_evidencia()
    {
        // Arrange
        $evidencia = Evidencia::factory()->create([
            'tarea_id' => $this->tarea->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/tareas/{$this->tarea->id}/evidencias/{$evidencia->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_evidencia()
    {
        // Arrange
        $evidencia = Evidencia::factory()->create([
            'tarea_id' => $this->tarea->id
        ]);
        $updateData = [
            'url' => $this->faker->url(),
            'descripcion' => $this->faker->paragraph(),
            'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
        ];

        // Act
        $response = $this->putJson("/api/v1/tareas/{$this->tarea->id}/evidencias/{$evidencia->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'created_at', 'updated_at']
                 ]);

        $evidencia->refresh();
        $this->assertEquals($updateData['url'], $evidencia->url);
        $this->assertEquals($updateData['descripcion'], $evidencia->descripcion);
        $this->assertEquals($updateData['estado_validacion'], $evidencia->estado_validacion);
    }

    public function test_can_delete_evidencia()
    {
        // Arrange
        $evidencia = Evidencia::factory()->create([
            'tarea_id' => $this->tarea->id
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/tareas/{$this->tarea->id}/evidencias/{$evidencia->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'Evidencia eliminado correctamente'
                 ]);
    }

    public function test_can_search_evidencias()
    {
        // Arrange
        $searchTerm = 'test search';
        $evidencia1 = Evidencia::factory()->create([
            'tarea_id' => $this->tarea->id,
            'descripcion' => 'Contains test search term',

        ]);
        $evidencia2 = Evidencia::factory()->create([
            'tarea_id' => $this->tarea->id,
            'descripcion' => 'Different content',

        ]);

        // Act
        $response = $this->getJson("/api/v1/tareas/{$this->tarea->id}/evidencias?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($evidencia1->id, $data[0]['id']);
    }

    public function test_can_paginate_evidencias()
    {
        // Arrange
        Evidencia::factory()->count(25)->create(
            ['tarea_id' => $this->tarea->id]
        );

        // Act
        $response = $this->getJson("/api/v1/tareas/{$this->tarea->id}/evidencias?per_page=10");

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

    public function test_requires_url_field()
    {
        // Arrange
        $data = [
        'url' => $this->faker->words(3, true),
        'descripcion' => $this->faker->paragraph(),
        'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
    ];
        unset($data['url']);

        // Act
        $response = $this->postJson("/api/v1/tareas/{$this->tarea->id}/evidencias", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('url');
    }
    public function test_requires_descripcion_field()
    {
        // Arrange
        $data = [
        'url' => $this->faker->url(),
        'descripcion' => $this->faker->paragraph(),
        'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
    ];
        unset($data['descripcion']);

        // Act
        $response = $this->postJson("/api/v1/tareas/{$this->tarea->id}/evidencias", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('descripcion');
    }
    public function test_requires_estado_validacion_field()
    {
        // Arrange
        $data = [
        'url' => $this->faker->url(),
        'descripcion' => $this->faker->paragraph(),
        'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
    ];
        unset($data['estado_validacion']);

        // Act
        $response = $this->postJson("/api/v1/tareas/{$this->tarea->id}/evidencias", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('estado_validacion');
    }

    public function test_estado_validacion_accepts_valid_values()
    {
        foreach (['pendiente', 'validada', 'rechazada'] as $value) {
            $data = [
        'url' => $this->faker->url(),
        'descripcion' => $this->faker->paragraph(),
        'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
    ];
            $data['estado_validacion'] = $value;

            $response = $this->postJson("/api/v1/tareas/{$this->tarea->id}/evidencias", $data);
            $response->assertCreated();
        }
    }

    public function test_estado_validacion_rejects_invalid_values()
    {
        // Arrange
        $data = [
        'url' => $this->faker->url(),
        'descripcion' => $this->faker->paragraph(),
        'estado_validacion' => $this->faker->randomElement(['pendiente', 'validada', 'rechazada'])
    ];
        $data['estado_validacion'] = 'invalid_value';

        // Act
        $response = $this->postJson("/api/v1/tareas/{$this->tarea->id}/evidencias", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('estado_validacion');
    }
}
