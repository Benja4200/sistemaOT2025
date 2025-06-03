<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacto extends Model
{
    use SoftDeletes;

    protected $table = 'contacto';

    protected $primaryKey = 'id';

    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'nombre_contacto',
        'telefono_contacto',
        'departamento_contacto',
        'cargo_contacto',
        'email_contacto',
        'cod_sucursal',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'cod_sucursal', 'id');
    }
}
