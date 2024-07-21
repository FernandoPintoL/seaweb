<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vivienda;

class ViviendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vivienda::create([
            'nroVivienda' => 'C5',
            'detalle' => "",
            'nroEspacios' => 5,
            'vivienda_ocupada' => true,
            "tipo_vivienda_id" => 1, ///FK
            "condominio_id" => 1, ///FK
        ]);
    }
}