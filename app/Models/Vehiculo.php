<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    protected $table = "vehiculos";
    protected $primaryKey = "id";
    protected $fillable = [
        'placa',
        'visitante_id',
        'detalle',
        'photo_path',
        'tipo_vehiculo',
        'created_at',
        'updated_at'
    ];
}