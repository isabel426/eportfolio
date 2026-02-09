<?php

namespace Tests\Unit\Models;

use App\Models\AsignacionRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsignacionRevisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new AsignacionRevision())->getFillable();

        $expected = [
            'evidencia_id',
            'revisor_id',
            'asignado_por_id',
            'fecha_limite',
            'estado'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new AsignacionRevision();
        $this->assertEquals('asignaciones_revision', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $asignacionRevision = AsignacionRevision::factory()->create();

        $this->assertInstanceOf(AsignacionRevision::class, $asignacionRevision);
        $this->assertDatabaseHas('asignaciones_revision', [
            'id' => $asignacionRevision->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $asignacionRevision = AsignacionRevision::factory()->make();
        $this->assertInstanceOf(AsignacionRevision::class, $asignacionRevision);
    }


}
