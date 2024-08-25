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
        /* $perfil = Perfil::create([
            'name' => "GLADYS ALBA FRANCO",
            'email' => '',
            'direccion' => '',
            'celular' => '',
            'nroDocumento' => '',
            'tipo_documento_id' => 1,
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        Habitante::create([
            'isDuenho' => true,
            'isDependiente' => false,
            'perfil_id' => $perfil->id,
            'vivienda_id' => 1,
            'profile_photo_path' => "",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]); */
    }
}