<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Condominio;
use App\Models\perfil;
use Carbon\Carbon;
class CondominioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perfil = Perfil::create([
            'name' => "SEVILLA II",
            'email' => 'sevilla@gmail.com',
            'direccion' => 'B/Conavi, 15',
            'celular' => '3936031',
            'nroDocumento' => '109623235',
            'tipo_documento_id' => 5,
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        Condominio::create([
            'propietario'=> 'SEVILLA II',
            'razonSocial'=> 'SEVILLA II',
            'nit' => '109623235',
            'cantidad_viviendas' => 20,
            'perfil_id' => $perfil->id,
            'user_id' => 2,
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
    }
}
