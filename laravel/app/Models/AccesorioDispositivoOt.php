<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccesorioDispositivoOt extends Model
{
    use SoftDeletes;

    protected $table = 'accesorio_dispositivo_ot';

    protected $fillable = [
        'cargador_acc',
        'cargador_posee_acc',
        'cable_acc',
        'adaptador_acc',
        'bateria_acc',
        'bateria_posee_acc',
        'pantalla_acc',
        'teclado_acc',
        'drum_acc',
        'toner_acc',
        'cod_dispositivo_ot',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function dispositivo()
    {
        return $this->belongsTo(DispositivoOt::class, 'cod_dispositivo_ot');
    }
}
