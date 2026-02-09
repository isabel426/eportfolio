<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Evidencia;
use App\Models\ModuloFormativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $user = new User();
        $this->assertEquals(['name', 'email', 'password'], $user->getFillable());
    }

    public function test_it_hides_password_and_remember_token()
    {
        $user = new User(['password' => 'secret', 'remember_token' => 'token']);
        $array = $user->toArray();
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_evidencias_relationship_returns_correct_models()
    {
        $user = User::factory()->create();
        $evidencia = Evidencia::factory()->create(['estudiante_id' => $user->id]);
        $this->assertTrue($user->evidencias->contains($evidencia));
    }

    public function test_modulos_impartidos_relationship_returns_correct_models()
    {
        $user = User::factory()->create();
        $modulo = ModuloFormativo::factory()->create(['docente_id' => $user->id]);
        $this->assertTrue($user->modulosImpartidos->contains($modulo));
    }

    public function test_es_docente_returns_true_if_user_has_modulos()
    {
        $user = User::factory()->create();
        ModuloFormativo::factory()->create(['docente_id' => $user->id]);
        $this->assertTrue($user->esDocente(null));
    }

    public function test_es_docente_returns_false_if_user_has_no_modulos()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->esDocente(null));
    }

    public function test_es_docente_modulo_returns_true_if_user_is_docente_of_modulo()
    {
        $user = User::factory()->create();
        $modulo = ModuloFormativo::factory()->create(['docente_id' => $user->id]);
        $this->assertTrue($user->esDocenteModulo($modulo));
    }

    public function test_es_docente_modulo_returns_false_if_user_is_not_docente_of_modulo()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $modulo = ModuloFormativo::factory()->create(['docente_id' => $otherUser->id]);
        $this->assertFalse($user->esDocenteModulo($modulo));
    }

    public function test_es_estudiante_returns_true_if_user_is_enrolled_in_modulo()
    {
        $user = User::factory()->create();
        $modulo = ModuloFormativo::factory()->create();
        $user->modulosMatriculados()->attach($modulo->id);
        $this->assertTrue($user->modulosMatriculados->contains($modulo));
    }

    public function test_es_estudiante_returns_false_if_user_is_not_enrolled_in_modulo()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->esEstudiante(null));
    }

    public function test_es_estudiante_modulo_returns_true_if_user_is_enrolled_in_specific_modulo()
    {
        $user = User::factory()->create();
        $modulo = ModuloFormativo::factory()->create();
        $user->modulosMatriculados()->attach($modulo->id);
        $this->assertTrue($user->esEstudianteModulo($modulo));
    }

    public function test_es_estudiante_modulo_returns_false_if_user_is_not_enrolled_in_specific_modulo()
    {
        $user = User::factory()->create();
        $modulos = ModuloFormativo::factory()->count(2)->create();
        $user->modulosMatriculados()->attach($modulos[0]->id);
        $this->assertFalse($user->esEstudianteModulo($modulos[1]));
    }

    public function test_es_administrador_returns_false_if_user_is_not_administrador()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->esAdministrador());
    }

    public function test_es_administrador_returns_true_if_user_is_administrador()
    {
        $adminEmail = config('app.admin.email');
        $user = User::factory()->create(['email' => $adminEmail]);
        $this->assertTrue($user->esAdministrador());
    }
}
