<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Linea extends Model
{
    use SoftDeletes;

    protected $table = 'linea';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_linea',
        'cod_subcategoria',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'cod_subcategoria');
    }

    public function sublines()
    {
        return $this->hasMany(Sublinea::class, 'cod_linea');
    }
}
