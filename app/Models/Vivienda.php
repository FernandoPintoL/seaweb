<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoVivienda;
use App\Models\Condominio;
class Vivienda extends Model
{
    use HasFactory;
    protected $table = "viviendas";
    protected $primaryKey = "id";
    protected $fillable = [
        'nroVivienda',
        'detalle',
        'nroEspacios',
        "vivienda_ocupada",
        "tipo_vivienda_id", ///FK
        "condominio_id", ///FK
        'created_at',
        'updated_at'
    ];

    public function tipoVivienda(){
        return $this->belongsTo(TipoVivienda::class,"tipo_vivienda_id", 'id');
    }
    public function condominio(){
        return $this->belongsTo(Condominio::class,"condominio_id", 'id');
    }
}