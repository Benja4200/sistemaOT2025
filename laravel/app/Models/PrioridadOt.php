<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrioridadOt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prioridad_ot';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'descripcion_prioridad_ot',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
