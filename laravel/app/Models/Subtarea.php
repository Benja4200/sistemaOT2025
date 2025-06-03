<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subtarea';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'cod_tarea',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationship
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'cod_tarea', 'id'); 
    }
}
