<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulosFormativosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('modulos_formativos')->truncate();

        $ciclos_formativos = CiclosFormativosTableSeeder::$called;
        $codigosCiclo = array_column($ciclos_formativos, 'codCiclo');

        foreach (self::$modulos_formativos as $modulo) {
            DB::table('modulos_formativos')->insert([
                'ciclo_formativo_id' => array_search($modulo['codCiclo'], $codigosCiclo) + 1,
                'nombre' => $modulo['nombre'],
                'codigo' => $modulo['codigo'],
                'horas_totales' => $modulo['horasTotales'],
                'curso_escolar' => $modulo['cursoEscolar'],
                'centro' => $modulo['centro'],
                'docente_id' => $modulo['docenteId'],
                'descripcion' => $modulo['descripcion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->command->info('¡Tabla ciclos inicializada con datos!');
    }

/*
    MODULOS_FORMATIVOS {
        bigint id PK
        bigint ciclo_formativo_id FK
        varchar nombre
        varchar codigo
        int horas_totales
        varchar curso_escolar
        varchar centro
        bigint docente_id FK
        text descripcion
        timestamp created_at
        timestamp updated_at


*/


private static $modulos_formativos = [
    [
        'codCiclo' => 'IFC303',
        'nombre' => 'Desarrollo Web en Entorno Cliente',
        'codigo' => '0612',
        'horasTotales' => 160,
        'cursoEscolar' => '2025/2026',
        'centro' => 'IES Tecnológico',
        'docenteId' => 5,
        'descripcion' => 'Programación con JavaScript, frameworks modernos y manipulación del DOM.'
    ],
    [
        'codCiclo' => 'IFC303',
        'nombre' => 'Desarrollo Web en Entorno Servidor',
        'codigo' => '0613',
        'horasTotales' => 180,
        'cursoEscolar' => '2025/2026',
        'centro' => 'IES Tecnológico',
        'docenteId' => 2,
        'descripcion' => 'Desarrollo backend utilizando PHP, Laravel y gestión de bases de datos.'
    ],
    [
        'codCiclo' => 'IFC302',
        'nombre' => 'Sistemas Informáticos',
        'codigo' => '0483',
        'horasTotales' => 120,
        'cursoEscolar' => '2025/2026',
        'centro' => 'IES Tecnológico',
        'docenteId' => 3,
        'descripcion' => 'Instalación y configuración de sistemas operativos y hardware.'
    ],
    [
        'codCiclo' => 'IFC302',
        'nombre' => 'Bases de Datos',
        'codigo' => '0484',
        'horasTotales' => 190,
        'cursoEscolar' => '2025/2026',
        'centro' => 'IES Tecnológico',
        'docenteId' => 5,
        'descripcion' => 'Diseño lógico y físico de bases de datos relacionales SQL.'
    ],
    [
        'codCiclo' => 'IFC303',
        'nombre' => 'Despliegue de Aplicaciones Web',
        'codigo' => '0614',
        'horasTotales' => 90,
        'cursoEscolar' => '2025/2026',
        'centro' => 'IES Tecnológico',
        'docenteId' => 4,
        'descripcion' => 'Configuración de servidores web, DNS, FTP y contenedores Docker.'
    ]
];

}
