<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perfil;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Visitante extends Model
{
    use HasFactory;
    protected $table = "visitantes";
    protected $primaryKey = "id";
    protected $fillable = [
        'profile_photo_path',
        'is_permitido',
        'description_is_no_permitido',
        'perfil_id'
    ];
    public function perfil(): BelongsTo{
        return $this->belongsTo(Perfil::class, 'perfil_id','id');
    }
}