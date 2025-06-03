<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modelo extends Model
{
    use SoftDeletes;

    protected $table = 'modelo';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_modelo',
        'desc_corta_modelo',
        'desc_larga_modelo',
        'part_number_modelo',
        'cod_marca',
        'cod_sublinea',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'cod_marca', 'id');
    }

    public function sublinea()
    {
        return $this->belongsTo(Sublinea::class, 'cod_sublinea', 'id');
    }

    public function dispositivos()
    {
        return $this->hasMany(Dispositivo::class, 'cod_modelo');
    }
    
     public function repuestos()
    {
        return $this->belongsToMany(Repuesto::class, 'modelo_repuesto', 'cod_modelo', 'cod_repuesto');
    }
}
