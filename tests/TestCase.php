<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Configuración común para todos los tests
        // Remover withoutExceptionHandling para production
        // $this->withoutExceptionHandling();
    }

    /**
     * Crea un usuario autenticado para tests
     */
    protected function authenticatedUser(array $attributes = []): \App\Models\User
    {
        $user = \App\Models\User::factory()->create($attributes);
        \Laravel\Sanctum\Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Crea datos de test específicos para una entidad
     */
    protected function createTestData(string $model, array $attributes = [], int $count = 1)
    {
        $factory = app("App\\Models\\{$model}")::factory();

        if ($count === 1) {
            return $factory->create($attributes);
        }

        return $factory->count($count)->create($attributes);
    }

    /**
     * Aserta que una respuesta JSON contiene la estructura esperada
     */
    protected function assertApiResponse($response, array $expectedStructure = [])
    {
        $response->assertOk()
                 ->assertJsonStructure(array_merge([
                     'data',
                     'meta',
                     'links'
                 ], $expectedStructure));
    }

    /**
     * Aserta que una validación falló para campos específicos
     */
    protected function assertValidationFailed($response, array $fields)
    {
        $response->assertUnprocessable();

        foreach ($fields as $field) {
            $response->assertJsonValidationErrors($field);
        }
    }

    /**
     * Ejecuta migraciones frescas para el test
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', [
                '--drop-views' => true,
                '--drop-types' => true,
                '--seed' => false,
            ]);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}

/**
 * Helper class para manejar el estado de la base de datos en tests
 */
class RefreshDatabaseState
{
    /**
     * Indica si las migraciones ya se ejecutaron
     */
    public static bool $migrated = false;
}
