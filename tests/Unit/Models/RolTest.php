<?php

namespace Tests\Unit\Models;

use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $fillable = (new Rol())->getFillable();
        
        $expected = [
            'name',
            'description'
        ];
        
        $this->assertEquals($expected, $fillable);
    }

    public function test_it_has_correct_table_name()
    {
        $model = new Rol();
        $this->assertEquals('roles', $model->getTable());
    }

    public function test_it_can_be_created_with_factory()
    {
        $rol = Rol::factory()->create();
        
        $this->assertInstanceOf(Rol::class, $rol);
        $this->assertDatabaseHas('roles', [
            'id' => $rol->id
        ]);
    }


    public function test_it_uses_expected_factory()
    {
        $rol = Rol::factory()->make();
        $this->assertInstanceOf(Rol::class, $rol);
    }


}
