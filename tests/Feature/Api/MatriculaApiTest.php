<?php

namespace Tests\Feature\Api;

use App\Models\FamiliaProfesional;
use App\Models\CicloFormativo;
use App\Models\Matricula;
use App\Models\ModuloFormativo;
use App\Models\User;
use GuzzleHttp\Promise\Each;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;
use PhpParser\Node\Expr\AssignOp\Mod;

class MatriculaApiTest extends FeatureTestCase
{
    use WithFaker;

    protected User $user;
    protected FamiliaProfesional $familiaProfesional; // Assuming you have a FamiliaProfesional model
    protected CicloFormativo $cicloFormativo; // Assuming you have a CicloFormativo model
    protected ModuloFormativo $moduloFormativo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

        // Create a FamiliaProfesional and CicloFormativo for the test
        $this->familiaProfesional = FamiliaProfesional::factory()->create();
        $this->cicloFormativo = CicloFormativo::factory()->create([
            'familia_profesional_id' => $this->familiaProfesional->id,
        ]);

        $this->moduloFormativo = ModuloFormativo::factory()->create([
            'ciclo_formativo_id' => $this->cicloFormativo->id, // Assuming a valid ciclo_formativo_id exists
            'docente_id' => $this->user->id // Assuming the user is a docente
        ]);

    }

    public function test_can_list_matriculas()
    {
        // Arrange
        Matricula::factory()->count(3)->create([
            'modulo_formativo_id' => $this->moduloFormativo->id,
        ]);

        $otroModulo = ModuloFormativo::factory()->create(['ciclo_formativo_id' => $this->cicloFormativo->id]);

        Matricula::factory()->create([
            'modulo_formativo_id' => $otroModulo->id,
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/matriculas");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'estudiante', 'modulo_formativo', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);

        // Sólo debe devolver las matrículas del módulo específico
        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_list_modulos_matriculados()
    {
        // Arrange
        $user = $this->user;

        // Crear dos módulos y matricular al usuario autenticado en ambos
        $modulo1 = ModuloFormativo::factory()->create(['ciclo_formativo_id' => $this->cicloFormativo->id]);
        $modulo2 = ModuloFormativo::factory()->create(['ciclo_formativo_id' => $this->cicloFormativo->id]);
        Matricula::factory()->create([
            'modulo_formativo_id' => $modulo1->id,
            'estudiante_id' => $user->id,
        ]);
        Matricula::factory()->create([
            'modulo_formativo_id' => $modulo2->id,
            'estudiante_id' => $user->id,
        ]);

        // Crear un módulo y matricular a otro usuario
        $otherUser = User::factory()->create();
        $modulo3 = ModuloFormativo::factory()->create(['ciclo_formativo_id' => $this->cicloFormativo->id]);
        Matricula::factory()->create([
            'modulo_formativo_id' => $modulo3->id,
            'estudiante_id' => $otherUser->id,
        ]);

        // Act
        $response = $this->getJson('/api/v1/modulos-matriculados');

        // Assert
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $modulo1->id])
            ->assertJsonFragment(['id' => $modulo2->id])
            ->assertJsonMissing(['id' => $modulo3->id]);
    }

    public function test_can_create_matricula()
    {
        // Arrange
        $data = [];

        // Act
        $response = $this->postJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/matriculas", $data);

        // Assert
        $response->assertCreated()
                 ->assertJsonStructure([
                     'data' => ['id', 'estudiante', 'modulo_formativo', 'created_at', 'updated_at']
                 ]);
    }

    public function test_batchStore_admin_behaviour()
    {
        // Arrange
        $students = User::factory()->count(3)->create();

        $teacher1 = User::factory()->create();
        $teacher2 = User::factory()->create();

        // Crear dos módulos, cada uno impartido por uno de los docentes
        $mod1 = ModuloFormativo::factory()->create([
            'ciclo_formativo_id' => $this->cicloFormativo->id,
            'docente_id' => $teacher1->id,
        ]);
        $mod2 = ModuloFormativo::factory()->create([
            'ciclo_formativo_id' => $this->cicloFormativo->id,
            'docente_id' => $teacher2->id,
        ]);

        $payload = [
            'estudiantes_id' => $students->pluck('id')->toArray(),
            'modulos_formativos_id' => [$mod1->id, $mod2->id],
        ];

        // Admin (según config app.admin.email)
        $admin = User::factory()->create(['email' => config('app.admin.email')]);

        Sanctum::actingAs($admin);
        $response = $this->postJson('/api/v1/matriculas', $payload);

        // Assert
        $response->assertOk();
        $this->assertCount(count($students) * 2, $response->json('data')); // 3 estudiantes * 2 módulos
        $this->assertDatabaseCount('matriculas', count($students) * 2);
    }

    public function test_batchStore_estudiante_behaviour()
    {
        $num_max_modulos = config('app.max_modulos_matricula', 5);
        // Arrange
        $students = User::factory()->count(3)->create();
        $student = User::factory()->create([
            'email' => 'student1@'.config('app.domains.estudiantes')
        ]); // será el que haga la petición

        $teacher = User::factory()->create();

        // Crear dos módulos, cada uno impartido por uno de los docentes
        $modulos = ModuloFormativo::factory()->count($num_max_modulos + 2)->create([
            'ciclo_formativo_id' => $this->cicloFormativo->id,
            'docente_id' => $teacher->id,
        ]);

        // Payload contiene array de estudiantes (pero al venir de un estudiante, debe ignorarse)
        $payload = [
            'estudiantes_id' => $students->pluck('id')->toArray(), // debe ser ignorado
            'modulos_formativos_id' => $modulos->pluck('id')->toArray(), // se aplicará el límite de 1 módulo
        ];

        // Act: petición autenticada como estudiante
        Sanctum::actingAs($student);
        $response = $this->postJson('/api/v1/matriculas', $payload);

        // Assert: sólo se matricula el usuario autenticado y sólo en 1 módulo (el primero)
        $response->assertOk();
        $this->assertCount($num_max_modulos, $response->json('data'));

        // Verificar DB: una matrícula y pertenece al estudiante autenticado
        $this->assertDatabaseCount('matriculas', $num_max_modulos);
        $this->assertDatabaseHas('matriculas', [
            'estudiante_id' => $student->id,
            'modulo_formativo_id' => $modulos[0]['id'], // se usó el primer módulo tras aplicar slice
        ]);

        // Asegurarse de que no hay matrículas para los otros estudiantes
        foreach ($students as $other) {
            $this->assertDatabaseMissing('matriculas', [
                'estudiante_id' => $other->id,
            ]);
        }
    }

    public function test_can_show_matricula()
    {
        // Arrange
        $matricula = Matricula::factory()->create([
            'modulo_formativo_id' => $this->moduloFormativo->id,
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/matriculas/{$matricula->id}");

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => ['id', 'estudiante', 'modulo_formativo', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_delete_matricula()
    {
        // Arrange
        $matricula = Matricula::factory()->create([
            'modulo_formativo_id' => $this->moduloFormativo->id,
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/matriculas/{$matricula->id}");

        // Assert
        $response->assertOk()
                 ->assertJson([
                     'message' => 'Matricula eliminado correctamente'
                 ]);
    }

    public function test_can_paginate_matriculas()
    {
        // Arrange
        Matricula::factory()->count(25)->create([
            'modulo_formativo_id' => $this->moduloFormativo->id,
        ]);

        // Act
        $response = $this->getJson("/api/v1/modulos-formativos/{$this->moduloFormativo->id}/matriculas?per_page=10");

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
}
