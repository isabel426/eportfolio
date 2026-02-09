<?php

namespace Tests\Unit\Models;

use App\Models\FamiliaProfesional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamiliaProfesionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new FamiliaProfesional())->getFillable();
        
        $expected = [
            'nombre',
            'codigo',
            'descripcion'
        ];
        
        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new FamiliaProfesional();
        $this->assertEquals('familias_profesionales', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $familiaProfesional = FamiliaProfesional::factory()->create();
        
        $this->assertInstanceOf(FamiliaProfesional::class, $familiaProfesional);
        $this->assertDatabaseHas('familias_profesionales', [
            'id' => $familiaProfesional->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $familiaProfesional = FamiliaProfesional::factory()->make();
        $this->assertInstanceOf(FamiliaProfesional::class, $familiaProfesional);
    }


}
