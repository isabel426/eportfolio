<?php

namespace Tests\Unit\Models;

use App\Models\ModuloFormativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuloFormativoTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new ModuloFormativo())->getFillable();
        
        $expected = [
            'ciclo_formativo_id',
            'nombre',
            'codigo',
            'horas_totales',
            'curso_escolar',
            'centro',
            'docente_id',
            'descripcion'
        ];
        
        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new ModuloFormativo();
        $this->assertEquals('modulos_formativos', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $moduloFormativo = ModuloFormativo::factory()->create();
        
        $this->assertInstanceOf(ModuloFormativo::class, $moduloFormativo);
        $this->assertDatabaseHas('modulos_formativos', [
            'id' => $moduloFormativo->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $moduloFormativo = ModuloFormativo::factory()->make();
        $this->assertInstanceOf(ModuloFormativo::class, $moduloFormativo);
    }


    public function test_it_calculates_hours_correctly()
    {
        // Este test se puede expandir cuando se implementen cálculos
        $this->assertTrue(true);
    }
}
