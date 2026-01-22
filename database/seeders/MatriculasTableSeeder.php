<?php

namespace Database\Seeders;

use App\Models\Matricula;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatriculasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear datos de ejemplo para la tabla matriculas
        //\App\Models\Matricula::factory()->count(10)->create();
        Matricula::truncate();
         foreach (self::$datos as $dato) {
           Matricula::insert([
               'estudiante_id' => $dato['estudiante_id'],
               'modulo_formativo_id' => $dato['modulo_formativo_id'],
           ]);

       }$this->command->info('¡Tabla matriculas inicializada con datos!');
   }
    public static $datos = [
         [
              'estudiante_id' => 1,
              'modulo_formativo_id' => 1,
         ],
         [
              'estudiante_id' => 2,
              'modulo_formativo_id' => 2,
         ],
         [
              'estudiante_id' => 3,
              'modulo_formativo_id' => 1,
         ],
         [
              'estudiante_id' => 4,
              'modulo_formativo_id' => 3,
         ],
         [
              'estudiante_id' => 5,
              'modulo_formativo_id' => 2,
         ],
    ];
}
