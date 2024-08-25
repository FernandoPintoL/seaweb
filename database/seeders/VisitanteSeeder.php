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
        /* $perfil = Perfil::create([
            'name' => "FERNANDO PINTO LINO",
            'email' => 'pintolinofernando@gmail.com',
            'direccion' => 'B/Conavi, 25',
            'celular' => '73682145',
            'nroDocumento' => '8956887',
            'tipo_documento_id' => 1,
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        Visitante::create([
            'profile_photo_path' => "",
            'perfil_id' => $perfil->id,
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]); */
    }
}