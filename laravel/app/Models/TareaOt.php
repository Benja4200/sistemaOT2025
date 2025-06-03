<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TareaOt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarea_ot';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'cod_tarea',
        'cod_ot',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationships
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'cod_tarea');
    }

    public function ot()
    {
        return $this->belongsTo(Ot::class, 'cod_ot');
    }
}
