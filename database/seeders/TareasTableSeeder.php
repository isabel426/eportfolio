<?php

namespace Database\Seeders;

use App\Models\Tarea;
use Illuminate\Database\Seeder;

class TareasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Tarea::create([
            'criterio_evaluacion_id' => 1,
            'fecha_apertura' => now(),
            'fecha_cierre' => now()->addDays(7),
            'activo' => true,
            'enunciado' => 'Tarea de prueba 1',
        ]);

        Tarea::create([
            'criterio_evaluacion_id' => 1,
            'fecha_apertura' => now(),
            'fecha_cierre' => now()->addDays(10),
            'activo' => true,
            'enunciado' => 'Tarea de prueba 2',
        ]);
    }
}
