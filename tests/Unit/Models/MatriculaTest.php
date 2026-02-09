<?php

namespace Tests\Unit\Models;

use App\Models\Matricula;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatriculaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new Matricula())->getFillable();

        $expected = [
            'estudiante_id',
            'modulo_formativo_id'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new Matricula();
        $this->assertEquals('matriculas', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $matricula = Matricula::factory()->create();

        $this->assertInstanceOf(Matricula::class, $matricula);
        $this->assertDatabaseHas('matriculas', [
            'id' => $matricula->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $matricula = Matricula::factory()->make();
        $this->assertInstanceOf(Matricula::class, $matricula);
    }


}
