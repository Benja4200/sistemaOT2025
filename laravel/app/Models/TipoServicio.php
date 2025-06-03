<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoServicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_servicio';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'descripcion_tipo_servicio',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
