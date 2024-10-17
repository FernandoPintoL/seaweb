<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Condominio extends Model
{
    use HasFactory;
    protected $table = "condominios";
    protected $primaryKey = "id";
    protected $fillable = [
        'propietario',
        'razonSocial',
        'nit',
        'cantidad_viviendas',
        'perfil_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'perfil_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'condominio_user')->withPivot('permisos');
    }
    public function viviendas()
    {
        return $this->hasMany(Vivienda::class);
    }
}
