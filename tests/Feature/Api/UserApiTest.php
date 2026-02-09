<?php

namespace Tests\Feature\Api;

use App\Models\CicloFormativo;
use App\Models\Matricula;
use App\Models\ModuloFormativo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use Laravel\Sanctum\Sanctum;

class UserApiTest extends FeatureTestCase
{
    use RefreshDatabase;

    public function test_admin_profile_roles()
    {
        // Admin identificado por la configuración
        $admin = User::factory()->create(['email' => config('app.admin.email')]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/user')
            ->assertOk()
            ->assertJsonPath('roles', ['administrador']);
    }

    public function test_docente_profile_roles()
    {
        $user = User::factory()->create();

        $ciclo = CicloFormativo::factory()->create();
        ModuloFormativo::factory()->create([
            'ciclo_formativo_id' => $ciclo->id,
            'docente_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/user')
            ->assertOk()
            ->assertJsonPath('roles', ['docente']);
    }

    public function test_estudiante_profile_roles()
    {
        $user = User::factory()->create();

        $modulo = ModuloFormativo::factory()->create();
        Matricula::factory()->create([
            'modulo_formativo_id' => $modulo->id,
            'estudiante_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/user')
            ->assertOk()
            ->assertJsonPath('roles', ['estudiante']);
    }

    public function test_estudiante_and_docente_profile_roles()
    {
        $user = User::factory(2)->create();

        $ciclo = CicloFormativo::factory()->create();

        // módulo donde es docente
        ModuloFormativo::factory()->create([
            'ciclo_formativo_id' => $ciclo->id,
            'docente_id' => $user[1]->id,
        ]);

        // módulo donde es estudiante (matrícula)
        $modAlumno = ModuloFormativo::factory()->create([
            'ciclo_formativo_id' => $ciclo->id,
            'docente_id' => $user[0]->id,
        ]);
        Matricula::factory()->create([
            'modulo_formativo_id' => $modAlumno->id,
            'estudiante_id' => $user[1]->id,
        ]);

        Sanctum::actingAs($user[1]);

        $this->getJson('/api/v1/user')
            ->assertOk()
            ->assertJsonPath('roles', ['docente', 'estudiante']);
    }
}
