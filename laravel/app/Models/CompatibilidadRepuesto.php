<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompatibilidadRepuesto extends Model
{
    use SoftDeletes;

    protected $table = 'compatibilidad_repuesto';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'cod_modelo',
        'cod_repuesto',
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

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'cod_repuesto', 'id');
    }
}
