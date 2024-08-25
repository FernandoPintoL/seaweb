<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Administrador',
            'email' => 'administrador@gmail.com',
            'usernick' => 'administrador',
            'password' => Hash::make('123456789'),
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);

        User::create([
            'name' => 'Condominio Sevilla',
            'email' => 'sevilla@gmail.com',
            'usernick' => 'sevilla',
            'password' => Hash::make('123456789'),
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        $this->call([
            TipoDocumentoSeeder::class,
            TipoViviendaSeeder::class,
            TipoVisitaSeeder::class,
            CondominioSeeder::class,
            // ViviendaSeeder::class,
            // HabitanteSeeder::class,
            // VisitanteSeeder::class,
            // IngresoSeeder::class
        ]);
    }
}