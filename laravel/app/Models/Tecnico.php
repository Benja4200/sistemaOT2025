<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tecnico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tecnico';

    protected $primaryKey = 'id';

    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'nombre_tecnico',
        'rut_tecnico',
        'telefono_tecnico',
        'email_tecnico',
        'precio_hora_tecnico',
        'cod_usuario',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationship
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario');
    }

    public function tecnicoServicio()
    {
        return $this->hasMany(TecnicoServicio::class, 'cod_tecnico');
    }
    
     public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'tecnico_servicio', 'cod_tecnico', 'cod_servicio');
    }
}
