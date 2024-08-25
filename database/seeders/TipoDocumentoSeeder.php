<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_documentos')->insert([
            'sigla' => "CI",
            'detalle' => "(CI) Carnet de Identidad",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        DB::table('tipo_documentos')->insert([
            'sigla' => "LIC",
            'detalle' => "(LIC) Licencia de conducir",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        DB::table('tipo_documentos')->insert([
            'sigla' => "TIC",
            'detalle' => "(TIC) Tarjeta de identifiación del conductor",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        DB::table('tipo_documentos')->insert([
            'sigla' => "DNI",
            'detalle' => "(DNI) Documento Nacional de Identidad",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        DB::table('tipo_documentos')->insert([
            'sigla' => "NIT",
            'detalle' => "(NIT) Número de Identificación Tributaria",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        DB::table('tipo_documentos')->insert([
            'sigla' => "PAS",
            'detalle' => "(PAS) Pasaporte",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);
        DB::table('tipo_documentos')->insert([
            'sigla' => "OTRO",
            'detalle' => "OTRO",
            "created_at"=> date_create('now')->format('Y-m-d H:i:s'),
            "updated_at"=> date_create('now')->format('Y-m-d H:i:s')
        ]);

    }
}
