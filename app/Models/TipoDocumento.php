<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perfil;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TipoDocumento extends Model
{
    use HasFactory;
    protected $table = "tipo_documentos";
    protected $primaryKey = "id";
    protected $fillable = [
        'sigla',
        'detalle',
    ];
}