<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Habitante;
use App\Models\Perfil;

class HabitanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perfil = Perfil::create([
            'name' => "Jorge Perez",
            'email' => 'jorgeperez@gmail.com',
            'direccion' => 'B/Conavi, 5',
            'celular' => '73682145',
            'nroDocumento' => '8956887',
            'tipo_documento_id' => 1,
        ]);
        Habitante::create([
            'isDuenho' => true,
            'isDependiente' => false,
            'perfil_id' => $perfil->id,
            'vivienda_id' => 1,
            'profile_photo_path' => ""
        ]);
    }
}