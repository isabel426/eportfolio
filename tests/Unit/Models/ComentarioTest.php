<?php

namespace Tests\Unit\Models;

use App\Models\Comentario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComentarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new Comentario())->getFillable();

        $expected = [
            'evidencia_id',
            'user_id',
            'contenido',
            'tipo'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new Comentario();
        $this->assertEquals('comentarios', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $comentario = Comentario::factory()->create();

        $this->assertInstanceOf(Comentario::class, $comentario);
        $this->assertDatabaseHas('comentarios', [
            'id' => $comentario->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $comentario = Comentario::factory()->make();
        $this->assertInstanceOf(Comentario::class, $comentario);
    }


}
