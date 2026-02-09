<?php

namespace Tests\Feature\Api;

use App\Models\FamiliaProfesional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class FamiliaProfesionalApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

    }

    public function test_can_list_familiaProfesionals()
    {
        // Arrange
        FamiliaProfesional::factory()->count(3)->create();

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'nombre', 'codigo', 'descripcion', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_familiaProfesional()
    {
        // Arrange
        $data = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->postJson("/api/v1/familias-profesionales", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'nombre', 'codigo', 'descripcion', 'created_at', 'updated_at']
                 ]);

        $this->assertDatabaseHas('familias_profesionales', [
            'nombre' => $data['nombre'],
            'codigo' => $data['codigo'],
            'descripcion' => $data['descripcion']
        ]);
    }

    public function test_can_show_familiaProfesional()
    {
        // Arrange
        $familiaProfesional = FamiliaProfesional::factory()->create();

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales/{$familiaProfesional->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'nombre', 'codigo', 'descripcion', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_update_familiaProfesional()
    {
        // Arrange
        $familiaProfesional = FamiliaProfesional::factory()->create();
        $updateData = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph()
        ];

        // Act
        $response = $this->putJson("/api/v1/familias-profesionales/{$familiaProfesional->id}", $updateData);

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'nombre', 'codigo', 'descripcion', 'created_at', 'updated_at']
                 ]);

        $familiaProfesional->refresh();
        $this->assertEquals($updateData['nombre'], $familiaProfesional->nombre);
        $this->assertEquals($updateData['codigo'], $familiaProfesional->codigo);
        $this->assertEquals($updateData['descripcion'], $familiaProfesional->descripcion);
    }

    public function test_can_delete_familiaProfesional()
    {
        // Arrange
        $familiaProfesional = FamiliaProfesional::factory()->create();

        // Act
        $response = $this->deleteJson("/api/v1/familias-profesionales/{$familiaProfesional->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'FamiliaProfesional eliminado correctamente'
                 ]);
    }

    public function test_can_search_familiaProfesionals()
    {
        // Arrange
        $searchTerm = 'test search';
        $familiaProfesional1 = FamiliaProfesional::factory()->create([
            'nombre' => 'Contains test search term',

        ]);
        $familiaProfesional2 = FamiliaProfesional::factory()->create([
            'nombre' => 'Different content',

        ]);

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales?search=" . urlencode($searchTerm));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals($familiaProfesional1->id, $data[0]['id']);
    }

    public function test_can_paginate_familiaProfesionals()
    {
        // Arrange
        FamiliaProfesional::factory()->count(25)->create();

        // Act
        $response = $this->getJson("/api/v1/familias-profesionales?per_page=10");

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
            'descripcion' => $this->faker->paragraph()
        ];
            unset($data['nombre']);

            // Act
            $response = $this->postJson("/api/v1/familias-profesionales", $data);

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
            'descripcion' => $this->faker->paragraph()
        ];
            unset($data['codigo']);

            // Act
            $response = $this->postJson("/api/v1/familias-profesionales", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('codigo');
        }

        public function test_codigo_must_be_unique()
        {
            // Arrange
            $existing = FamiliaProfesional::factory()->create();
            $data = [
            'nombre' => $this->faker->words(3, true),
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'descripcion' => $this->faker->paragraph()
        ];
            $data['codigo'] = $existing->codigo;

            // Act
            $response = $this->postJson("/api/v1/familias-profesionales", $data);

            // Assert
            $response->assertUnprocessable()
                     ->assertJsonValidationErrors('codigo');
        }

}
