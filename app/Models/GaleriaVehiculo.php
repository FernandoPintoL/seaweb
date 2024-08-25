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
    public $timestamps = false;

    protected $fillable = [
        'photo_path',
        'detalle',
        'vehiculo_id',
        'created_at',
        'updated_at'
    ];
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class,'vehiculo_id','id');
    }
}