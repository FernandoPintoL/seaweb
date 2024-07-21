<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehiculo;
class GaleriaVehiculo extends Model
{
    use HasFactory;
    protected $table = "galeria_vehiculos";
    protected $primaryKey = "id";
    protected $fillable = [
        'photo_path',
        'detalle',
        'vehiculo_id'
    ];
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class,'vehiculo_id','id');
    }
}