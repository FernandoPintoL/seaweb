<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Perfil;
class Habitante extends Model
{
    use HasFactory;
    protected $table = "habitantes";
    protected $primaryKey = "id";
    protected $fillable = [
        'isDuenho',
        'isDependiente',
        'responsable_id',
        'perfil_id',
        'vivienda_id',
        'profile_photo_path',
        'created_at',
        'updated_at'
    ];
    public function perfil(){
        return $this->belongsTo(Perfil::class, 'perfil_id','id');
    }
    public function vivienda(){
        return $this->belongsTo(Vivienda::class, 'vivienda_id','id');
    }
    public function responsable(){
        return $this->hasOne(Habitante::class, 'responsable_id','id');
    }
}