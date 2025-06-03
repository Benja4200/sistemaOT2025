<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepuestoDispositivoOt extends Model
{
    use SoftDeletes;

    protected $table = 'repuesto_dispositivo_ot';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'observacion_repuesto',
        'cod_repuesto',
        'cod_dispositivo_ot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relación con el repuesto.
     * Se asume que la tabla de repuestos se llama "repuestos" y su pk es "id".
     */
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'cod_repuesto', 'id');
    }

    /**
     * Relación con DispositivoOt.
     */
    public function dispositivoOt()
    {
        return $this->belongsTo(DispositivoOt::class, 'cod_dispositivo_ot', 'id');
    }
}
