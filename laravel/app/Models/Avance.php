<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avance extends Model
{
    use SoftDeletes;

    protected $table = 'avance';

    protected $primaryKey = 'id';

    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'comentario_avance',
        'fecha_avance',
        'tiempo_avance',
        'cod_ot',
        'imagen',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'fecha_avance',
    ];

    public function ot()
    {
        return $this->belongsTo(Ot::class, 'cod_ot', 'numero_ot');
    }
}
