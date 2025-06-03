<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles; // Asegúrate de importar el trait

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles; // Incluye el trait HasRoles

    protected $table = 'usuario';

    protected $primaryKey = 'id';

    public $incrementing = true; // Cambia a true si el id es auto-incremental

    protected $keyType = 'int';

    protected $fillable = [
        'nombre_usuario',
        'password_usuario',
        'rol_usuario',
        'email_usuario',
        'email_verified_at',
        'firma',
    ];

    protected $hidden = [
        'password_usuario',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'email_verified_at'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'cod_usuario', 'id');
    }

    // Relación con Roles (opcional si usas Spatie correctamente)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }
    
    public function getEmailAttribute()
    {
        return $this->email_usuario;
    }

}
