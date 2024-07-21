<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoVivienda;
class TipoViviendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoVivienda::create([
            'sigla' => "CS",
            'detalle' => "CASA",
        ]);
        TipoVivienda::create([
            'sigla' => "DPTO",
            'detalle' => "DEPARTAMENTO",
        ]);
        TipoVivienda::create([
            'sigla' => "OTRO",
            'detalle' => "OTRO",
        ]);
    }
}