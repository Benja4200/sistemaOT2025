<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table = 'categoria';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_categoria',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'cod_categoria');
    }
    
    public function serviciosEquipos()
    {
        return $this->belongsToMany(Servicio::class, 'categoria_servicio', 'cod_categoria', 'cod_servicio');
    }
}
