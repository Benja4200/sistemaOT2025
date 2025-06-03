<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repuesto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'repuesto';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nombre_repuesto',
        'descripcion_repuesto',
        'part_number_repuesto',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function modelos()
    {
        return $this->belongsToMany(Modelo::class, 'modelo_repuesto', 'cod_repuesto', 'cod_modelo');
    }
}
