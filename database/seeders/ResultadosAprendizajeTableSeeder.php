<?php

namespace Database\Seeders;

use App\Models\ResultadoAprendizaje;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResultadosAprendizajeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResultadoAprendizaje::truncate();
        foreach (self::$resultados as $resultado) {
            ResultadoAprendizaje::insert([
                'modulo_formativo_id' => $resultado['numero_resultado'],
                'descripción' => $resultado['descripcion'],
                'orden'=>$resultado['orden'],
                'peso_porcentaje'=>$resultado['peso'],
                'codigo'=>$resultado['codigo']

            ]);
        }
        $this->command->info('¡Tabla familias_profesionales inicializada con datos!');
    }

  public static $resultados=[
  [
    "numero_resultado"=> 1,
    "orden"=>1,
    "codigo"=>"RA1",
    "peso"=>35,
    "descripcion"=> "Selecciona las arquitecturas y tecnologías de programación web en entorno servidor, analizando sus capacidades y características propias.",
    "criterios_evaluacion"=> [
      "Se han caracterizado y diferenciado los modelos de ejecución de código en el servidor y en el cliente web.",
      "Se han reconocido las ventajas que proporciona la generación dinámica de páginas.",
      "Se han identificado los mecanismos de ejecución de código en los servidores web.",
      "Se han reconocido las funcionalidades que aportan los servidores de aplicaciones y su integración con los servidores web.",
      "Se han identificado y caracterizado los principales lenguajes y tecnologías relacionados con la programación web en entorno servidor.",
      "Se han verificado los mecanismos de integración de los lenguajes de marcas con los lenguajes de programación en entorno servidor.",
      "Se han reconocido y evaluado las herramientas y frameworks de programación en entorno servidor."
    ]
    ],
  [
    "numero_resultado"=> 2,
    "orden"=>2,
    "codigo"=>"RA2",
    "peso"=>30,
    "descripcion"=> "Escribe sentencias ejecutables por un servidor web reconociendo y aplicando procedimientos de integración del código en lenguajes de marcas.",
    "criterios_evaluacion"=> [
      "Se han reconocido los mecanismos de generación de páginas web a partir de lenguajes de marcas con código embebido.",
      "Se han identificado las principales tecnologías asociadas.",
      "Se han utilizado etiquetas para la inclusión de código en el lenguaje de marcas.",
      "Se ha reconocido la sintaxis del lenguaje de programación que se ha de utilizar.",
      "Se han escrito sentencias simples y se han comprobado sus efectos en el documento resultante.",
      "Se han utilizado directivas para modificar el comportamiento predeterminado.",
      "Se han utilizado los distintos tipos de variables y operadores disponibles en el lenguaje.",
      "Se han identificado los ámbitos de utilización de las variables."
    ]

    ]  ,
  [
    "numero_resultado"=> 3,
    "orden"=>3,
    "codigo"=>"RA3",
    "peso"=>35,
    "descripcion"=> "Escribe bloques de sentencias embebidos en lenguajes de marcas, seleccionando y utilizando las estructuras de programación.",
    "criterios_evaluacion"=> [
      "Se han utilizado mecanismos de decisión en la creación de bloques de sentencias.",
      "Se han utilizado bucles y se ha verificado su funcionamiento.",
      "Se han utilizado matrices (arrays) para almacenar y recuperar conjuntos de datos.",
      "Se han creado y utilizado funciones.",
      "Se han utilizado formularios web para interactuar con el usuario del navegador web.",
      "Se han empleado métodos para recuperar la información introducida en el formulario.",
      "Se han añadido comentarios al código."
    ]
  ]
    ];
    }
