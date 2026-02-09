<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Deshabilitar claves foráneas en SQLite para todos los tests
        if (\DB::getDriverName() === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // Configuración específica para Feature tests
        // La base de datos se resetea automáticamente con RefreshDatabase
    }
}
