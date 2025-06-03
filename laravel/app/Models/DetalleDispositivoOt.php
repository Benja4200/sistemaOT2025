<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleDispositivoOt extends Model
{
    use SoftDeletes;

    protected $table = 'detalle_dispositivo_ot';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'rayones_det',
        'rupturas_det',
        'tornillos_det',
        'gomas_det',
        'estado_dispositivo_det',
        'observaciones_det',
        'cod_dispositivo_ot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function dispositivoOt()
    {
        return $this->belongsTo(DispositivoOt::class, 'cod_dispositivo_ot', 'id');
    }
}
