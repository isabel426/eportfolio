<?php

namespace Database\Seeders;

use App\Models\Evidencia;
use App\Models\Evidencias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvidenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Evidencia::truncate();
        foreach (self::$evidencias as $evidencia) {
            $c = new Evidencia;
            $c->estudiante_id = $evidencia['estudiante_id'];
            $c->tarea_id = $evidencia['tarea_id'];
            $c->url = $evidencia['url'];
            $c->descripcion = $evidencia['descripcion'];
            $c->estado_validacion = $evidencia['estado_validacion'];
            $c->save();
        }
        $this->command->info('Tabla evidencias inicializada con datos!');
    }
    private static $evidencias = [
        [
            'estudiante_id' => 1,
            'tarea_id' => 1,
            'url' => 'http://ejemplo.com/evidencia/proyecto_final_ifc.zip',
            'descripcion' => 'Código y documentación del proyecto de fin de módulo de la familia IFC (Informática y Comunicaciones).',
            'estado_validacion' => 'validada',
        ],
        [
            'estudiante_id' => 2,
            'tarea_id' => 2,
            'url' => 'http://ejemplo.com/evidencia/caso_clinico_san.pdf',
            'descripcion' => 'Informe detallado del Caso Clínico A2, relacionado con la familia SAN (Sanidad).',
            'estado_validacion' => 'pendiente',
        ],
        [
            'estudiante_id' => 3,
            'tarea_id' => 1,
            'url' => 'http://ejemplo.com/evidencia/plan_reservas_hotel.xls',
            'descripcion' => 'Planificación de gestión de reservas para el primer trimestre del hotel (Familia HOTEL).',
            'estado_validacion' => 'rechazada',
        ],
    ];
}
