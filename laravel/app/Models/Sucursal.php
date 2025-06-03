<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sucursal';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_sucursal',
        'telefono_sucursal',
        'direccion_sucursal',
        'cod_cliente',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationship
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cod_cliente');
    }

    public function contacto()
    {
        return $this->hasMany(Contacto::class, 'cod_sucursal');
    }

    public function dispositivo()
    {
        return $this->hasMany(Dispositivo::class, 'cod_sucursal');
    }
}
