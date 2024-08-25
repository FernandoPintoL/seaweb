<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Habitante;
use App\Models\Visitante;
use App\Models\Vivienda;
use App\Models\Vehiculo;
use App\Models\TipoVisita;
use App\Models\User;

class Ingreso extends Model
{
    use HasFactory;
    protected $table = "ingresos";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'tipo_ingreso',
        'detalle',
        'detalle_salida',
        'isAutorizado',
        'salida_created_at',
        'salida_updated_at',
        'visitante_id', ///FK
        'residente_habitante_id', ///FK
        'autoriza_habitante_id', ///FK
        'ingresa_habitante_id', ///FK
        'vehiculo_id', ///FK
        'tipo_visita_id', ///FK
        'user_id', ///FK
        'created_at',
        'updated_at'
    ];
    public function visitante(){
        return $this->belongsTo(Visitante::class, 'visitante_id','id');
    }
    public function residente(){
        return $this->belongsTo(Habitante::class, 'residente_habitante_id','id');
    }
    public function autoriza(){
        return $this->belongsTo(Habitante::class, 'autoriza_habitante_id','id');
    }
    public function ingresa(){
        return $this->belongsTo(Habitante::class, 'ingresa_habitante_id','id');
    }
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id','id');
    }
    public function tipoVisita(){
        return $this->belongsTo(TipoVisita::class, 'tipo_visita_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}