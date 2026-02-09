<?php

namespace Tests\Unit\Models;

use App\Models\EvaluacionEvidencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluacionEvidenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new EvaluacionEvidencia())->getFillable();

        $expected = [
            'evidencia_id',
            'user_id',
            'puntuacion',
            'estado',
            'observaciones'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new EvaluacionEvidencia();
        $this->assertEquals('evaluaciones_evidencias', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $evaluacionEvidencia = EvaluacionEvidencia::factory()->create();

        $this->assertInstanceOf(EvaluacionEvidencia::class, $evaluacionEvidencia);
        $this->assertDatabaseHas('evaluaciones_evidencias', [
            'id' => $evaluacionEvidencia->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $evaluacionEvidencia = EvaluacionEvidencia::factory()->make();
        $this->assertInstanceOf(EvaluacionEvidencia::class, $evaluacionEvidencia);
    }


}
