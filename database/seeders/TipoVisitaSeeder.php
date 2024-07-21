<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoVisita;
class TipoVisitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoVisita::create([
            'sigla' => "FT",
            'detalle' => "FIESTA",
        ]);
        TipoVisita::create([
            'sigla' => "OTRO",
            'detalle' => "OTRO",
        ]);
    }
}