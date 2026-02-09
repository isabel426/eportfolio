<?php

namespace Tests\Feature\Api;

use App\Models\AsignacionRevision;
use App\Models\User;
use App\Models\Evidencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class AsignacionRevisionApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected Evidencia $parent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        $this->evidencia = Evidencia::factory()->create();
    }

    public function test_can_list_asignacionRevisions()
    {
        // Arrange
        AsignacionRevision::factory()->count(3)->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'evidencia_id', 'revisor_id', 'asignado_por_id', 'fecha_limite', 'estado', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_list_asignaciones_user_filtered_by_estado_asignacion()
    { // 'pendiente', 'completada', 'expirada'
        // Arrange
        AsignacionRevision::factory()->create([
            'revisor_id' => $this->user->id,
            'estado' => 'completada',
            'evidencia_id' => $this->evidencia->id
        ]);
        AsignacionRevision::factory()->create([
            'revisor_id' => $this->user->id,
            'estado' => 'pendiente',
            'evidencia_id' => $this->evidencia->id
        ]);
        AsignacionRevision::factory()->create([
            'revisor_id' => User::factory()->create()->id,
            'estado' => 'completada',
            'evidencia_id' => $this->evidencia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/users/{$this->user->id}/asignaciones-revision?estado_asignacion=" . urlencode('completada'));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals('completada', $data[0]['estado']);
        $this->assertEquals($this->user->id, $data[0]['revisor_id']);
    }

    public function test_can_list_asignaciones_of_user()
    {
        // Arrange
        $otherUser = User::factory()->create();

        // Crear 3 asignaciones para el usuario y 3 para otro usuario
        AsignacionRevision::factory()->count(3)->create([
            'revisor_id' => $this->user->id,
            'evidencia_id' => $this->evidencia->id
        ]);
        AsignacionRevision::factory()->count(3)->create([
            'revisor_id' => $otherUser->id,
            'evidencia_id' => $this->evidencia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/users/{$this->user->id}/asignaciones-revision");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'evidencia_id', 'revisor_id', 'asignado_por_id', 'fecha_limite', 'estado', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_asignacionRevision()
    {
        // Arrange
        $data = [
            'revisor_id' => User::factory()->create()->id,
            'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
        ];

        // Act
        $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'revisor_id', 'asignado_por_id', 'fecha_limite', 'estado', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('asignaciones_revision', [
            'fecha_limite' => $data['fecha_limite'],
            'estado' => $data['estado']
        ]);

        $this->assertDatabaseHas('evidencias', [
            'id' => $this->evidencia->id,
            'estado_validacion' => 'asignada'
        ]);
    }

    public function test_can_show_asignacionRevision()
    {
        // Arrange
        $asignacionRevision = AsignacionRevision::factory()->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision/{$asignacionRevision->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'revisor_id', 'asignado_por_id', 'fecha_limite', 'estado', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_asignacionRevision()
    {
        // Arrange
        $asignacionRevision = AsignacionRevision::factory()->create(['evidencia_id' => $this->evidencia->id]);
        $updateData = [
            'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
        ];

        // Act
        $response = $this->putJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision/{$asignacionRevision->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'evidencia_id', 'revisor_id', 'asignado_por_id', 'fecha_limite', 'estado', 'created_at', 'updated_at']
                 ]);

        $asignacionRevision->refresh();
        $this->assertEquals($updateData['fecha_limite'], $asignacionRevision->fecha_limite->format('Y-m-d'));
        $this->assertEquals($updateData['estado'], $asignacionRevision->estado);
    }

    public function test_can_delete_asignacionRevision()
    {
        // Arrange
        $asignacionRevision = AsignacionRevision::factory()->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->deleteJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision/{$asignacionRevision->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'AsignacionRevision eliminado correctamente'
                 ]);
    }

    public function test_can_search_asignacionRevisions()
    {
        // Arrange
        $searchTerm = 'completada';
        $asignacionRevision1 = AsignacionRevision::factory()->create([
            'estado' => 'completada',
            'evidencia_id' => $this->evidencia->id
        ]);
        $asignacionRevision2 = AsignacionRevision::factory()->create([
            'estado' => 'pendiente',
            'evidencia_id' => $this->evidencia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision?estado=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($asignacionRevision1->id, $data[0]['id']);
    }

    public function test_can_paginate_asignacionRevisions()
    {
        // Arrange
        AsignacionRevision::factory()->count(25)->create(['evidencia_id' => $this->evidencia->id]);

        // Act
        $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision?per_page=10");

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

        public function test_requires_revisor_id_field()
        {
            // Arrange
            $data = [
            'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
        ];
            unset($data['revisor_id']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('revisor_id');
        }

        public function test_requires_fecha_limite_field()
        {
            // Arrange
            $data = [
            'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
        ];
            unset($data['fecha_limite']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('fecha_limite');
        }
        public function test_requires_estado_field()
        {
            // Arrange
            $data = [
            'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
        ];
            unset($data['estado']);

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('estado');
        }
        public function test_estado_accepts_valid_values()
        {

            foreach (['pendiente', 'completada', 'expirada'] as $value) {
                $data = [
                    'revisor_id' => User::factory()->create()->id,
                    'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                    'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
                ];
                $data['estado'] = $value;

                $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision", $data);
                $response->assertCreated();
            }
        }

        public function test_estado_rejects_invalid_values()
        {
            // Arrange
            $data = [
            'revisor_id' => User::factory()->create()->id,
            'fecha_limite' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'expirada'])
        ];
            $data['estado'] = 'invalid_value';

            // Act
            $response = $this->postJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('estado');
        }

        public function test_cannot_access_asignacionRevision_from_wrong_parent()
        {
            // Arrange
            $otherEvidencia = Evidencia::factory()->create();
            $asignacionRevision = AsignacionRevision::factory()->create([
                'evidencia_id' => $this->evidencia->id
            ]);

            // Act
            $response = $this->getJson("/api/v1/evidencias/{$otherEvidencia->id}/asignaciones-revision/{$asignacionRevision->id}");

            // Assert
            $response->assertNotFound();
        }

        public function test_asignacionRevision_belongs_to_correct_parent()
        {
            // Arrange
            $asignacionRevision = AsignacionRevision::factory()->create([
                'evidencia_id' => $this->evidencia->id
            ]);

            // Act
            $response = $this->getJson("/api/v1/evidencias/{$this->evidencia->id}/asignaciones-revision/{$asignacionRevision->id}");

            // Assert
            $response->assertOk();
            $this->assertEquals($this->evidencia->id, $response->json('data.evidencia_id'));
        }
}
