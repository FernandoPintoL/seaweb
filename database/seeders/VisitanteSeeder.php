<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perfil;
use App\Models\Visitante;

class VisitanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perfil = Perfil::create([
            'name' => "Jose Pedro",
            'email' => 'josepedro@gmail.com',
            'direccion' => 'B/Conavi, 25',
            'celular' => '69089055',
            'nroDocumento' => '9623235',
            'tipo_documento_id' => 1,
        ]);
        Visitante::create([
            'profile_photo_path' => "",
            'perfil_id' => $perfil->id
        ]);
    }
}