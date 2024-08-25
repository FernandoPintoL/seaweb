<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingreso;

class DetalleNoAutorizado extends Model
{
    use HasFactory;
    protected $table = "detalle_no_autorizados";
    protected $primaryKey = "id";
    protected $fillable = [
        'ingreso_id',
        'detalle_no_autorizado',
        'created_at',
        'updated_at'
    ];
    public function ingreso(){
        return $this->belongsTo(Ingreso::class, 'ingreso_id', 'id');
    }
}