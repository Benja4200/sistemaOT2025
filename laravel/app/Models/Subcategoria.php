<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subcategoria';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_subcategoria',
        'cod_categoria',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'cod_categoria');
    }

    public function lineas()
    {
        return $this->hasMany(Linea::class, 'cod_subcategoria');
    }

}
