<?php

namespace Database\Seeders;

use App\Models\ResultadoAprendizaje;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        Model::unguard();
        Schema::disableForeignKeyConstraints();

        $this->call(CriteriosEvaluacionTableSeeder::class);
        $this->call(FamiliasProfesionalesTableSeeder::class);
        $this->call(CiclosFormativosTableSeeder::class);
        $this->call(EvidenciasTableSeeder::class);
        $this->call(ResultadosAprendizajeTableSeeder::class);

        Model::reguard();

        Schema::enableForeignKeyConstraints();
    }
}
