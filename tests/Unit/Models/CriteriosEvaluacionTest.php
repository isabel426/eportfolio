<?php

namespace Tests\Unit\Models;

use App\Models\CriterioEvaluacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriteriosEvaluacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new CriterioEvaluacion())->getFillable();

        $expected = [
            'resultado_aprendizaje_id',
            'codigo',
            'descripcion',
            'peso_porcentaje',
            'orden'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new CriterioEvaluacion();
        $this->assertEquals('criterios_evaluacion', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $criteriosEvaluacion = CriterioEvaluacion::factory()->create();

        $this->assertInstanceOf(CriterioEvaluacion::class, $criteriosEvaluacion);
        $this->assertDatabaseHas('criterios_evaluacion', [
            'id' => $criteriosEvaluacion->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $criteriosEvaluacion = CriterioEvaluacion::factory()->make();
        $this->assertInstanceOf(CriterioEvaluacion::class, $criteriosEvaluacion);
    }


}
