<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ModeloRepuesto extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $table = 'modelo_repuesto'; // Especifica el nombre de la tabla si no sigue la convención

    protected $fillable = [
        'cod_modelo',
        'cod_repuesto',
    ];

    public $timestamps = true; 
}