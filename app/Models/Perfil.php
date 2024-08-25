<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TipoDocumento;
use App\Models\Habitante;

class Perfil extends Model
{
    use HasFactory;
    protected $table = "perfils";
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'email',
        'direccion',
        'celular',
        'nroDocumento',
        'tipo_documento_id',
        'created_at',
        'updated_at'
    ];
    
    public function tipoDocumento() : BelongsTo{
        return $this->belongsTo(TipoDocumento::class);
    }

    public function habitante(){
        return $this->hasOne(Habitante::class);
    }
}