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
        Tarea::truncate();
        Tarea::create([
            'criterio_evaluacion_id' => 1,
            'fecha_apertura' => now(),
            'fecha_cierre' => now()->addDays(7),
            'activo' => true,
            'observaciones' => 'DWEC UT18. Ejercicios 1 de 2',
        ]);

        Tarea::create([
            'criterio_evaluacion_id' => 1,
            'fecha_apertura' => now(),
            'fecha_cierre' => now()->addDays(10),
            'activo' => true,
            'observaciones' => 'DWEC UT18. Ejercicios 2 de 2',
        ]);

        Tarea::create([
            'criterio_evaluacion_id' => 1,
            'fecha_apertura' => now(),
            'fecha_cierre' => now()->addDays(10),
            'activo' => true,
            'observaciones' => 'DWEC UT18. Ejercicios de refuerzo',
        ]);


        Tarea::create([
            'criterio_evaluacion_id' => 1,
            'fecha_apertura' => now(),
            'fecha_cierre' => now()->addDays(10),
            'activo' => true,
            'observaciones' => 'DWEC UT18. Ejercicios portfolio',
        ]);
    }
}
