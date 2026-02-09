<?php

namespace Tests\Unit\Models;

use App\Models\Tarea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TareaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new Tarea())->getFillable();

        $expected = [
            'fecha_apertura',
            'fecha_cierre',
            'activo',
            'observaciones'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new Tarea();
        $this->assertEquals('tareas', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $tarea = Tarea::factory()->create();

        $this->assertInstanceOf(Tarea::class, $tarea);
        $this->assertDatabaseHas('tareas', [
            'id' => $tarea->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $tarea = Tarea::factory()->make();
        $this->assertInstanceOf(Tarea::class, $tarea);
    }


}
