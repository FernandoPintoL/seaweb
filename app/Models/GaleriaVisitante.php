<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visitante;

class GaleriaVisitante extends Model
{
    use HasFactory;
    protected $table = "galeria_visitantes";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'photo_path',
        'detalle',
        'visitante_id',
        'created_at',
        'updated_at'
    ];
    public function visitante(){
        return $this->belongsTo(Visitante::class,'visitante_id','id');
    }
}