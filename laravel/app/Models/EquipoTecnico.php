<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipoTecnico extends Model
{
    use SoftDeletes;

    protected $table = 'equipo_tecnico';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'cod_tecnico',
        'cod_ot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'cod_tecnico', 'id');
    }

    public function ot()
    {
        return $this->belongsTo(Ot::class, 'cod_ot', 'numero_ot');
    }
}
