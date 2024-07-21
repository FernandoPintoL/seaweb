<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingreso;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class GaleriaIngreso extends Model
{
    use HasFactory;
    protected $table = "galeria_ingresos";
    protected $primaryKey = "id";
    protected $fillable = [
        'photo_path',
        'detalle',
        'isAutorizado',
        'ingreso_id',
    ];
    public function ingreso() : BelongsTo {
        return $this->belongsTo(Ingreso::class,'ingreso_id','id');
    }
}