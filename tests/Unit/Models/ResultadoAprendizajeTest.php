<?php

namespace Tests\Unit\Models;

use App\Models\ResultadoAprendizaje;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultadoAprendizajeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new ResultadoAprendizaje())->getFillable();
        
        $expected = [
            'modulo_formativo_id',
            'codigo',
            'descripcion',
            'peso_porcentaje',
            'orden'
        ];
        
        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new ResultadoAprendizaje();
        $this->assertEquals('resultados_aprendizaje', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $resultadoAprendizaje = ResultadoAprendizaje::factory()->create();
        
        $this->assertInstanceOf(ResultadoAprendizaje::class, $resultadoAprendizaje);
        $this->assertDatabaseHas('resultados_aprendizaje', [
            'id' => $resultadoAprendizaje->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $resultadoAprendizaje = ResultadoAprendizaje::factory()->make();
        $this->assertInstanceOf(ResultadoAprendizaje::class, $resultadoAprendizaje);
    }


}
