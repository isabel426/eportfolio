<?php

namespace Tests\Support;

class FactoryDataHelper
{
    /**
     * Genera datos válidos para una entidad específica
     */
    public static function validDataFor(string $entity): array
    {
        return match($entity) {
            'familia_profesional' => [
                'nombre' => 'Informática y Comunicaciones',
                'codigo' => 'IFC001',
                'descripcion' => 'Familia profesional de informática'
            ],
            'ciclo_formativo' => [
                'nombre' => 'Desarrollo de Aplicaciones Web',
                'codigo' => 'DAW001',
                'grado' => 'superior',
                'descripcion' => 'Ciclo de desarrollo web'
            ],
            'modulo_formativo' => [
                'nombre' => 'Desarrollo Web en Entorno Servidor',
                'codigo' => 'DWES',
                'horas_totales' => 160,
                'curso_escolar' => '2024-2025',
                'centro' => 'CIFP Carlos III',
                'descripcion' => 'Módulo de backend'
            ],
            'evidencia' => [
                'url' => 'https://github.com/usuario/proyecto',
                'descripcion' => 'Proyecto de ejemplo',
                'estado_validacion' => 'pendiente'
            ],
            default => []
        };
    }

    /**
     * Genera datos inválidos para tests de validación
     */
    public static function invalidDataFor(string $entity, string $field): array
    {
        $validData = self::validDataFor($entity);
        
        return match($field) {
            'email' => array_merge($validData, ['email' => 'invalid-email']),
            'required' => array_merge($validData, [$field => null]),
            'numeric' => array_merge($validData, [$field => 'not-a-number']),
            'enum' => array_merge($validData, [$field => 'invalid-enum-value']),
            default => $validData
        };
    }
}
