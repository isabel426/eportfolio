<?php

namespace Tests\Unit\Models;

use App\Models\CicloFormativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CicloFormativoTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new CicloFormativo())->getFillable();
        
        $expected = [
            'familia_profesional_id',
            'nombre',
            'codigo',
            'grado',
            'descripcion'
        ];
        
        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new CicloFormativo();
        $this->assertEquals('ciclos_formativos', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $cicloFormativo = CicloFormativo::factory()->create();
        
        $this->assertInstanceOf(CicloFormativo::class, $cicloFormativo);
        $this->assertDatabaseHas('ciclos_formativos', [
            'id' => $cicloFormativo->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $cicloFormativo = CicloFormativo::factory()->make();
        $this->assertInstanceOf(CicloFormativo::class, $cicloFormativo);
    }


}
