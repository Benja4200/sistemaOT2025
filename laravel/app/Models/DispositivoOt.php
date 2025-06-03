<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispositivoOt extends Model
{
    use SoftDeletes;

    protected $table = 'dispositivo_ot';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'cod_dispositivo',
        'cod_ot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'cod_dispositivo', 'id');
    }

    public function ot()
    {
        return $this->belongsTo(Ot::class, 'cod_ot', 'numero_ot');
    }

    public function detalles()
    {
        return $this->hasOne(DetalleDispositivoOt::class, 'cod_dispositivo_ot', 'id');
    }

    public function accesorios()
    {
        return $this->hasOne(AccesorioDispositivoOt::class, 'cod_dispositivo_ot', 'id');
    }

    public function tareaDispositivo()
    {
        return $this->hasMany(TareaDispositivo::class, 'cod_dispositivo_ot', 'id');
    }
    
    public function repuestosDispositivo()
    {
        return $this->hasMany(RepuestoDispositivoOt::class, 'cod_dispositivo_ot', 'id');
    }
}
