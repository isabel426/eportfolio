<?php

namespace Tests\Feature\Api;

use App\Models\Comentario;
use App\Models\User;
use App\Models\Evidencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class ComentarioApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        $this->evidencia = Evidencia::factory()->create();
    }

    public function test_can_list_comentarios()
    {
        // Arrange
        Comentario::factory()->count(3)->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'evidencia_id', 'user_id', 'contenido', 'tipo', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_comentario()
    {
        // Arrange
        $data = [
            'contenido' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['feedback', 'mejora', 'felicitacion'])
        ];

        // Act
        $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'contenido', 'tipo', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('comentarios', [
            'contenido' => $data['contenido'],
            'tipo' => $data['tipo']
        ]);
    }

    public function test_can_show_comentario()
    {
        // Arrange
        $comentario = Comentario::factory()->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios/{$comentario->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'contenido', 'tipo', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_comentario()
    {
        // Arrange
        $comentario = Comentario::factory()->create(['evidencia_id' => $this->evidencia->id]);
        $updateData = [
            'contenido' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['feedback', 'mejora', 'felicitacion'])
        ];

        // Act
        $response = $this->putJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios/{$comentario->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'user_id', 'contenido', 'tipo', 'created_at', 'updated_at']
                 ]);

        $comentario->refresh();
        $this->assertEquals($updateData['contenido'], $comentario->contenido);
        $this->assertEquals($updateData['tipo'], $comentario->tipo);
    }

    public function test_can_delete_comentario()
    {
        // Arrange
        $comentario = Comentario::factory()->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->deleteJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios/{$comentario->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'Comentario eliminado correctamente'
                 ]);
    }

    public function test_can_search_comentarios()
    {
        // Arrange
        $searchTerm = 'test search';
        $comentario1 = Comentario::factory()->create([
            'contenido' => 'Contains test search term',
            'evidencia_id' => $this->evidencia->id
        ]);
        $comentario2 = Comentario::factory()->create([
            'contenido' => 'Different content',
            'evidencia_id' => $this->evidencia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($comentario1->id, $data[0]['id']);
    }

    public function test_can_paginate_comentarios()
    {
        // Arrange
        Comentario::factory()->count(25)->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios?per_page=10");

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

        public function test_requires_contenido_field()
        {
            // Arrange
            $data = [
            'contenido' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['feedback', 'mejora', 'felicitacion'])
        ];
            unset($data['contenido']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios", $data);

            // Assert
            $response->assertUnprocessable()
                        ->assertJsonValidationErrors('contenido');
        }
        public function test_requires_tipo_field()
        {
            // Arrange
            $data = [
            'contenido' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['feedback', 'mejora', 'felicitacion'])
        ];
            unset($data['tipo']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios", $data);

            // Assert
            $response->assertUnprocessable()
                        ->assertJsonValidationErrors('tipo');
        }
        public function test_tipo_accepts_valid_values()
        {
            foreach (['feedback', 'mejora', 'felicitacion'] as $value) {
                $data = [
            'contenido' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['feedback', 'mejora', 'felicitacion'])
        ];
                $data['tipo'] = $value;

                $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios", $data);
                $response->assertCreated();
            }
        }

        public function test_tipo_rejects_invalid_values()
        {
            // Arrange
            $data = [
            'contenido' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['feedback', 'mejora', 'felicitacion'])
        ];
            $data['tipo'] = 'invalid_value';

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('tipo');
        }

        public function test_cannot_access_comentario_from_wrong_parent()
        {
            // Arrange
            $otherEvidencia = Evidencia::factory()->create();
            $comentario = Comentario::factory()->create([
                'evidencia_id' => $this->evidencia->id
            ]);

            // Act
            $response = $this->getJson("/api/v1/evidencias/{$otherEvidencia->id}/comentarios/{$comentario->id}");

            // Assert
            $response->assertNotFound();
        }

        public function test_comentario_belongs_to_correct_parent()
        {
            // Arrange
            $comentario = Comentario::factory()->create([
                'evidencia_id' => $this->evidencia->id
            ]);

            // Act
            $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/comentarios/{$comentario->id}");

            // Assert
            $response->assertOk();
            $this->assertEquals($this->evidencia->id, $response->json('data.evidencia_id'));
        }
}
