<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Condominio;
use App\Models\perfil;
class CondominioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perfil = Perfil::create([
            'name' => "SEVILLA",
            'email' => 'sevilla@gmail.com',
            'direccion' => 'B/Conavi, 15',
            'celular' => '3936031',
            'nroDocumento' => '109623235',
            'tipo_documento_id' => 3
        ]);
        Condominio::create([
            'propietario'=> 'SEVILLA',
            'razonSocial'=> 'SEVILLA CONDOMINIOS',
            'nit' => '109623235',
            'cantidad_viviendas' => 20,
            'perfil_id' => $perfil->id,
            'user_id' => 2,
        ]);
    }
}