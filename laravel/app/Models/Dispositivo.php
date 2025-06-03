<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispositivo extends Model
{
    use SoftDeletes;

    protected $table = 'dispositivo';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'numero_serie_dispositivo',
        'cod_modelo',
        'cod_sucursal',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'cod_modelo', 'id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'cod_sucursal', 'id');
    }
}
