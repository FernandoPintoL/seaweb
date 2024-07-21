<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vivienda;

class GaleriaVivienda extends Model
{
    use HasFactory;
    protected $table = "galeria_viviendas";
    protected $primaryKey = "id";
    protected $fillable = [
        'photo_path' => '',
        'detalle' => '',
        'vivienda_id'=> 0,
    ];
    public function vivienda(){
        return $this->belongsTo(Vivienda::class,'vivienda_id', 'id');
    }
}