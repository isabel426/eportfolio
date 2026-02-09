<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamiliaProfesional extends Model
{
        protected $table = 'familias_profesionales';

        protected $fillable = ['codigo', 'nombre', 'imagen', 'descripcion'];

        public static $filterColumns = [
            'codigo',
            'nombre',
            'imagen',
            'descripcion'
        ];

        /* Tests\Feature\Api\FamiliaProfesionalApiTest > codigo must be unique                                                                                         BadMethodCallException
  Call to undefined method App\Models\FamiliaProfesional::factory()*/

        /* HAz el método App\Models\FamiliaProfesional::factory()*/

        public static function factory()
        {
            return new \Database\Factories\FamiliaProfesionalFactory();
        }

}
