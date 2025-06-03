<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarea';

    protected $primaryKey = 'id';

    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'nombre_tarea',
        'tiempo_tarea',
        'cod_servicio',
        'cod_subcategoria',
        'requiere_obs',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'cod_subcategoria');
    }
    
    // Define the relationship
    public function servicio()
    {
        // Especificar la clave primaria de la tabla 'servicio' si es diferente de 'id'
        return $this->belongsTo(Servicio::class, 'cod_servicio', 'id'); // 'id' es la clave primaria de Servicio
    }
    
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_tarea', 'cod_tarea', 'cod_servicio')
                    ->withPivot('precio', 'tiempo') // Incluye los campos adicionales
                    ->withTimestamps(); // Incluye los timestamps
    }
}
