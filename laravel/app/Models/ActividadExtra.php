<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActividadExtra extends Model
{
    use SoftDeletes;

    protected $table = 'actividad_extra';

    protected $fillable = [
        'nombre_actividad',
        'horas_actividad',
        'cod_ot',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function ot()
    {
        return $this->belongsTo(Ot::class, 'cod_ot', 'numero_ot');
    }
}
