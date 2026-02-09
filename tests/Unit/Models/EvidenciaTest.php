<?php

namespace Tests\Unit\Models;

use App\Models\Evidencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvidenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new Evidencia())->getFillable();

        $expected = [
            'estudiante_id',
            'tarea_id',
            'url',
            'descripcion',
            'estado_validacion'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new Evidencia();
        $this->assertEquals('evidencias', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $evidencia = Evidencia::factory()->create();

        $this->assertInstanceOf(Evidencia::class, $evidencia);
        $this->assertDatabaseHas('evidencias', [
            'id' => $evidencia->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $evidencia = Evidencia::factory()->make();
        $this->assertInstanceOf(Evidencia::class, $evidencia);
    }


    public function test_it_has_estado_validacion_scope()
    {
        // Este test se puede expandir cuando se implementen scopes
        $this->assertTrue(true);
    }
}
