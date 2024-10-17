<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\Permission;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $fillable = [
        'name',
        'email',
        'usernick',
        'password',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacion uno a muchos con Ingreso
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

    // Relación uno a uno: Un usuario tiene un condominio asignado
    public function condominio()
    {
        return $this->hasOne(Condominio::class, 'user_id');
    }
    // Relación muchos a muchos con Condominios
    public function condominios()
    {
        return $this->belongsToMany(Condominio::class, 'condominio_user')->withPivot('permisos');
    }

    public function verificarIngresos()
    {
        $ingresos = $this->ingresos();
        return $ingresos->exists();
        // return $ingresos->exists;
    }
    // Método para obtener los permisos de un condominio específico
    public function permisosEnCondominio($condominioId)
    {
        $condominio = $this->condominios()->where('condominio_id', $condominioId)->first();
        return $condominio ? $condominio->pivot->permisos : [];
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function canIndex($modelo)
    {
        if ($this->isSuperAdmin()) {
            return true;
        } else {
            $has_role       = $this->hasAnyRole($modelo);
            $has_permissions = $this->hasAnyPermission([$modelo . '.LISTAR', $modelo . '.MOSTRAR']);
            return $has_permissions || $has_role;
        }
    }

    public function canCrear($modelo)
    {
        if ($this->isSuperAdmin()) {
            return true;
        } else {
            $has_permissions = $this->hasPermissionTo($modelo . '.CREAR');
            return $has_permissions;
        }
    }

    public function canEditar($modelo)
    {
        if ($this->isSuperAdmin()) {
            return true;
        } else {
            $has_permissions = $this->hasPermissionTo($modelo . '.EDITAR');
            return $has_permissions;
        }
    }

    public function canEliminar($modelo)
    {
        if ($this->isSuperAdmin()) {
            return true;
        } else {
            $has_permissions = $this->hasPermissionTo($modelo . '.ELIMINAR');
            return $has_permissions;
        }
    }
}
