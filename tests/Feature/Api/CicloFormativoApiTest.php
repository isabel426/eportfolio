<?php

namespace Tests\Feature\Api;

use App\Models\CicloFormativo;
use App\Models\FamiliaProfesional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class CicloFormativoApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected FamiliaProfesional $familia;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => config('app.admin.email'),
        ]);
        Sanctum::actingAs($this->user);

        $this->familia = FamiliaProfesional::factory()->create();

    }

    public function test_can_list_cicloFormativos()
    {
        // Arrange
        CicloFormativo::factory()->count(3)->create([
            'familia_profesional_id' => $this->familia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'familia_profesional_id', 'nombre', 'codigo', 'grado', 'descripcion', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_cicloFormativo()
    {
        // Arrange
        $data = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
            'descripcion' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'familia_profesional_id', 'nombre', 'codigo', 'grado', 'descripcion', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('ciclos_formativos', [
            'nombre' => $data['nombre'],
            'codigo' => $data['codigo'],
            'grado' => $data['grado'],
            'descripcion' => $data['descripcion']
        ]);
    }

    public function test_no_administrator_cannot_create_cicloFormativo()
    {
        // Arrange
        $nonAdminUser = User::factory()->create();
        Sanctum::actingAs($nonAdminUser);

        $data = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
            'descripcion' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertForbidden();

        $this->assertDatabaseMissing('ciclos_formativos', [
            'nombre' => $data['nombre'],
            'codigo' => $data['codigo'],
            'grado' => $data['grado'],
            'descripcion' => $data['descripcion']
        ]);
    }

    public function test_can_show_cicloFormativo()
    {
        // Arrange
        $cicloFormativo = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos/{$cicloFormativo->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'familia_profesional_id', 'nombre', 'codigo', 'grado', 'descripcion', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_cicloFormativo()
    {
        // Arrange
        $cicloFormativo = CicloFormativo::factory()->create(
            ['familia_profesional_id' => $this->familia->id]
        );
        $updateData = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
            'descripcion' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->putJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos/{$cicloFormativo->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'familia_profesional_id', 'nombre', 'codigo', 'grado', 'descripcion', 'created_at', 'updated_at']
                 ]);

        $cicloFormativo->refresh();
        $this->assertEquals($updateData['nombre'], $cicloFormativo->nombre);
        $this->assertEquals($updateData['codigo'], $cicloFormativo->codigo);
        $this->assertEquals($updateData['grado'], $cicloFormativo->grado);
        $this->assertEquals($updateData['descripcion'], $cicloFormativo->descripcion);
    }

    public function test_no_administrator_cannot_update_cicloFormativo()
    {
        // Arrange
        $nonAdminUser = User::factory()->create();
        Sanctum::actingAs($nonAdminUser);

        $cicloFormativo = CicloFormativo::factory()->create(
            ['familia_profesional_id' => $this->familia->id]
        );
        $originalData = $cicloFormativo->toArray();
        $updateData = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
            'descripcion' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->putJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos/{$cicloFormativo->id}", $updateData);

        // Assert
        $response->assertForbidden();

        $cicloFormativo->refresh();
        $this->assertEquals($originalData['nombre'], $cicloFormativo->nombre);
        $this->assertEquals($originalData['codigo'], $cicloFormativo->codigo);
        $this->assertEquals($originalData['grado'], $cicloFormativo->grado);
        $this->assertEquals($originalData['descripcion'], $cicloFormativo->descripcion);
    }

    public function test_can_delete_cicloFormativo()
    {
        // Arrange
        $cicloFormativo = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familia->id
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos/{$cicloFormativo->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'CicloFormativo eliminado correctamente'
                 ]);
    }

    public function test_no_administrator_cannot_delete_cicloFormativo()
    {
        // Arrange
        $nonAdminUser = User::factory()->create();
        Sanctum::actingAs($nonAdminUser);

        $cicloFormativo = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familia->id
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos/{$cicloFormativo->id}");

        // Assert
        $response->assertForbidden();
    }

    public function test_can_search_cicloFormativos()
    {
        // Arrange
        $searchTerm = 'test search';
        $cicloFormativo1 = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familia->id,
            'nombre' => 'Contains test search term',

        ]);
        $cicloFormativo2 = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familia->id,
            'nombre' => 'Different content',

        ]);

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($cicloFormativo1->id, $data[0]['id']);
    }

    public function test_can_paginate_cicloFormativos()
    {
        // Arrange
        CicloFormativo::factory()->count(25)->create([
            'familia_profesional_id' => $this->familia->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos?per_page=10");

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

    public function test_requires_nombre_field()
    {
        // Arrange
        $data = [
        'nombre' => $this->faker->words(3, true),
        'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
        'descripcion' => $this->faker->paragraph()
    ];
        unset($data['nombre']);

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('nombre');
    }
    public function test_requires_codigo_field()
    {
        // Arrange
        $data = [
        'nombre' => $this->faker->words(3, true),
        'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
        'descripcion' => $this->faker->paragraph()
    ];
        unset($data['codigo']);

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('codigo');
    }
    public function test_requires_grado_field()
    {
        // Arrange
        $data = [
        'nombre' => $this->faker->words(3, true),
        'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
        'descripcion' => $this->faker->paragraph()
    ];
        unset($data['grado']);

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('grado');
    }
    public function test_codigo_must_be_unique()
    {
        // Arrange
        $existing = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familia->id
        ]);
        $data = [
        'nombre' => $this->faker->words(3, true),
        'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
        'descripcion' => $this->faker->paragraph()
    ];
        $data['codigo'] = $existing->codigo;

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('codigo');
    }
    public function test_grado_accepts_valid_values()
    {
        foreach (['basico', 'medio', 'superior'] as $value) {
            $data = [
        'nombre' => $this->faker->words(3, true),
        'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
        'descripcion' => $this->faker->paragraph()
    ];
            $data['grado'] = $value;

            $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);
            $response->assertCreated();
        }
    }

    public function test_grado_rejects_invalid_values()
    {
        // Arrange
        $data = [
        'nombre' => $this->faker->words(3, true),
        'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        'grado' => $this->faker->randomElement(['basico', 'medio', 'superior']),
        'descripcion' => $this->faker->paragraph()
    ];
        $data['grado'] = 'invalid_value';

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales/{$this->familia->id}/ciclos-formativos", $data);

        // Assert
        $response->assertUnprocessable()
                    ->assertJsonValidationErrors('grado');
    }

}
