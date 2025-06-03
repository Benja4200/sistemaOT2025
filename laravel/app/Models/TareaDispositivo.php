<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TareaDispositivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tarea_dispositivo';

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'cod_tarea',
        'cod_dispositivo_ot',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationships
    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'cod_tarea');
    }

    public function dispositivoOt()
    {
        return $this->belongsTo(DispositivoOt::class, 'cod_dispositivo_ot');
    }
}
